<?php

namespace App\Http\Enums;

enum OrderMessageAction: string
{
    case Create = 'create';
    case Renew = 'renew';
    case Free = 'free-key';
}
