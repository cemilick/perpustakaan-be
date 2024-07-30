<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    protected $class = Customer::class;

    public function getCustomerOptions()
    {
        $customer = new Customer();
        $customer = $customer->getOptions();

        return $this->responseJson(array_merge([
            [
                'value' => null,
                'label' => 'Pilih Customer'
            ]
        ], collect($customer)->map(function ($item) {
            return [
                'value' => $item->id,
                'label' => $item->nama
            ];
        })->toArray()));
    }
}
