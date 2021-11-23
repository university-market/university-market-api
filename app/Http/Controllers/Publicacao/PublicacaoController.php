<?php

namespace App\Http\Controllers\Publicacao;

use Illuminate\Http\Request;

// Base
use App\Base\Aws\Buckets\UniversityMarketBuckets;
use App\Base\Exceptions\UniversityMarketException;
use App\Base\Controllers\UniversityMarketController;
use App\Base\Logs\Logger\UniversityMarketLogger;
use App\Base\Logs\Type\StdLogChange;
use App\Base\Logs\Type\StdLogType;
use App\Base\Resource\UniversityMarketResource;
// Helpers
use App\Helpers\Aws\S3\S3Helper;

// Common
use Exception;

// Entidades
use App\Models\Curso\Curso;
use App\Models\Publicacao\Publicacao;
use App\Models\Publicacao\Denuncia;
use App\Models\Publicacao\Publicacao_Tag;

// Models de publicacao utilizadas
use App\Http\Controllers\Publicacao\Models\PublicacaoCriacaoModel;
use App\Http\Controllers\Publicacao\Models\PublicacaoCriaMovimentacaoModel;
use App\Http\Controllers\Publicacao\Models\PublicacaoDetalheModel;
use App\Http\Controllers\Publicacao\Models\PublicacaoDenunciaModel;
use App\Http\Controllers\Publicacao\Models\PublicacaoMovimentacaoModel;
use App\Http\Controllers\Publicacao\Models\PublicacaoTipoDenunciaModel;
use App\Models\Estudante\Endereco;
use App\Models\Estudante\Estudante;
use App\Models\Publicacao\Movimentacao;
use App\Models\Publicacao\Tag;
use App\Models\Publicacao\TipoDenuncia;

class PublicacaoController extends UniversityMarketController
{

    public function obter($publicacaoId)
    {

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $publicacao = Publicacao::find($publicacaoId);

        $endereco = Endereco::where('deleted_at',null)
                            ->where('estudante_id',$publicacao->estudante_id)
                            ->first();

        if (is_null($publicacao) || $publicacao->deleted)
            throw new UniversityMarketException("Publicação não encontrada");

        if (!is_null($publicacao->data_hora_finalizacao))
            throw new UniversityMarketException("Publicação acabou de ser vendida");

        $model = new PublicacaoDetalheModel();

        $model->publicacaoId = $publicacao->id;
        $model->titulo = $publicacao->titulo;
        $model->descricao = $publicacao->descricao;
        $model->valor = $publicacao->valor;
        $model->especificacoesTecnicas = $publicacao->especificacao_tecnica;
        $model->pathImagem = $publicacao->caminho_imagem;
        $model->dataHoraCriacao = $publicacao->created_at;
        $model->estudanteId = $publicacao->estudante_id;
        $model->municipio = $endereco->municipio ?? null;
        $model->bairro = $endereco->bairro ?? null;
        $model->cep = $endereco->cep ?? null;



        // Query e map de tags da publicacao
        $tags = array_map(function ($item) {
            return $item->tag->conteudo;
        }, $this->obterTags($publicacaoId, true)->all());

        $model->tags = implode(',', $tags);

        return $this->response($model);
    }

    public function obterByUser($estudanteId)
    {

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $publicacoes = Publicacao::where('estudante_id', $estudanteId)
            ->where('deleted', false)
            ->get();

        $list = [];

        foreach ($publicacoes as $publicacao) {

            $model = new PublicacaoDetalheModel();

            $model->publicacaoId = $publicacao->id;
            $model->titulo = $publicacao->titulo;
            $model->descricao = $publicacao->descricao;
            $model->valor = $publicacao->valor;
            $model->especificacoesTecnicas = $publicacao->especificacao_tecnica;
            $model->pathImagem = $publicacao->caminho_imagem;
            $model->dataHoraCriacao = $publicacao->created_at;

            if ($publicacao->data_hora_finalizacao) {
                $model->vendida = true;
            } else {
                $model->vendida = false;
            }

            $list[] = $model;
        }

        return $this->response($list);
    }

