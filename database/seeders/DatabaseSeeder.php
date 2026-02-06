<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * ZARO Platform için başlangıç test verileri
     */
    public function run(): void
    {
        // Admin kullanıcısı oluştur
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@zaro.com',
            'password' => Hash::make('admin123'),
            'relation_type' => 'Öğretmen',
            'birth_date' => '1990-01-01',
            'kvkk_accepted' => true,
            'email_verified_at' => now(),
        ]);

        // Test anne kullanıcısı - 2 çocuklu
        User::create([
            'name' => 'Ayşe Yılmaz',
            'email' => 'ayse@test.com',
            'password' => Hash::make('password'),
            'relation_type' => 'Anne',
            'birth_date' => '1988-05-15',
            'phone' => '0532 123 4567',
            'kvkk_accepted' => true,
            'children_data' => [
                [
                    'name' => 'Elif Yılmaz',
                    'birth_date' => '2020-03-10',
                    'gender' => 'Kız',
                ],
                [
                    'name' => 'Ahmet Yılmaz',
                    'birth_date' => '2022-07-20',
                    'gender' => 'Erkek',
                ],
            ],
            'email_verified_at' => now(),
        ]);

        // Test baba kullanıcısı - 1 çocuklu
        User::create([
            'name' => 'Mehmet Demir',
            'email' => 'mehmet@test.com',
            'password' => Hash::make('password'),
            'relation_type' => 'Baba',
            'birth_date' => '1985-12-03',
            'phone' => '0533 987 6543',
            'kvkk_accepted' => true,
            'children_data' => [
                [
                    'name' => 'Zeynep Demir',
                    'birth_date' => '2021-01-15',
                    'gender' => 'Kız',
                ],
            ],
            'email_verified_at' => now(),
        ]);

        // Test öğretmen kullanıcısı
        User::create([
            'name' => 'Fatma Kaya',
            'email' => 'ogretmen@test.com',
            'password' => Hash::make('password'),
            'relation_type' => 'Öğretmen',
            'birth_date' => '1992-08-22',
            'phone' => '0534 456 7890',
            'kvkk_accepted' => true,
            'email_verified_at' => now(),
        ]);
    }
}