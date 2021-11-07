<?php

namespace App\Base\Logs\Logger;

use stdClass;

abstract class UniversityMarketLogger {

    /**
     * Caminho do diretório de armazenamento dos logs do projeto
     * 
     * @property string $log_file_path Diretório de armazenamento de logs
     */
    private static $log_file_path = __DIR__ . '/../../../..' . '/storage/logs/university-market';

    /**
     * Final de linha (EOL - End Of Line) padrão dos arquivos de log persistidos
     * 
     * @property string $log_end_line End Of Line padrão dos arquivos de log
     */
    private static $log_end_line = "\r\n";

    /**
     * Registrar log de operações relevantes realizadas no sistema, persistindo mudanças que aconteceram no contexto da aplicação
     * 
     * @method log
     * 
     * @param UniversityMarketResource $resource Recurso ao qual se refere o log a ser persistido
     * @param int|string $resource_id Id do registro referente ao recurso trabalhado
     * @param StdLogType $log_type Tipo de operação aplicada sobre o recurso
     * @param string $message Mensagem adicional a ser persistida no registro
     * @param int $usuario_id Id do usuário que realizou a operação
     * @param StdLogChange $changes Mudanças aplicadas sobre determinado registro (utilizar método `serialize()` de `StdLogChange`)
     * @example $ `$changes = new StdLogChange()->setBefore(['a' => 'a'])->setAfter(['a' => 'b'])->serialize();`
     * 
     * @return void
     */
    public static function log($resource, $resource_id, $log_type, $message, $usuario_id, $changes = null) {

        $log_model = self::createLogModel($resource, $resource_id, $log_type, $message, $usuario_id, $changes);

        return self::persistLog($log_model);
    }

    /**
     * Cria model de log a ser persistida
     * 
     * @param UniversityMarketResource $resource Recurso ao qual se refere o log a ser persistido
     * @param int|string $resource_id Id do registro referente ao recurso trabalhado
     * @param StdLogType $log_type Tipo de operação aplicada sobre o recurso
     * @param string $message Mensagem adicional a ser persistida no registro
     * @param int $usuario_id Id do usuário que realizou a operação
     * @param StdLogChange $changes Mudanças aplicadas sobre determinado registro (utilizar método `serialize()` de `StdLogChange`)
     * 
     * @return object Model de log pronta para ser persistida
     */
    private static function createLogModel($resource, $resource_id, $log_type, $message, $usuario_id, $changes) {

        $model = new stdClass();

        // Campos customizados do log
        $model->resource = $resource;
        $model->resourceId = $resource_id;
        $model->logType = $log_type;
        $model->message = $message;
        $model->changes = $changes ?? null;
        $model->usuarioId = $usuario_id;

        // Campos padrao do log
        $model->date = date('d-m-Y H:i:s');

        return $model;
    }

    /**
     * Persiste a model de log informada
     * 
     * @method persistLog
     * 
     * @param object $model Model de log a ser persistida
     * 
     * @return void
     */
    private static function persistLog($model) {

        $serialized = json_encode($model);

        $log_file_path = self::getLogFilePath();

        file_put_contents($log_file_path, $serialized . self::$log_end_line, FILE_APPEND);
    }

    private static function getLogFilePath() {

        if (!file_exists(self::$log_file_path)) {

            // Criar diretório de logs do projeto
            mkdir(self::$log_file_path, 0777, true);
        }

        $today = date('Y-m-d');
        $file_name = "university-market-log-$today.log";

        return self::$log_file_path . '/' . $file_name;
    }
}