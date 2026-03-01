<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use App\Models\Warranties\Customer;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Spatie\Permission\Models\Role;  

class CreateCustomerUser implements CreatesNewUsers
{
    use PasswordValidationRules; 

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'document_type'   => ['required', 'string', 'max:10'],
            'document_number' => ['required', 'string', 'max:50', 'unique:customers,document_number'],
            'phone'           => ['required', 'string', 'max:20'],
            'address'         => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'        => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            
            $customer = Customer::create([
                'first_name'      => $input['first_name'],
                'last_name'       => $input['last_name'],
                'document_type'   => $input['document_type'],
                'document_number' => $input['document_number'],
                'phone'           => $input['phone'],
                'address'         => $input['address'],
                'is_active'       => true,
            ]);

            $user = $customer->user()->create([
                'name'     => $input['first_name'] . ' ' . $input['last_name'],
                'email'    => $input['email'],
                'password' => $input['password'],
            ]);

            Role::firstOrCreate(['name' => 'CLIENTE', 'guard_name' => 'web']);
            $user->assignRole('CLIENTE');

            return $user;
        });
    }
}