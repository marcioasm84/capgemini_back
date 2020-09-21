<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLancamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lancamentos', function (Blueprint $table) {
            $table->id();
            
            $table->string('operacao');
            $table->double('valor');
            $table->unsignedBigInteger('conta_id')->references('id')->on('contas');  

            //Poderá ser utilizado para transferências
            //$table->unsignedBigInteger('conta_id_destino')->nullable()->references('id')->on('contas');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lancamentos');
    }
}
