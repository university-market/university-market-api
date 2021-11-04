<?php

namespace App\Http\Controllers\Publicacao;

use App\Helpers\Aws\S3\S3Helper;
use Illuminate\Http\Request;

// Base
use App\Base\Controllers\UniversityMarketController;
use App\Base\Exceptions\UniversityMarketException;

// Entidades
use App\Models\Curso\Curso;
use App\Models\Publicacao\Publicacao;
use App\Models\Publicacao\Publicacao_Tag;

// Models de publicacao utilizadas
use App\Http\Controllers\Publicacao\Models\PublicacaoCriacaoModel;
use App\Http\Controllers\Publicacao\Models\PublicacaoDetalheModel;
use App\Base\Aws\Buckets\UniversityMarketBuckets;

class PublicacaoController extends UniversityMarketController {

    public function obter($publicacaoId) {

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $publicacao = Publicacao::find($publicacaoId);

        if (\is_null($publicacao) || $publicacao->excluida)
            throw new UniversityMarketException("Publicação não encontrada");

        $model = new PublicacaoDetalheModel();

        $model->publicacaoId = $publicacao->id;
        $model->titulo = $publicacao->titulo;
        $model->descricao = $publicacao->descricao;
        $model->valor = $publicacao->valor;
        $model->especificacoesTecnicas = $publicacao->especificacao_tecnica;
        $model->pathImagem = $publicacao->caminho_imagem;
        $model->dataHoraCriacao = $publicacao->created_at;

        return $this->response($model);
    }

    public function obterByUser($estudanteId) {

        //$session = $this->getSession();

        //if (!$session)
            //return $this->unauthorized();

        $publicacoes = Publicacao::where('estudante_id', $estudanteId)
            ->where('deleted', false)
            ->get()->toArray();

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

    public function criar(Request $request) {

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
        // $publicacao->caminho_imagem = $this->uploadImage($request);
        $publicacao->data_hora_finalizacao = null; // Somente quando finalizada
        $publicacao->curso_id = $session->estudante->curso->id;
        $publicacao->estudante_id = $session->estudante_id;

        $image_url = S3Helper::upload(
            UniversityMarketBuckets::$default,
            $request->file('image'),
            'imagem-teste-upload-s3',
        ) ?? $this->uploadImage($request);

        $publicacao->caminho_imagem = $image_url;

        $publicacao->save();

        return $this->response();
    }

    public function listar() {

        $publicacoes = Publicacao::where('deleted', false)->get();

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

    public function listarByCurso($cursoId) {
        
        if (is_null($cursoId))
            throw new UniversityMarketException("Curso não encontrado");

        $curso = Curso::find($cursoId);

        if (is_null($curso))
            throw new UniversityMarketException("Curso não encontrado");

        $cursoFields = ['id', 'nome'];
        $publicacaoFields = ['id', 'titulo', 'caminho_imagem'];
        $estudanteFields = ['id', 'nome', 'email'];

        $list = Curso::where('id', $cursoId)
            ->with(['publicacoes' => function($publicacaoQuery) use ($publicacaoFields, $estudanteFields) {
                $publicacaoQuery->with(['estudante' => function($estudanteQuery) use ($estudanteFields) {
                    $estudanteQuery->select($estudanteFields);
                }])
                ->select($publicacaoFields);
            }])
            ->select($cursoFields)
            ->get();

        return $this->response($list);
    }

    public function alterar(Request $request, $publicacaoId) {

        $model = $this->cast($request, PublicacaoCriacaoModel::class);

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $publicacao = Publicacao::find($publicacaoId);

        if (is_null($publicacao) || $publicacao->excluida)
            throw new UniversityMarketException("Publicação não encontrada");

        // Validação valor recebido na model
        if ($model->valor !== null && !is_numeric($model->valor))
            throw new UniversityMarketException("O valor informado não é válido");

        // Titulo
        if ($publicacao->titulo != $model->titulo) {

            $publicacao->titulo = (is_null($model->titulo) || empty(trim($model->titulo))) ? 
                $publicacao->titulo : trim($model->titulo);
        }
        
        // Descricao
        if ($publicacao->descricao != $model->descricao) {

            $publicacao->descricao = (is_null($model->descricao) || empty(trim($model->descricao))) ? 
                $publicacao->descricao : trim($model->descricao);
        }

        // Valor
        if ($publicacao->valor != $model->valor) {

            $publicacao->valor = (is_null($model->valor) || empty(trim($model->valor))) ? 
                $publicacao->valor : (double)$model->valor;
        }

        // Tags
        // if ($publicacao->tags != $model->tags) {

        //     $publicacao->tags = (\is_null($model->tags) || empty(trim($model->tags))) ? 
        //         $publicacao->tags : $model->tags;
        // }

        // Detalhes tecnicos
        if ($publicacao->especificacoesTecnicas != $model->especificacoesTecnicas) {

            $publicacao->especificacoesTecnicas = strlen(trim($model->especificacoesTecnicas)) == 0 ?
                null : trim($model->especificacoesTecnicas);
        }
        
        // Imagem
        // if ($publicacao->pathImagem != $model->pathImagem) {
        
        //     $publicacao->pathImagem = (\is_null($model->pathImagem) || empty(trim($model->pathImagem))) ? 
        //         $publicacao->pathImagem : trim($model->pathImagem);
        // }

        $publicacao->save();

        return $this->response();
    }

    public function obterTags($publicacaoId) {

        if (is_null($publicacaoId))
            throw new UniversityMarketException("Publicação não encontrada");

        $publicacao = Publicacao::find($publicacaoId);

        if (is_null($publicacao) || $publicacao->excluida)
            throw new UniversityMarketException("Publicação não encontrada");

        $tagFields = ['id', 'conteudo'];

        $tags = Publicacao_Tag::where('publicacao_id', $publicacaoId)
            ->with(['tag' => function($tagQuery) use($tagFields) {
                $tagQuery->select($tagFields)->get();
            }])
            ->select('tag_id')
            ->get();

        return $this->response($tags);
    }

    public function excluir($publicacaoId) {

        $publicacao = Publicacao::find($publicacaoId);

        if (\is_null($publicacao) || $publicacao->excluida)
            throw new UniversityMarketException("Publicação não encontrada");

        $publicacao->deleted = true;

        $publicacao->save();
        
        return $this->response();
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {

            $original_filename = $request->file('image')->getClientOriginalName();
            $original_filename_arr = explode('.', str_replace(" ", "_", $original_filename));
            $file_ext = $original_filename_arr[count($original_filename_arr)-1];
            
            $filename_parts = [];
            foreach ($original_filename_arr as $key => $value)
                if ($key < count($original_filename_arr)-1)
                    $filename_parts[] = $value;

            $filename = implode('.', $filename_parts);
            
            $destination_path = './storage/upload/publicacao/';
            $final_filename = $filename . '-um-pub-' . time() . '.' . $file_ext;

            if ($request->file('image')->move($destination_path, $final_filename))
                return '/storage/upload/publicacao/' . $final_filename;
        }
        throw new UniversityMarketException("Não foi possível realizar o upload da imagem");
    }

}