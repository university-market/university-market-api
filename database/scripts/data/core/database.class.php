<?php

class DatabaseClass {

    public $db;

    /**
     * @method getDb() Realiza a conexão com o serviço de banco de dados
     * @return object Objeto PDO contendo os parâmetros da conexão
     * @return boolean False caso não seja possível se conectar ao banco de dados
     */
    public function __construct() {
    
        try {

            $file_path = './db_env.ini';

            if (!file_exists($file_path))
                throw new Exception("Database env not found!");

            $ini = parse_ini_file($file_path, true); 
            $a = $ini['database'];

            $config = "mysql:host={$a['host']};dbname={$a['database']}"; 
            $config.= (isset($a['collation'])) ? ";charset={$a['collation']}":''; 

            $this->db = new \PDO(
                $config, 
                $a['username'], $a['password']
            ); 

        } catch (\PDOException $e) {

            throw new Exception("Um erro ocorreu na execução do programa de inicialização do BD.");
        }

        return $this->db;
    }
}