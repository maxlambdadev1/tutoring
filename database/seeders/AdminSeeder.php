<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allowed_email = [
            [
                'email' => 'aragon.jefferson89@gmail.com',
                'admin_name' => 'Jefferson Aragon',                
            ],
            [
                'email' => 'nrothquel@gmail.com',
                'admin_name' => 'Nic Rothquel',
            ],
            [
                'email' => 'berishvilitemoraz11@gmail.com',
                'admin_name' => 'Teimuraz Berishvili'
            ],
            [
                'email' => 'kevinmonta08@gmail.com',
                'admin_name' => 'Kevin Monta'
            ],            
        ];
        foreach ($allowed_email as $value) {
            $user = User::create([
                'email' => $value['email'],
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'role' => 1,
                'remember_token' => Str::random(10),
            ]);
            Admin::create([
                'user_id' => $user->id,
                'admin_name' => $value['admin_name'],
                'admin_role_id' => 1,
            ]);            
        }
    }
}
