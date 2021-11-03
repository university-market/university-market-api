<?php

namespace App\Base\Aws\S3;

// Base
use UniversityMarketBuckets;
use App\Base\Exceptions\UniversityMarketException;

// Common
use Exception;
use AwsDefaultRegions;

// AWS S3 Resources
use Aws\S3\S3Client;
use Aws\S3\ObjectUploader;
use Aws\S3\MultipartUploader;
use Aws\Exception\AwsException;
use Aws\Exception\MultipartUploadException;

/**
 * Classe base para estabelecer conexao com o AWS S3
 */
class S3Base {
    
    /**
     * Versao do client do S3 utilizado
     * Values: 'latest'
     */
    private $version = 'latest';

    /**
     * Region onde será buscado o bucket
     */
    private $region = 'sa-east-1';

    /**
     * @property UniversityMarketBuckets Bucket a ser considerado nas operacoes
     */
    private $bucket;

    /**
     * @property array Credenciais utilizadas para estabelecer comunicacao com recursos da AWS
     * @example $credentials = ['key' => 'my_id_key', 'secret' => 'my_secret_key']
     */
    private $credentials = [
        'key'       => null,
        'secret'    => null
    ];

    /**
     * @property string $client S3 Client utilizado por helpers
     */
    protected $client;

    /**
     * É fortemente recomendado que os parâmetros deste método não sejam hardcode
     * Utilize como environment variables
     * 
     * @param string $key ID da chave de acesso à AWS
     * @param string $secret Chave de acesso secreta à AWS
     * 
     * @return void
     */
    public function __construct($key, $secret)
    {
        if (is_null($key) || empty($key))
            throw new UniversityMarketException("Uma chave válida deve ser fornecida para estabelecer conexão com o S3");

        if (is_null($secret) || empty($secret))
            throw new UniversityMarketException("Uma secret válida deve ser fornecida para estabelecer conexão com o S3");

        $this->credentials['key'] = $key;
        $this->credentials['secret'] = $secret;
    }

    private function getClient() {

        return new S3Client([
            'version'     => 'latest',
            'region'      => $this->region,
            'credentials' => $this->credentials
        ]);
    }

    // Upload

    public function upload($file, $file_key, $callback_error = null) {

        $uploader = new MultipartUploader($this->getClient(), $file, [
            'bucket' => $this->bucket,
            'key' => $file_key,
        ]);

        try {

            $result = $uploader->upload();

            return $result['ObjectURL'];
        }
        catch (MultipartUploadException | ObjectUploader | AwsException | Exception $e) {

            echo '';
            $callback_error();
        }

        return null;
    }


    /**
     * Atualizar region padrão utilizada na conexao
     * @method updateRegion
     */
    protected function updateRegion($region) {

        if (is_null($region) || empty($region))
            throw new UniversityMarketException("Uma region válida deve ser fornecida para estabelecer conexão com o S3");

        if (AwsDefaultRegions::region_exists('region', $region))
            throw new UniversityMarketException("Uma region válida deve ser fornecida para estabelecer conexão com o S3");

        $this->region = $region;
    }

    /**
     * Atualizar bucket para realizar operacao
     * @method updateBucket
     */
    public function updateBucket($bucket) {

        if (is_null($bucket))
            throw new UniversityMarketException("Bucket informado não foi devidamente configurado");

        if (!property_exists(UniversityMarketBuckets::class, $bucket))
            throw new UniversityMarketException("Bucket informado não foi devidamente configurado");

        $this->bucket = $bucket;
    }

}