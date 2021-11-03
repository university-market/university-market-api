<?php

namespace App\Base\Aws\S3;

use App\Base\Exceptions\UniversityMarketException;

/**
 * Classe Helper para realizar operacoes junto à AWS S3
 */
abstract class S3Helper {

    private static function getAwsS3Client() {

        $key = env('AWS_ACCESS_KEY');
        $secret = env('AWS_SECRET_KEY');

        if (is_null($key) || is_null($secret))
            throw new UniversityMarketException("AWS_ACCESS_KEY e AWS_SECRET_KEY devem ser devidamente configuradas");

        return new S3Base($key, $secret);
    }

    /**
     * Realizar o upload de arquivo para o S3
     * @method upload
     * @param mixed $file Arquivo a ser realizado upload
     * @param string $file_key Chave do arquivo a ser salvo no S3
     * @param callback $callback_error Função de callback a ser executada em caso de erro no upload
     * @return string URL pública do arquivo
     */
    public static function upload($bucket, $file, $file_key, $callback_error = null) {

        $client = self::getAwsS3Client();

        $client->updateBucket($bucket);

        return $client->upload($file, $file_key, $callback_error);
    }

}