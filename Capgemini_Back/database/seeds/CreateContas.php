<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateContas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contas')->delete();
        
        DB::table('contas')->insert([
            'nome_cliente' => 'Carlos Santana',
            'agencia' => '1159',
            'agencia_digito' => '1',
            'conta' => '123456',
            'conta_digito' => '2',
            'chave_cliente' => 'e10adc3949ba59abbe56e057f20f883e',
            'senha_cliente' => md5('543210'),
            'saldo' => 0
        ]);
        
        DB::table('contas')->insert([
            'nome_cliente' => 'Mônica Martins',
            'agencia' => '2248',
            'agencia_digito' => '2',
            'conta' => '456789',
            'conta_digito' => '3',
            'chave_cliente' => 'e35cf7b66449df565f93c607d5a81d09',
            'senha_cliente' => md5('987654'),
            'saldo' => 0
        ]);
        
    }
}