    public function criar(Request $request)
    {

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $model = $this->cast($request, PublicacaoCriacaoModel::class);

        // Validar informacoes construidas na model
        $model->validar();

        $publicacao = new Publicacao();

        $publicacao->titulo = $model->titulo;
        $publicacao->descricao = $model->descricao;
        $publicacao->especificacao_tecnica = $model->especificacoesTecnicas;
        $publicacao->valor = $model->valor;
        $publicacao->caminho_imagem = null; // Alterado em requisição separa para upload de imagem
        $publicacao->data_hora_finalizacao = null; // Somente quando finalizada
        $publicacao->curso_id = $session->estudante->curso->id;
        $publicacao->estudante_id = $session->estudante_id;

        $publicacao->save();

        $tags = is_null($model->tags) ? null : explode(',', $model->tags);

        if (!is_null($tags)) {

            foreach ($tags as $tag_item) {

                $tag = new Tag();

                $tag->conteudo = $tag_item;
                $tag->save();

                $tag_publicacao = new Publicacao_Tag();

                $tag_publicacao->tag_id = $tag->id;
                $tag_publicacao->publicacao_id = $publicacao->id;

                $tag_publicacao->save();
            }
        }

        // Persistir log de criacao de publicacao
        UniversityMarketLogger::log(
            UniversityMarketResource::$publicacao,
            $publicacao->id,
            StdLogType::$criacao,
            "Publicação criada",
            $session->estudante_id,
            null
        );

        return $this->response($publicacao->id);
    }

    public function uploadImagemPublicacao($publicacaoId, Request $request)
    {

        if (is_null($publicacaoId))
            throw new UniversityMarketException("Publicação não encontrada");

        $publicacao = Publicacao::find($publicacaoId);

        if (is_null($publicacao) || $publicacao->deleted)
            throw new UniversityMarketException("Publicação não encontrada");

        $publicacao_image_key = 'publicacaoImage';

        if (!$request->hasFile($publicacao_image_key))
            throw new UniversityMarketException("Nenhuma imagem foi fornecida");

        $file = $request->file($publicacao_image_key);

        $url = $this->uploadImage($publicacao->id, $file);

        if (is_null($url))
            throw new UniversityMarketException("Ocorreu um erro ao realizar upload da imagem");

        $publicacao->caminho_imagem = $url;
        $publicacao->save();

        // Log de upload de imagem

        return $this->response();
    }

    public function listar()
    {

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $estudante = Estudante::find($session->estudante_id);

        if (is_null($estudante))
            throw new UniversityMarketException("Estudante não encontrado");

        $publicacoes = Publicacao::where('deleted', false)
            ->where('data_hora_finalizacao', null)->get();

        $list = [];

        foreach ($publicacoes as $publicacao) {

            $model = new PublicacaoDetalheModel();

            $model->publicacaoId = $publicacao->id;
            $model->titulo = $publicacao->titulo;
            $model->descricao = $publicacao->descricao;
            $model->valor = $publicacao->valor;
            $model->especificacoesTecnicas = $publicacao->especificacao_tecnica;
            $model->pathImagem = $publicacao->caminho_imagem;
            $model->dataHoraCriacao = $publicacao->created_at;

            $list[] = $model;
        }

        return $this->response($list);
    }


