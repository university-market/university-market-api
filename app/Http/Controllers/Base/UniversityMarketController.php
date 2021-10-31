<?php

namespace App\Http\Controllers\Base;

use Laravel\Lumen\Routing\Controller as BaseController;

use App\Models\Session\AppSession;
use App\Exceptions\Base\UMException;

define('SESSION_TYPE_ADMIN', 1);
define('SESSION_TYPE_ESTUDANTE', 2);

// Update Application DateTime Format
date_default_timezone_set("America/Sao_Paulo");

class UniversityMarketController extends BaseController {

    /**
     * @property array $content_type Content type to non-null methods
     */
    private $content_type = ['Content-type' => 'application/json'];

    /**
     * @property string $datetime_format Default used datetime format
     */
    protected $datetime_format = "Y-m-d H:i:s";

    /**
     * @property string $auth_token_key Default auth token received in request header
     */
    private $auth_token_key = "um-auth-token";

    /**
     * @property string $session_type Default session type key received in request header
     */
    private $session_type = "session-type";

    /**
     * Convert an or more objects to a specific class.
     * @method cast()
     * @param object|object[] $initial_object Initial object
     * @param string $target_class The class to cast the object to
     * @return object|object[]
     */
    protected function cast($initial_object, $target_class) {

        if ($initial_object === false || is_null($initial_object))
            return null;

        // Class exists test
        if (!$this->try_parse_model($target_class))
            throw new UMException("Classe $target_class não encontrada ao realizar type casting");

        if (!is_array($initial_object)) {

            $final_class = new $target_class();

            foreach ((array)$final_class as $property => $_) {

                if (property_exists($final_class, $property)) {

                    $final_class->$property = is_array($initial_object) ? 
                        $initial_object[$property] : 
                        $initial_object->$property;
                }
            }

            return $final_class;
        }

        $final_collection = [];

        foreach ($initial_object as $obj) {

            $final_class = new $target_class();

            foreach ((array)$final_class as $property => $_) {

                if (property_exists($final_class, $property)) {

                    $final_class->$property = is_array($obj) ? 
                        $obj[$property] : 
                        $obj->$property;
                }
            }

            $final_collection[] = $final_class;
        }

        return $final_collection;
    }

    /**
     * @method response()
     * @param mixed $data Data to send in response instance
     * @return mixed Returns a response in JSON format or only 200 status (OK), if no body
     */
    public function response($data = null) {

        if (is_null($data))
            return response(null, 200);

        return response()->json($data, 200);
    }

    /**
     * @method getSession()
     * @return BaseSession|null Returns a BaseSession instance, or null if unauthorized
     */
    protected function getSession() {

        // Get the current request instance
        $request = request();

        $auth_token = $request->header($this->auth_token_key) ?? null;
        $session_type = $request->header($this->session_type) ?? null;
        
        if (is_null($auth_token))
            return null;

        if (is_null($session_type))
            return null;

        $owner_field_id = $this->get_field_id_by_session_type($session_type);
        
        // Get session from database
        $session = AppSession::with($owner_field_id)->where('token', $auth_token)->first();
        
        if (is_null($session))
            return null;

        // Excluir sessão existente
        if (time() > $session->expiration_time) {

            $this->clear_existing_session($session->usuario_id ?? $session->estudante_id, (int)$session_type);
            return null;
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
     * @method try_parse_model()
     * @param object $class_ref Class reference
     * @return boolean Model is a object or can be find his reference
     */
    private function try_parse_model($class_ref) {

        return is_object($class_ref) || class_exists($class_ref);
    }

    /**
     * @method clear_existing_session()
     * @param integer $owner_id Proprietário da sessão ativa (estudante ou usuário)
     * @param integer $session_type Tipo da sessão a ser considerada (estudante ou usuário)
     * @return void Deleta toda session existente
     */
    private function clear_existing_session($owner_id, $session_type) {

        $field_id = $this->get_field_id_by_session_type($session_type, true);

        $collection_ids = AppSession::select('id')
            ->where($field_id, $owner_id)
            ->get();

        AppSession::destroy($collection_ids);
    }

    /**
     * @method get_field_id_by_session_type()
     * @param integer $session_type Tipo da sessão a ser trabalhada (1 - Admin | 2 - Estudante)
     * @param boolean $only_id Quando verdadeiro, retorna o nome do campo id (FK). Falso ou não definido retorna o nome do campo que referencia a entidade
     * @return string Nome do campo do proprietário da sessão na tabela
     */
    private function get_field_id_by_session_type($session_type, $only_id = false) {

        $field_id = null;

        switch ($session_type) {

            case SESSION_TYPE_ADMIN: // Administrador

                $field_id = $only_id ? 'usuario_id' : 'usuario';
                break;

            case SESSION_TYPE_ESTUDANTE: // Estudante

                $field_id = $only_id ? 'estudante_id' : 'estudante';
                break;
        }

        return $field_id;
    }
}