<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    protected $class = Customer::class;
}
