<?php

use App\Base\Exceptions\UniversityMarketException;

abstract class AwsDefaultRegions {

    private static $default_endline = "\r\n";
    private static $default_csv_separator = ';';

    private static function getDefaultRegionsFromFile($file_name) {

        if (!file_exists($file_name))
            throw new UniversityMarketException("Arquivo de regions não encontrado");

        $content_in_string = file_get_contents($file_name);
        $content_in_array = explode(self::$default_endline, $content_in_string);

        $header = explode(self::$default_csv_separator, $content_in_array[0]); // Linha de cabeçalho

        $content = array_slice($content_in_array, 1);

        $result = [];
        foreach ($content as $line) {

            $initial_object = explode(self::$default_csv_separator, $line);

            $result_object = [];

            foreach ($initial_object as $index => $value) {
    
                $result_object[$header[$index]] = $value;
            }

            $result[] = $result_object;
        }

        return $result;
    }

    /**
     * @method to_array
     * @return array List of all availables AWS regions
     */
    public static function to_array() {

        return self::getDefaultRegionsFromFile('./aws-default-regions.csv');
    }

    /**
     * @method region_exists
     * 
     * @param string $key Chave pela qual deseja verificar a existência de uma region
     * @param string $value Valor da chave informada
     * 
     * @example $key => 'name'      ; value => 'South America (São Paulo)'
     * @example $key => 'region'    ; value => 'sa-east-1'
     * 
     * @return boolean If gived region exists
     */
    public static function region_exists($key, $value) {

        if ($key != 'name' && $key != 'region')
            throw new UniversityMarketException("Chave informada inválida. Chaves possíveis: 'name' & 'region'");

        $list = self::to_array();

        foreach ($list as $region) {

            if ($region[$key] == $value) {
                return true;
            }
        }
        return false;
    }
}