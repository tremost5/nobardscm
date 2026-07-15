<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'pram@dscmkids.online'],
            [
                'name' => 'Pram DSCM',
                'password' => bcrypt('Pram1831'),
                'role' => 'superadmin',
                'status' => 'aktif',
                'email_verified_at' => now(),
            ]
        );
    }
}
