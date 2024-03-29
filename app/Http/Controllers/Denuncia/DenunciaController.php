<?php

namespace App\Http\Controllers\Denuncia;

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
use App\Http\Controllers\Denuncia\Models\PublicacaoDenunciaModel;
use App\Http\Controllers\Denuncia\Models\RequestListagemDenunciasModel;
use App\Http\Controllers\Denuncia\Models\DenunciaListaModel;

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

        $publicacao = Publicacao::find($publicacaoId);

        if (is_null($publicacao))
            throw new UniversityMarketException("Publicação não encontrada");

        $denuncia = new Denuncia();

        $denuncia->descricao = $model->motivo;
        $denuncia->estudante_id_autor = $session->estudante_id;
        $denuncia->estudante_id_denunciado = $model->estudante_id_denunciado;
        $denuncia->publicacao_id = $publicacaoId;
        $denuncia->tipo_denuncia_id = $model->tipo_denuncia_id;

        $denuncia->save();

        // Persistir log de criacao de edição da publicacao
        UniversityMarketLogger::log(
            UniversityMarketResource::$denuncia,
            $denuncia->id,
            StdLogType::$criacao,
            "Denúncia criada para a publicação $publicacaoId",
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

        // $session = $this->getSession();

        // if (!$session)
        //     return $this->unauthorized();

        $denuncias = TipoDenuncia::get();

        $list = [];

        foreach ($denuncias as $denuncia) {
            
            $model = new TipoDenunciaPublicacaoModel();

            $model->id = $denuncia->id;
            $model->descricao = $denuncia->descricao;

            $list[] = $model;
        }

        return $this->response($list);
    }

    /**
     * Listar denúncias realizadas
     * 
     * @method listarDenunciasRealizadas
     * @param Request $request Instância Requisição - cast to model `RequestListagemDenunciasModel`
     * 
     * @type Http GET
     * @route `/listar`
     */
    public function listarDenunciasRealizadas(Request $request) {

        // $session = $this->getSession();

        // if (is_null($session))
        //     return $this->unauthorized();

        $request_model = $this->cast($request, RequestListagemDenunciasModel::class);

        $denuncias = Denuncia::with('estudante_autor', 'estudante_denunciado')
            ->orderBy('created_at', 'DESC')
            ->get();

        // dd($denuncias);

        $list = [];

        foreach ($denuncias as $denuncia) {

            // Validações - filtragem por situacao
            if (!is_null($request_model->situacao) && $request_model->situacao != "null") {

                if ($denuncia->situacao_denuncia_id != $request_model->situacao)
                    continue;
            }

            // Validações - filtragem por tipos
            if (!is_null($request_model->tipos) && !empty($request_model->tipos)) {

                if (count(json_decode($request_model->tipos)) > 0) {

                    if (!in_array($denuncia->tipo_denuncia_id, json_decode($request_model->tipos)))
                        continue;
                }
            }

            $model = new DenunciaListaModel();

            $model->denunciaId = $denuncia->id;
            $model->descricao = $denuncia->descricao;
            $model->tipoDenuncia = $denuncia->tipo_denuncia->descricao;
            $model->publicacaoId = $denuncia->publicacao_id;
            // Datas importantes
            $model->dataHoraCriacao = $denuncia->created_at;
            $model->dataHoraUltimaRevisao = $denuncia->updated_at;
            // Resultado e situacao
            $model->situacaoId = $denuncia->situacao_denuncia_id;
            $model->situacao = $denuncia->situacao_denuncia->situacao;
            $model->resultado = $denuncia->resultado_denuncia->resultado ?? null;
            // Autor
            $model->estudanteAutor = $denuncia->estudante_autor->nome;
            $model->estudanteAutorId = $denuncia->estudante_id_autor;
            // Denunciado
            $model->estudanteDenunciado = $denuncia->estudante_denunciado->nome;
            $model->estudanteDenunciadoId = $denuncia->estudante_id_denunciado;

            $list[] = $model;
        }

        return $this->response($list);
    }

}