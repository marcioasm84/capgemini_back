<?php

namespace App\Http\Controllers;

use App\Conta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lancamento;

class ContaController extends Controller
{
    public function index() {
        $contas = Conta::all()->load('lancamentos')->toArray();
        
        dd($contas);
    }
    
    public function buscarConta(Request $request) {
        
        //$chave_cliente = $request->input('chave_cliente');
        $agencia = $request->input('agencia');
        $conta = $request->input('conta');
        $senha_cliente = $request->input('senha');
        
        $retorno = new class{};
        
        if( !$agencia || !$conta || !$senha_cliente) {
            $retorno->mensagem = 'Vocé deve informa os dados para acesso. '; 
            $retorno->status = 'erro';
            return response()->json($retorno);
        }
        
        $aAgencia = explode('-', $agencia);
        $aConta = explode('-', $conta);
        
        
        $c_senha_cliente = md5($senha_cliente);
        $conta = Conta::where('agencia', $aAgencia[0])
                        ->where('agencia_digito', $aAgencia[1])
                        ->where('conta', $aConta[0])
                        ->where('conta_digito', $aConta[1])        
                        ->where('senha_cliente', $c_senha_cliente)->first();
        
        if( !$conta) {
            $retorno->mensagem = 'Conta inexistente.';
            $retorno->status = 'erro';
            return response()->json($retorno);
        }
        
        $retorno->mensagem = 'Conta encontrada com sucesso';
        $retorno->status = 'sucesso';
        $retorno->dados = $conta->load('lancamentos');
        return response()->json($retorno);
    }
    
    private function getConta($chave_cliente, $senha_cliente) {
        if( !$chave_cliente || !$senha_cliente) {
            die("Parametros invalidos!");
        }
        
        $c_senha_cliente = md5($senha_cliente);
        $conta = Conta::where('chave_cliente', $chave_cliente)->where('senha_cliente', $c_senha_cliente)->first();
        
        if( !$conta) {
            die("Conta invalida");
        }
        return $conta;
    }
    
    private function getContaPorConta($agencia, $agencia_digito, $conta, $conta_digito ) {
        if( !$agencia || !$agencia_digito || !$conta || !$conta_digito) {
            die("Parametros invalidos!");
        }
        
        $conta = Conta::where('agencia', $agencia)
                       ->where('agencia_digito', $agencia_digito)
                       ->where('conta', $conta)
                       ->where('conta_digito', $conta_digito)
                       ->first();
        
        if( !$conta) {
            die("Conta invalida");
        }
        return $conta;
    }
    
    public function saldo($chave_cliente, $senha_cliente) {
        
        $conta = $this->getConta($chave_cliente, $senha_cliente);
        
        return "O saldo da conta de " . $conta->nome_cliente . " e " . $conta->saldo;
    }
    
    public function deposito(Request $request) {
        
        $chave_cliente = $request->input('chave_cliente');
        $senha_cliente = $request->input('senha');
        $valor = $request->input('valor_deposito');
        
        
        $conta = $this->getConta($chave_cliente, $senha_cliente);
        
        $retorno = new class{};
        
        if(!$valor || !is_numeric($valor) || $valor <= 0) {
            $retorno->mensagem = 'Valores inválidos.';
            $retorno->status = 'erro';
            return response()->json($retorno);
        }
        
        try {
            DB::beginTransaction();
            
            $lancamento = new Lancamento();
            $lancamento->operacao = "DEPOSITO";
            $lancamento->valor = $valor;            
            $conta->lancamentos()->save($lancamento);
            
            $conta->saldo += $valor;
            $conta->save();
            
            DB::commit();
        }catch(\Exception $ex) {
            DB::rollback();
            
            $retorno->mensagem = 'Houve um erro ao fazer o deposito.';
            $retorno->status = 'erro';
            return response()->json($retorno);
        }
        
        $retorno->mensagem = 'Deposito realizado com sucesso.';
        $retorno->status = 'sucesso';
        $retorno->dados = $conta->load('lancamentos');
        return response()->json($retorno);
    }
    
    /*
    public function deposito_terceiro($agencia, $agencia_digito, $conta, $conta_digito, $valor) {
        $conta = $this->getContaPorConta($agencia, $agencia_digito, $conta, $conta_digito );
        
        if(!$valor || !is_numeric($valor) || $valor <= 0) {
            return "Valores invalidos";
        }
        
        try {
            DB::beginTransaction();
            
            $lancamento = new Lancamento();
            $lancamento->operacao = "DEPOSITO";
            $lancamento->valor = $valor;
            $conta->lancamentos()->save($lancamento);
            
            $conta->saldo += $valor;
            $conta->save();
            
            DB::commit();
        }catch(\Exception $ex) {
            DB::rollback();
            return "Houve um erro ao fazer o deposito.";
        }
        return "Deposito de ". $valor. " realizado com sucesso.";
    }*/
    
    public function saque(Request $request) {
        
        $chave_cliente = $request->input('chave_cliente');
        $senha_cliente = $request->input('senha');
        $valor = $request->input('valor_saque');
        
        
        $conta = $this->getConta($chave_cliente, $senha_cliente);
        
        $retorno = new class{};
        
        if(!$valor || !is_numeric($valor) || $valor <= 0) {
            $retorno->mensagem = 'Valores inválidos.';
            $retorno->status = 'erro';
            return response()->json($retorno);
        }       
        
        if($conta->saldo - $valor < 0) {            
            $retorno->mensagem = 'Voce não tem saldo suficiente para esse saque.';
            $retorno->status = 'erro';
            return response()->json($retorno);
        }
        
        try {
            
            DB::beginTransaction();
            
            $lancamento = new Lancamento();
            $lancamento->operacao = "SAQUE";
            $lancamento->valor = $valor;
            $conta->lancamentos()->save($lancamento);
            
            $conta->saldo -= $valor;
            $conta->save();
            
            DB::commit();
        }catch(\Exception $ex) {
            DB::rollback();

            $retorno->mensagem = 'Houve um erro ao fazer o saque.';
            $retorno->status = 'erro';
            return response()->json($retorno);
        }
        
        $retorno->mensagem = 'Saque realizado com sucesso.';
        $retorno->status = 'sucesso';
        $retorno->dados = $conta->load('lancamentos');
        return response()->json($retorno);
    }
}
