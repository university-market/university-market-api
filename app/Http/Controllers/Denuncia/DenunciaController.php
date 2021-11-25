<?php

namespace App\Http\Controllers\Estudante;

use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;
use App\Base\Logs\Logger\UniversityMarketLogger;
use App\Base\Logs\Type\StdLogChange;
use App\Base\Logs\Type\StdLogType;
use App\Base\Resource\UniversityMarketResource;

// Entidades
use App\Models\Denuncia\Denuncia;
use App\Models\Denuncia\TipoDenuncia;
use App\Models\Publicacao\Publicacao;

// Models de denúncia utilizadas
use App\Http\Controllers\Denuncia\Models\TipoDenunciaPublicacaoModel;

class DenunciaController extends UniversityMarketController {

    /**
     * Denunciar publicação
     * 
     * @method denunciar
     * @param Request $request Instância de requisição - cast para model `PublicacaoDenunciaModel`
     * 
     * @type Http POST
     * @route `/{publicacaoId}`
     */
    public function denunciar($publicacaoId, Request $request) {

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $model = $this->cast($request, PublicacaoDenunciaModel::class);

        $publicacao = Publicacao::find($model->publicacao_id);

        if (is_null($publicacao))
            throw new UniversityMarketException("Publicação não encontrada");

        $denuncia = new Denuncia();

        $denuncia->descricao = $model->motivo;
        $denuncia->estudante_id_autor = $session->estudante_id;
        $denuncia->estudante_id_denunciado = $model->estudante_id_denunciado;
        $denuncia->publicacao_id = $model->publicacao_id;
        $denuncia->tipo_denuncia_id = $model->tipo_denuncia_id;

        $denuncia->save();

        // Persistir log de criacao de edição da publicacao
        UniversityMarketLogger::log(
            UniversityMarketResource::$denuncia,
            $denuncia->id,
            StdLogType::$criacao,
            "Denúncia criada para a publicação",
            $session->estudante_id,
            null
        );
    }

    /**
     * Listar tipos de denúncias disponíveis para a Publicação
     * 
     * @method listarTiposDenuncias
     * 
     * @type Http GET
     * @route `/tipos`
     */
    public function listarTiposDenuncias() {

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $denuncias = TipoDenuncia::get();

        $list = [];

        foreach ($denuncias as $denucia) {
            
            $model = new TipoDenunciaPublicacaoModel();

            $model->id = $denucia->id;
            $model->descricao = $denucia->descricao;

            $list[] = $model;
        }

        return $this->response($list);
    }

}