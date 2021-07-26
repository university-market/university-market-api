<?php

define('DEFAULT_START_SCRIPT_NAME', 'Script0');
define('DATA_SOURCE_PATH', '../data/db-control.json');
define('ENDLINE', '<br>');

require_once './core/database.class.php';

class ScriptControl {

    public $executed = [];

    private $last_update = null;
    private $not_executed = [];

    private $data_source_path = null;

    public function __construct($data_source_path = null) {

        // Definindo data source dos scripts do banco
        $this->data_source_path = $data_source_path ?? DATA_SOURCE_PATH;

        // Exception se data source informado não existir
        if (!file_exists($this->data_source_path))
            throw new Exception("Data source não existe!");

        $data = json_decode(file_get_contents($this->data_source_path));

        $this->populate($data);
    }

    public function initializeOperation() {

        $scripts = $this->readScriptsOfDirectory();

        $this->clearExecuted($scripts);
    }

    public function getLastExec() {

        return $this->last_update;
    }

    public function executeScripts() {

        $this->last_update = date('d/m/Y');

        $db = new DatabaseClass();

        echo 'Operação iniciada!'.ENDLINE;

        foreach ($this->not_executed as $script) {

            $sql = $this->readSQL($script);

            $db->executeScript($sql);

            echo $script . ' executado!' . ENDLINE;
            echo $script . ' salvo como executado! ' . ENDLINE;

            $this->registerAsExecuted($script);
        }

        echo 'Operação finalizada!'.ENDLINE;
    }

    public function finalizeOperation() {

        $content = new stdClass;

        $content->last_update = $this->last_update;
        $content->executed = $this->executed;
        $content->not_executed = $this->not_executed;

        $serialized_content = json_encode($content);

        file_put_contents($this->data_source_path, $serialized_content);
    }

    // Private methods

    private function populate($data) {

        $this->last_update = $data->last_update;
        $this->executed = $data->executed;
    }

    private function readScriptsOfDirectory() {

        $source = scandir('..');
        $temp = [];

        foreach ($source as $value) {

            if (strpos($value, DEFAULT_START_SCRIPT_NAME) !== false) {

                $temp[] = $value;
            }
        }

        return $temp;
    }

    private function clearExecuted($source_scripts) {

        foreach ($source_scripts as $script) {

            if (!in_array($script, $this->executed)) {
                $this->not_executed[] = $script;
            }
        }
    }

    private function readSQL($script_name) {

        if (!file_exists('../' . $script_name))
                throw new Exception('Este script não existe');

        $sql = file_get_contents('../' . $script_name);

        $sql = trim($sql);

        if ($sql[strlen($sql)-1] != ';')
            $sql.= ';';
        
        return $sql;
    }

    private function registerAsExecuted($script_name) {

        foreach ($this->not_executed as $key => $not_executed_script) {

            if ($not_executed_script == $script_name) {

                $this->executed[] = $script_name;
                unset($this->not_executed[$key]);
                break;
            }
        }
    }
}

function show($data, $die = false) {

    $color = '#fff';
    $bg = '#3e3e3e';

    echo "<div style='color: $color; background: $bg; padding: 10px; margin: 10px 0px; display: block;'>";

    echo '<pre>';
    print_r($data);
    echo '</pre>';

    echo "<small>".var_dump($data)."</small>";

    if ($die)
        die('A execução do programa foi interrompida.');

    echo '</div>';
}