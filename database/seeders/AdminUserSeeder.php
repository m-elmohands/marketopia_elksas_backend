<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@elksas.test'], [
            'name' => 'El-Ksas Admin',
            'password' => 'password',
            'is_admin' => true,
        ]);
    }
}