    public function listarByCurso()
    {

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $estudante = Estudante::find($session->estudante_id);

        if (is_null($estudante))
            throw new UniversityMarketException("Estudante não encontrado");

        $publicacoes = Publicacao::where('deleted', false)
            ->where('curso_id', $estudante->curso_id)
            ->where('data_hora_finalizacao', null)
            ->get();
        $list = [];

        foreach ($publicacoes as $publicacao) {

            $model = new PublicacaoDetalheModel();

            $model->publicacaoId = $publicacao->id;
            $model->titulo = $publicacao->titulo;
            $model->descricao = $publicacao->descricao;
            $model->valor = $publicacao->valor;
            $model->especificacoesTecnicas = $publicacao->especificacao_tecnica;
            $model->pathImagem = $publicacao->caminho_imagem;
            $model->dataHoraCriacao = $publicacao->created_at;

            $list[] = $model;
        }

        return $this->response($list);
    }
    public function alterar(Request $request, $publicacaoId)
    {

        $model = $this->cast($request, PublicacaoCriacaoModel::class);

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $publicacao = Publicacao::find($publicacaoId);

        if (is_null($publicacao) || $publicacao->deleted)
            throw new UniversityMarketException("Publicação não encontrada");

        // Validação valor recebido na model
        if ($model->valor !== null && !is_numeric($model->valor))
            throw new UniversityMarketException("O valor informado não é válido");

        // Edicao da publicacao

        $beforeState = [];
        $afterState = [];

        // Titulo
        if ($publicacao->titulo != $model->titulo) {

            $beforeState['titulo'] = $publicacao->titulo;

            $publicacao->titulo = (is_null($model->titulo) || empty(trim($model->titulo))) ?
                $publicacao->titulo : trim($model->titulo);

            $afterState['titulo'] = $publicacao->titulo;
        }

        // Descricao
        if ($publicacao->descricao != $model->descricao) {

            $beforeState['descricao'] = $publicacao->descricao;

            $publicacao->descricao = (is_null($model->descricao) || empty(trim($model->descricao))) ?
                $publicacao->descricao : trim($model->descricao);

            $afterState['descricao'] = $publicacao->descricao;
        }

        // Valor
        if ($publicacao->valor != $model->valor) {

            $beforeState['valor'] = $publicacao->valor;

            $publicacao->valor = (is_null($model->valor) || empty(trim($model->valor))) ?
                $publicacao->valor : (float)$model->valor;

            $afterState['valor'] = $publicacao->valor;
        }

        // Tags
        // if ($publicacao->tags != $model->tags) {

        //     $publicacao->tags = (\is_null($model->tags) || empty(trim($model->tags))) ? 
        //         $publicacao->tags : $model->tags;
        // }

        // Detalhes tecnicos
        if ($publicacao->especificacao_tecnica != $model->especificacoesTecnicas) {

            $beforeState['especificacao_tecnica'] = $publicacao->especificacao_tecnica;

            $publicacao->especificacao_tecnica = strlen(trim($model->especificacoesTecnicas)) == 0 ?
                null : trim($model->especificacoesTecnicas);

            $afterState['especificacao_tecnica'] = $publicacao->especificacao_tecnica;
        }

        // Imagem
        // if ($publicacao->pathImagem != $model->pathImagem) {

        //     $publicacao->pathImagem = (\is_null($model->pathImagem) || empty(trim($model->pathImagem))) ? 
        //         $publicacao->pathImagem : trim($model->pathImagem);
        // }

        $publicacao->save();

        $changes = new StdLogChange();

        // Persistir log de criacao de edição da publicacao
        UniversityMarketLogger::log(
            UniversityMarketResource::$publicacao,
            $publicacao->id,
            StdLogType::$edicao,
            "Publicação editada",
            $session->estudante_id,
            $changes->setBeforeState($beforeState)->setAfterState($afterState)->serializeChanges()
        );

        return $this->response();
    }

    public function obterTags($publicacaoId, $internal = false)
    {

        if (is_null($publicacaoId))
            throw new UniversityMarketException("Publicação não encontrada");

        $publicacao = Publicacao::find($publicacaoId);

        if (is_null($publicacao) || $publicacao->deleted)
            throw new UniversityMarketException("Publicação não encontrada");

        $tagFields = ['id', 'conteudo'];

        $tags = Publicacao_Tag::where('publicacao_id', $publicacaoId)
            ->with(['tag' => function ($tagQuery) use ($tagFields) {
                $tagQuery->select($tagFields)->get();
            }])
            ->select('tag_id')
            ->get();

        return !$internal ? $this->response($tags) : $tags;
    }

    public function excluir($publicacaoId)
    {

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $publicacao = Publicacao::find($publicacaoId);

        if (\is_null($publicacao) || $publicacao->deleted)
            throw new UniversityMarketException("Publicação não encontrada");

        $publicacao->deleted = true;

        $publicacao->save();

        // Persistir log de criacao de edição da publicacao
        UniversityMarketLogger::log(
            UniversityMarketResource::$publicacao,
            $publicacao->id,
            StdLogType::$exclusao,
            "Publicação excluida",
            $session->estudante_id,
            null
        );
        return $this->response();
    }

