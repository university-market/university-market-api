<?php

namespace App\Http\Controllers\Publicacao;

use App\Exceptions\Base\UMException;
use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Publicacao\Publicacao;
use App\Models\Curso\Curso;
use App\Http\Controllers\Publicacao\Models\PublicacaoCriacaoModel;
use App\Http\Controllers\Publicacao\Models\PublicacaoDetalheModel;

class PublicacaoController extends UniversityMarketController {

    private $dataHoraFormat = "Y-m-d H:i:s";

    public function obter($publicacaoId) {

        $publicacao = Publicacao::find($publicacaoId);

        if (\is_null($publicacao) || $publicacao->excluida)
            throw new \Exception("Publicação não encontrada");

        $model = $this->cast($publicacao, PublicacaoDetalheModel::class);

        return response()->json($model);
    }

    public function criar(Request $request) {

        $model = $this->cast($request, PublicacaoCriacaoModel::class);

        // Validar informacoes construidas na model
        $model->validar();

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $publicacao = new Publicacao();

        $publicacao->titulo = $model->titulo;
        $publicacao->descricao = $model->descricao;
        $publicacao->especificacoesTecnicas = $model->especificacoesTecnicas;
        $publicacao->valor = $model->valor;
        $publicacao->pathImagem = $this->uploadImage($request);
        $publicacao->dataHoraCriacao = \date($this->dataHoraFormat);
        $publicacao->dataHoraFinalizacao = null; // Somente quando finalizada
        $publicacao->cursoId = 1;
        $publicacao->estudanteId = $session->estudanteId;

        $publicacao->save();

        return response(null, 200);
    }

    public function listar() {

        $publicacoes = Publicacao::where('excluida', false)->get()->toArray();

        $model = $this->cast($publicacoes, PublicacaoDetalheModel::class);

        return response()->json($model);
    }

    public function listarByCurso($cursoId) {
        
        if (is_null($cursoId))
            throw new UMException("Curso não encontrado");

        $curso = Curso::find($cursoId);

        if (is_null($curso))
            throw new UMException("Curso não encontrado");

        $cursoFields = ['cursoId', 'nome'];
        $publicacaoFields = ['publicacaoId', 'titulo', 'pathImagem', 'estudanteId'];
        $estudanteFields = ['estudanteId', 'nome', 'email'];

        $list = Curso::where('cursoId', $cursoId)
            ->with(['publicacao' => function($publicacaoQuery) use ($publicacaoFields, $estudanteFields) {
                $publicacaoQuery->with(['estudante' => function($estudanteQuery) use ($estudanteFields) {
                    $estudanteQuery->select($estudanteFields);
                }])
                ->select($publicacaoFields);
            }])
            ->select($cursoFields)
            ->get();

        return response()->json($list);
    }

    public function alterar(Request $request, $publicacaoId) {

        $model = $this->cast($request, PublicacaoCriacaoModel::class);

        $session = $this->getSession();

        if (!$session)
            return $this->unauthorized();

        $publicacao = Publicacao::find($publicacaoId);

        if (\is_null($publicacao) || $publicacao->excluida)
            throw new \Exception("Publicação não encontrada");

        // Validação valor recebido na model
        if ($model->valor !== null && !\is_numeric($model->valor))
            throw new \Exception("O valor informado não é válido");

        // Titulo
        if ($publicacao->titulo != $model->titulo) {

            $publicacao->titulo = (\is_null($model->titulo) || empty(trim($model->titulo))) ? 
                $publicacao->titulo : trim($model->titulo);
        }
        
        // Descricao
        if ($publicacao->descricao != $model->descricao) {

            $publicacao->descricao = (\is_null($model->descricao) || empty(trim($model->descricao))) ? 
                $publicacao->descricao : trim($model->descricao);
        }

        // Valor
        if ($publicacao->valor != $model->valor) {

            $publicacao->valor = (\is_null($model->valor) || empty(trim($model->valor))) ? 
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

        return response(null, 200);
    }

    public function excluir($publicacaoId) {

        $publicacao = Publicacao::find($publicacaoId);

        if (\is_null($publicacao) || $publicacao->excluida)
            throw new \Exception("Publicação não encontrada");

        $publicacao->excluida = true;

        $publicacao->save();
        
        return response(null, 200);
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
        throw new \Exception("Não foi possível realizar o upload da imagem");
    }

}