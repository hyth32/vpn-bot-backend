<?php

namespace App\Console\Commands;

use App\Http\Enums\NotificationType;
use App\Http\Integrations\RabbitMQService;
use App\Http\Repositories\WireGuardRepository;
use App\Models\Key;
use Illuminate\Console\Command;

class DeleteExpiredKeys extends Command
{
    protected $signature = 'delete-expired-keys';

    protected $description = 'Удаление ключей через неделю после истечения срока жизни';

    public function handle(
        RabbitMQService $rabbitMQService,
        WireGuardRepository $wireGuardRepository,
    ) {
        $keys = Key::where('expiration_date', '<=', now()->addWeek())
            ->with(['order.user', 'order.region']);

        if ($keys->exists()) {
            $keys->cursor()->each(function (Key $key) use ($rabbitMQService, $wireGuardRepository) {
                $telegramId = $key->order->user->telegram_id;

                $keyName = $key->config_name;
                $regionName = $key->order->region->name;
                $regionFlag = $key->order->region->flag;

                $wireGuardRepository->deleteConfig($key->config_id);
                $key->delete();

                $rabbitMQService->publish('notifications', [
                    'telegram_id' => $telegramId,
                    'type' => NotificationType::KeyDeleted->value,
                    'key' => [
                        'name' => $keyName,
                        'region_name' => $regionName,
                        'region_flag' => $regionFlag,
                    ],
                ]);
            });
        }
    }
}
