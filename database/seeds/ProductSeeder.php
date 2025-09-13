<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
  public function run()
  {
    $defaultLocale = config('app.defaultLocale', 'id');

    // Mapping kategori
    $productsByCategory = [
      1 => [ // Antibiotik & Antimikroba
        'Amoksilin',
        'Ampicilin',
        'Ciprofloxacin',
        'Erlamoxy',
        'Amoxan',
        'Azithromycin',
        'Ethambutol',
        'Cefadroxil',
        'Metronidazol'
      ],
      2 => [ // Analgesik, Antipiretik & Anti-Inflamasi
        'As. Mafenamat',
        'Antalgin',
        'Meloxicam',
        'Ponstan',
        'Paracetamol',
        'Ibuprofen',
        'Asam efenamat',
        'Nonflamin',
        'Anastan',
        'Rhelafen forte',
        'Bodrek',
        'Sanmol'
      ],
      3 => [ // Obat Saluran Pernapasan & Antialergi
        'Ambroxol',
        'Ceterizin',
        'Buscopan',
        'Obhcombi',
        'Asmet',
        'Paratusin',
        'Siladex'
      ],
      4 => [ // Kardiovaskular & Diuretik
        'Amlodipin',
        'Catopril 12,5',
        'Furosemide',
        'Sinvastatin',
        'HCT',
        'Spironolactone',
        'Aspilet'
      ],
      5 => [ // Obat Pencernaan
        'Donperidone',
        'Ranitidin',
        'Lansoprazole',
        'Sucralfate',
        'Ondansetron',
        'Gazero',
        'Lopamid',
        'Omeprazole'
      ],
      6 => [ // Suplemen & Vitamin
        'Hemaviton',
        'Visite',
        'Enervon c',
        'Redoxon',
        'Sangobion',
        'CDR',
        'Curcuma',
        'Glucosamine'
      ],
      7 => [ // Obat Sistem Saraf & Neuropati
        'Carbidu 0,5',
        'Mecobalamin',
        'Histigo',
        'Gabapentin',
        'Novorapid',
        'Braxidin',
        'Imbosit',
        'Betasen'
      ],
      8 => [ // Obat Kulit & Topikal
        'Acylovir 200mg',
        'Scannovir Cream',
        'Acifar',
        'Caladine'
      ],
      9 => [ // Obat Lain-lain
        'Bufacaryl',
        'Opistan',
        'Vesperum',
        'Bimastan',
        'Gluconic',
        'Omeroxol',
        'Kaditic',
        'Ursodeoxycholic acid',
        'Methylprednisolone',
        'Diclofenac'
      ],
    ];

    foreach ($productsByCategory as $categoryId => $products) {
      foreach ($products as $product) {
        $id = DB::table('products')->insertGetId([
          'image' => '',
          'folder' => time(),
          'category_id' => $categoryId,
          'quantity' => 100,
          'order_position' => 0,
          'link_to' => '',
          'tags' => '',
          'hidden' => 0,
          'url' => Str::slug($product),
          'created_at' => now(),
          'updated_at' => now(),
        ]);

        DB::table('products_translations')->insert([
          'for_id' => $id,
          'name' => $product,
          'description' => "Deskripsi untuk produk {$product}. Obat ini digunakan sesuai dengan anjuran dokter.",
          'price' => '10000', // contoh harga
          'locale' => $defaultLocale,
        ]);
      }
    }
  }
}
