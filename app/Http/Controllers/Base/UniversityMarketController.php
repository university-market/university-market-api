<?php

namespace App\Http\Controllers\Base;

use App\Models\Session\AppSession;
use DateTime;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

define('SESSION_TYPE_ADMIN', 1);
define('SESSION_TYPE_ESTUDANTE', 2);

// Update Application DateTime Format
date_default_timezone_set("America/Sao_Paulo");

class UniversityMarketController extends BaseController {

    /**
     * @property array $contentType Content type to non-null methods
     */
    protected $contentType = ['Content-type' => 'application/json'];

    /**
     * @property string $dateTimeFormat Default used datetime format
     */
    protected $dateTimeFormat = "Y-m-d H:i:s";

    /**
     * @property string $authTokenKey Default auth token received in request header
     */
    private $authTokenKey = "um-auth-token";

    /**
     * @property string $sessionType Default session type key received in request header
     */
    private $sessionType = "session-type";

    /**
     * Convert an or more objects to a specific class.
     * @method cast()
     * @param object|object[] $object Initial object
     * @param string $class_name The class to cast the object to
     * @return object|object[]
     */
    protected function cast($object, $class_name) {

        if ($object === false || \is_null($object)) return null;

        // Test class exists
        if (\is_null($this->makeModel($class_name)))
            throw new \Exception("Não foi possível encontrar a classe $class_name para realizar o type casting");

        if (!is_array($object)) {

            $finalClass = $this->makeModel($class_name);

            foreach ((array)$finalClass as $property => $value)
                if (\property_exists($finalClass, $property))
                    $finalClass->$property = \is_array($object) ? $object[$property] : $object->$property;

            return $finalClass;
        }

        $finalCollection = [];

        foreach ($object as $obj) {

            $finalClass = $this->makeModel($class_name);

            foreach ((array)$finalClass as $property => $value)
                if (\property_exists($finalClass, $property))
                    $finalClass->$property = \is_array($obj) ? $obj[$property] : $obj->$property;

            $finalCollection[] = $finalClass;
        }
        return $finalCollection;
    }

    private function makeModel($class_ref) {

        return \is_object($class_ref) ? $class_ref : 
            (class_exists($class_ref) ? new $class_ref() : null);
    }

    /**
     * @method getSession()
     * @return BaseSession|false Returns a BaseSession object or false if unauthorized
     */
    protected function getSession() {

        $request = request();

        $authToken = $request->header($this->authTokenKey) ?? null;
        $sessionType = $request->header($this->sessionType) ?? null;
        
        if (is_null($authToken))
            return false;

        if (is_null($sessionType))
            return false;

        $session = null;

        switch ((int)$sessionType) {

            case SESSION_TYPE_ADMIN: // Administrador
                $session = null;
                break;

            case SESSION_TYPE_ESTUDANTE: // Estudante
                $session = AppSession::where('sessionToken', $authToken)->first();
                break;
        }
        
        if (is_null($session))
            return false;

        // Excluir sessão existente
        if (time() > $session->expirationTime) {

            $this->clearExistingSession($session->sessionId, $session->usuarioId ?? $session->estudanteId, (int)$sessionType);
            return false;
        }

        return $session;
    }

    /**
     * @method unauthorized()
     * @return Response Returns a unauthorized response (code 401)
     */
    protected function unauthorized($mensagem = null) {

        return response($mensagem ?? "Operação não autorizada", 401);
    }

    /**
     * @method clearExistingSession()
     * @param integer $sessionId Id da session que deseja apagar
     * @param integer $ownerId Proprietário da sessão ativa (estudante ou usuário)
     * @param integer $sessionType Tipo da sessão a ser considerada (estudante ou usuário)
     * @return void Apaga a session existente
     */
    private function clearExistingSession($sessionId, $ownerId, $sessionType) {

        switch ($sessionType) {

            case SESSION_TYPE_ADMIN: // Administrador

                $session = null;

                break;

            case SESSION_TYPE_ESTUDANTE: // Estudante

                $sessionIds = AppSession::select('sessionId')
                    ->where('estudanteId', $ownerId)
                    ->get();

                AppSession::destroy($sessionIds);

                break;
        }
    }
}