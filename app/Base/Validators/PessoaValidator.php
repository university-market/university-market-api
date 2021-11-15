<?php

namespace App\Base\Validators;

/**
 * Validação de dados do nível Pessoa
 * 
 * @abstract
 * @example CPF, CNPJ
 */
abstract class PessoaValidator {

    /**
     * Validar se CPF é realmente válido - baseado em formatação e cálculo
     * 
     * @method validarCpf
     * @static
     * @param string $cpf CPF a ser validado
     * 
     * @return boolean
     */
    public static function validarCpf($cpf) {

        // Extrai somente números - para casos de máscara
        $value = preg_replace( '/[^0-9]/is', '', $cpf);
         
        // Verifica se foram informados todos os digitos
        if (strlen($value) != 11)
            return false;
    
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $value))
            return false;
    
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $value[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($value[$c] != $d)
                return false;
        }

        return true;
    }
}