<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\SharedHelpers;

class BaseController extends Controller
{
    use ApiResponse, SharedHelpers;
}
