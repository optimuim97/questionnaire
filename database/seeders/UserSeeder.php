<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Administrateur',
                'email'    => 'admin@questionnaire.com',
                'password' => Hash::make('admin1234'),
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(['email' => $data['email']], $data);
        }

        $this->command->info('✓ Utilisateur admin créé : admin@questionnaire.com / admin1234');
    }
}
