<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
  public function run()
  {
    $defaultLocale = config('app.defaultLocale', 'id'); // fallback kalau null

    $categories = [
      'Antibiotik & Antimikroba',
      'Analgesik, Antipiretik & Anti-Inflamasi',
      'Obat Saluran Pernapasan & Antialergi',
      'Kardiovaskular & Diuretik',
      'Obat Pencernaan',
      'Suplemen & Vitamin',
      'Obat Sistem Saraf & Neuropati',
      'Obat Kulit & Topikal',
      'Obat Lain-lain'
    ];

    foreach ($categories as $index => $categoryName) {
      // Insert ke tabel categories
      $categoryId = DB::table('categories')->insertGetId([
        'parent' => 0,
        'position' => $index + 1,
        'url' => Str::slug($categoryName),
      ]);

      // Insert ke tabel categories_translations
      DB::table('categories_translations')->insert([
        'for_id' => $categoryId,
        'name' => $categoryName,
        'locale' => $defaultLocale
      ]);
    }
  }
}
