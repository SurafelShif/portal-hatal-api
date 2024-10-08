<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(title="Portal-Hatal API", version="2")
 *  @OA\SecurityScheme(
 *    securityScheme="bearerAuth",
 *    in="header",
 *    name="bearerAuth",
 *    type="http",
 *    scheme="bearer",
 *    bearerFormat="JWT",
 * ),
 */
abstract class Controller
{
    //
}
