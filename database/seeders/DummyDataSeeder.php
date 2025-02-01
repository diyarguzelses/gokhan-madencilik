<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() {
        $faker = Faker::create();

        // Kategoriler için sahte veri ekle
        $categoryIds = [];
        foreach (range(1, 10) as $index) {
            $categoryIds[] = DB::table('categories')->insertGetId([
                'name' => $faker->word . ' Kategorisi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Projeler için sahte veri ekle (Her projeye rastgele bir kategori atanacak)
        foreach (range(1, 10) as $index) {
            DB::table('projects')->insert([
                'name' => $faker->sentence(3),
                'description' => $faker->paragraph(4),
                'status' => $faker->randomElement([0, 1]), // 0 = Devam Eden, 1 = Tamamlanan
                'category_id' => $faker->randomElement($categoryIds), // Rastgele bir kategori ata
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Haberler için sahte veri ekle
        foreach (range(1, 10) as $index) {
            DB::table('news')->insert([
                'title' => $faker->sentence(4),
                'content' => $faker->paragraph(5),
                'image' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Makine Parkı için sahte veri ekle
        foreach (range(1, 10) as $index) {
            DB::table('machines')->insert([
                'name' => $faker->word . ' Makinesi',
                'quantity' => $faker->numberBetween(1, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }}
