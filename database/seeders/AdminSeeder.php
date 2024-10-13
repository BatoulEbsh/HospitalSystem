<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user=User::query()->create([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin')
        ]);
        $user->roles()->attach([1]);
    }
}
