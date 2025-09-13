<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssociationRulesTable extends Migration
{
    public function up()
    {
        Schema::create('association_rules', function (Blueprint $table) {
            $table->id();
            $table->string('product_a');
            $table->string('product_b');
            $table->float('support');
            $table->float('confidence');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('association_rules');
    }
}
