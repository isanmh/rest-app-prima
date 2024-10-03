<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     description="API documentation Training RESTful API PT Prima",
 *     version="1.0.0",
 *     title="API Training",
 *     termsOfService="http://swagger.io/terms/",
 *     @OA\Contact(
 *         email="isan@gmail.com"
 *     ),
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
