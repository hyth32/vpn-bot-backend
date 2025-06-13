<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="VPN Bot API Documentation",
 *     description="API documentation for VPN Bot Backend"
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 * 
 * @OA\PathItem(
 *     path="/api"
 * )
 */
abstract class Controller
{
    //
}
