<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssociationRulesSeeder extends Seeder
{
    public function run()
    {
        $rules = [
            ['product_a' => 'Nonflamin',   'product_b' => 'Amoksilin',  'support' => 0, 'confidence' => 0],
            ['product_a' => 'Redoxon',     'product_b' => 'Amoksilin',  'support' => 0, 'confidence' => 0],
            ['product_a' => 'Cefadroxil',  'product_b' => 'Amoksilin',  'support' => 0, 'confidence' => 0],
            ['product_a' => 'Nonflamin',   'product_b' => 'Sanmol',     'support' => 0, 'confidence' => 0],
            ['product_a' => 'Sanmol',      'product_b' => 'Nonflamin',  'support' => 0, 'confidence' => 0],
            ['product_a' => 'Sanmol',      'product_b' => 'Redoxon',    'support' => 0, 'confidence' => 0],
            ['product_a' => 'Sanmol',      'product_b' => 'Cefadroxil', 'support' => 0, 'confidence' => 0],
            ['product_a' => 'Cefadroxil',  'product_b' => 'Sanmol',     'support' => 0, 'confidence' => 0],
            ['product_a' => 'Cefadroxil',  'product_b' => 'CDR',        'support' => 0, 'confidence' => 0],
            ['product_a' => 'CDR',         'product_b' => 'Cefadroxil', 'support' => 0, 'confidence' => 0],
        ];

        DB::table('association_rules')->truncate();
        DB::table('association_rules')->insert($rules);
    }
}
