<?php

namespace App\Common\Constants;

use Exception;

abstract class UniversityMarketConstants {

    /**
     * Configurações de senha da plataforma University Market
     */
    public static function password()  {

        $constant_reader = new UniversityMarketConstantsReader(true);

        return $constant_reader->get('password');
    }
}

//==============================================================================================

class UniversityMarketConstantsReader {

    /**
     * Path do arquivo que contém as constantes relevantes do sistema
     */
    private $const_ini_file_path = 'constants.ini';

    /**
     * Flag para preservar tipagem no parse do arquivo ou manter tipagem de string (padrão)
     */
    private $ini_file_typed = false;

    /**
     * Conteúdo do .ini deserializado
     */
    private $content = [];

    /**
     * @param boolean $manter_tipagem_na_leitura Quando true, a tipagem dos valores é mantida
     * 
     * @see https://www.php.net/manual/en/function.parse-ini-file.php - `$scanner_mode` (`INI_SCANNER_NORMAL`|`INI_SCANNER_RAW`|`INI_SCANNER_TYPED`)
     */
    function __construct($manter_tipagem_na_leitura = false)
    {
        $scanner_mode = $manter_tipagem_na_leitura ? INI_SCANNER_TYPED : INI_SCANNER_NORMAL;
        
        $this->content = parse_ini_file($this->const_ini_file_path, true, $scanner_mode);
    }

    /**
     * Obtém o valor (inclusive subseções - se houver) da chave especificada
     * 
     * @method get
     * @param string $const_key Chave da constante contida no source de constantes
     * 
     * @return mixed Valor correspondente à chave especificada
     * 
     * @throws ConstantNotFoundException Se a chave especificada não existir
     */
    public function get($const_key) {
        
        if (!key_exists($const_key, $this->content))
            throw new ConstantNotFoundException("Specified const_key not exists in constants source");

        return $this->content[$const_key];
    }
}

//==============================================================================================

final class ConstantNotFoundException extends Exception {

    /**
     * @param string `$message` Mensagem a ser lançada na exceção
     * @param int `$code` Código da exceção
     */
    public function __construct($message = "", $code = 0) {

        parent::__construct($message, $code);
    }
}