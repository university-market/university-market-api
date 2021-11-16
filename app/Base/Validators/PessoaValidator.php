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
     * @param string $data_nascimento Data de nascimento - Formato (`yyyy-mm-dd`) - (`Y-m-d`)
     * 
     * @return boolean
     */
    public static function validarMaioridade($data_nascimento) {

        $today = new DateTime(date('Y-m-d'));

        $initial_date = new DateTime(date($data_nascimento));

        return $initial_date->diff($today)->y < 18;
    }

    /**
     * Validar CNPJ da instituição - baseado em formatação e cálculo
     * 
     * @method validarCnpj
     * @static
     * @param string $cnpj - CNPJ a ser validado
     * 
     * @return boolean
     */
    public static function validarCnpj($cnpj) {

            // Verifica se um número foi informado
            if(empty($cnpj)) {
                return false;
            }
        
            // Elimina possivel mascara
            $cnpj = preg_replace("/[^0-9]/", "", $cnpj);
            $cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);
            
            // Verifica se o numero de digitos informados é igual a 11 
            if (strlen($cnpj) != 14) {
                return false;
            }
            
            // Verifica se nenhuma das sequências invalidas abaixo 
            // foi digitada. Caso afirmativo, retorna falso
            else if ($cnpj == '00000000000000' || 
                $cnpj == '11111111111111' || 
                $cnpj == '22222222222222' || 
                $cnpj == '33333333333333' || 
                $cnpj == '44444444444444' || 
                $cnpj == '55555555555555' || 
                $cnpj == '66666666666666' || 
                $cnpj == '77777777777777' || 
                $cnpj == '88888888888888' || 
                $cnpj == '99999999999999') {
                return false;
                
             // Calcula os digitos verificadores para verificar se o
             // CNPJ é válido
             } else {   
             
                $j = 5;
                $k = 6;
                $soma1 = 0;
                $soma2 = 0;
        
                for ($i = 0; $i < 13; $i++) {
        
                    $j = $j == 1 ? 9 : $j;
                    $k = $k == 1 ? 9 : $k;
        
                    $soma2 += ($cnpj{$i} * $k);
        
                    if ($i < 12) {
                        $soma1 += ($cnpj{$i} * $j);
                    }
        
                    $k--;
                    $j--;
        
                }
        
                $digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
                $digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;
        
                return (($cnpj{12} == $digito1) and ($cnpj{13} == $digito2));
             
            }
        }
    }