    public function marcarPublicacaoComoVendida(Request $request)
    {

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $model = $this->cast($request, PublicacaoCriaMovimentacaoModel::class);

        if (is_null($request->publicacaoId))
            throw new UniversityMarketException("Id da publicação não encontrado");

        $publicacao = Publicacao::find($model->publicacaoId);

        if (is_null($publicacao))
            throw new UniversityMarketException("Publicação não encontrada");

        if ($publicacao->estudante_id != $session->estudante_id)
            throw new UniversityMarketException("Você não pode editar esta publicação");

        $publicacao->data_hora_finalizacao = $this->now();

        $publicacao->save();

        return $this->response();
    }

    private function uploadImage($publicacaoId, $file)
    {
        $filename = $file->getClientOriginalName();
        $filename_arr = explode('.', str_replace(" ", "_", $filename));

        $extension = $filename_arr[count($filename_arr) - 1];

        try {

            $filename = 'image-' . $publicacaoId . '.' . $extension;

            /*
            // Upload do arquivo para bucket do S3
            $url = S3Helper::upload(
                UniversityMarketBuckets::$default,
                $file,
                $filename
            );
            */
            // /*

            // Persistir arquivo localmente no servidor
            $destination_path = '/storage/upload/publicacao/';
            $url = env('APP_URL') . $destination_path . $filename;

            if (!$file->move('.' . $destination_path, $filename))
                throw new UniversityMarketException("Não foi possível salvar o arquivo");
            // */

            return $url;
        } catch (Exception $e) {

            // Registrar log de erro da operação
        }

        return null;
    }

    private function publicacaoExcluida($publicacao_id)
    {

        $publicacao = publicacao::where('id', $publicacao_id)
            ->where('deleted', 1)
            ->first();

        if (is_null($publicacao))
            return false;

        return $publicacao;
    }

    public function denunciar(Request $request)
    {


        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $model = $this->cast($request, PublicacaoDenunciaModel::class);

        $existente = $this->publicacaoExcluida($model->publicacao_id);

        if ($existente) {
            throw new \Exception("Publicação excluida");
        }

        $denuncia = new Denuncia();

        $denuncia->descricao = $model->motivo;
        $denuncia->estudante_id_autor = $session->estudante_id;
        $denuncia->estudante_id_denunciado = $model->estudante_id_denunciado;
        $denuncia->publicacao_id = $model->publicacao_id;
        $denuncia->tipo_denuncia_id = $model->tipo_denuncia_id;

        $denuncia->save();

        // Persistir log de criacao de edição da publicacao
        UniversityMarketLogger::log(
            UniversityMarketResource::$publicacao,
            $denuncia->id,
            StdLogType::$criacao,
            "Publicação Denunciada",
            $session->estudante_id,
            null
        );
    }

    public function obterTiposDenuncias()
    {

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $denuncias = TipoDenuncia::get();

        $list = [];

        foreach ($denuncias as $denucia) {
            
            $model = new PublicacaoTipoDenunciaModel();

            $model->id = $denucia->id;
            $model->descricao = $denucia->descricao;

            $list[] = $model;
        }

        return $this->response($list);
    }

    public function pesquisarPublicacoes(Request $request){

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $estudante = Estudante::find($session->estudante_id);

        if (is_null($estudante))
            throw new UniversityMarketException("Estudante não encontrado");
        
        $publicacoes = Publicacao::where('deleted', false)
                                ->where('titulo', 'like', '%'.$request->pesquisa.'%')
                                ->orWhere('descricao', 'like', '%'.$request->pesquisa.'%')
                                ->where('data_hora_finalizacao', null)
                                ->get();
    

        $list = [];

        foreach ($publicacoes as $publicacao) {

            $model = new PublicacaoDetalheModel();

            $model->publicacaoId = $publicacao->id;
            $model->titulo = $publicacao->titulo;
            $model->descricao = $publicacao->descricao;
            $model->valor = $publicacao->valor;
            $model->especificacoesTecnicas = $publicacao->especificacao_tecnica;
            $model->pathImagem = $publicacao->caminho_imagem;
            $model->dataHoraCriacao = $publicacao->created_at;

            $list[] = $model;
        }

        return $this->response($list);
    }
}
