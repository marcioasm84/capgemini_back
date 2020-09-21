<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->string('nome_cliente');
            $table->unsignedInteger('agencia');
            $table->unsignedInteger('agencia_digito');
            $table->unsignedBigInteger('conta');
            $table->unsignedInteger('conta_digito');
            $table->string('chave_cliente');
            $table->string('senha_cliente');
            $table->double('saldo'); 
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
        Schema::dropIfExists('contas');
    }
}
