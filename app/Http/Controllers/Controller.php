<?php

namespace App\Http\Controllers;

// Annotations

use OpenApi\Annotations;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Dental Clinic API",
 *     description="API documentation for the Dental Clinic system",
 *     @OA\Contact(
 *         email="support@yourclinic.com",
 *         name="Support Team"
 *     ),
 * ),
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Main API Server"
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
