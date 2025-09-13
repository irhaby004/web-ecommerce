<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAprioriRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apriori_rules', function (Blueprint $table) {
            $table->id();
            $table->json('antecedent'); // Produk A
            $table->json('consequent'); // Produk B
            $table->float('support'); // Support (%)
            $table->float('confidence'); // Confidence (%)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('apriori_rules');
    }
}
