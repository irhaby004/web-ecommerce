<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAprioriItemsetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apriori_itemsets', function (Blueprint $table) {
            $table->id();
            $table->json('itemset'); // Simpan kombinasi produk dalam bentuk array JSON
            $table->float('support'); // Nilai support (%)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('apriori_itemsets');
    }
}
