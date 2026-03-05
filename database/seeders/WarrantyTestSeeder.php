<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warranties\Customer;
use App\Models\Warranties\WarrantyRequest;

class WarrantyTestSeeder extends Seeder
{
    public function run(): void
    {
        Customer::factory()
            ->count(4)
            ->create()
            ->each(function (Customer $customer) {

                $customer->warrantyRequests()->saveMany(
                    WarrantyRequest::factory()
                        ->count(rand(1,4))
                        ->make()
                );

            });
    }
}