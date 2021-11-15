<?php

namespace App\Base\Validators;

// Utils
use DateTime;

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


    /**
     * Validar se e-mail é realmente válido - baseado em formatação
     * 
     * @method validarEmail
     * @static
     * @param string $email E-mail a ser validado
     * 
     * @return boolean
     */
    public static function validarEmail($email) {

        // Remove os caracteres ilegais, caso tenha
        $value = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Valida o e-mail
        if (!filter_var($value, FILTER_VALIDATE_EMAIL))
            return false;

        return true;
    }

    /**
     * Validar maioridade civil da pessoa - com base em sua data de nascimento
     * 
     * @method validarMaioridade
     * @static
     * @param string $data_nascimento Data de nascimento - Formato (yyyy-mm-dd) - (Y-m-d)
     * 
     * @return boolean
     */
    public static function validarMaioridade($data_nascimento) {

        $today = new DateTime(date('Y-m-d'));

        $initial_date = new DateTime(date($data_nascimento));

        return $initial_date->diff($today)->y < 18;
    }
}