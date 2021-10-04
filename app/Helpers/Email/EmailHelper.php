<?php

namespace App\Helpers\Email;

use Illuminate\Support\Facades\Http;
use App\Models\Session\BaseSession;
use Exception;

abstract class EmailHelper {

    /**
     * Método estático para enviar e-mail com base na API definida no environment
     * @param BaseSession $session Application base session
     * @param object $data Dados a serem enviados na requisição para a API
     * @param string $template Identificador do template de e-mail a ser enviado pela API
     * @return object The request body response
     */
    public static function send($session, array $data, string $template) {

        // Prepare the request, adding headers, validation token and others
        $req = self::prepare($session);

        return self::doRequest($req, $data, $template);
    }

    /**
     * Prepare the request before send to target
     * @param BaseSession $session Application base session
     * @return Http A HttpClient instance
     */
    private static function prepare(BaseSession $session) {

        return $http = Http::acceptJson();
    }

    /**
     * Send the http request and returns the response body
     * @param Http $req The request instance, prepared to be send
     * @param object $data The data object to go in the request body
     * @param string $requestType The request type (POST default)
     * @return object The request body response
     */
    private static function doRequest($req, array $data, string $template, string $requestType = 'POST') {

        $api_route = env('EMAIL_SERVICE_API_URL');
        $api_route.= "/$template";

        if (is_null($api_route))
            throw new Exception("Variável de ambiente EMAIL_SERVICE_API_URL não foi definida");

        $response = null;

        switch($requestType) {

            case 'POST':
                $response = $req->post($api_route, $data)->body();
                break;

            default:
        }

        return $response;
    }

}