<?php

namespace App\Http\Controllers\Publicacao;

use App\Http\Controllers\Base\UniversityMarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models de publicacao utilizadas
use App\Models\Publicacao;
use App\Http\Controllers\Publicacao\Models\PublicacaoCriacaoModel;
use App\Http\Controllers\Publicacao\Models\PublicacaoDetalheModel;

class PublicacaoController extends UniversityMarketController {

    private $dataHoraFormat = "Y-m-d H:i:s";

    public function obter($publicacaoId) {

        $condition = [
            'publicacaoId' => $publicacaoId,
            'dataHoraExclusao' => null
        ];

        $publicacao = Publicacao::where($condition)->first();

        if (\is_null($publicacao))
            throw new \Exception("Publicação não encontrada");

        $model = $this->cast($publicacao, PublicacaoDetalheModel::class);

        return response()->json($model);
    }

    public function criar(Request $request) {

        $model = $this->cast($request, PublicacaoCriacaoModel::class);

        // Validar informacoes construidas na model
        $model->validar();

        $publicacao = new Publicacao();

        $publicacao->titulo = $model->titulo;
        $publicacao->descricao = $model->descricao;
        $publicacao->valor = $model->valor;
        $publicacao->tags = $model->tags;
        $publicacao->detalhesTecnicos = $model->detalhesTecnicos;
        $publicacao->dataHoraCriacao = \date($this->dataHoraFormat);
        $publicacao->pathImagem = $this->uploadImage($request);
        $publicacao->cursoId = 3;
        $publicacao->userId = 3;


        $publicacao->save();

        $publicacaoId = $publicacao->publicacaoId;

        return response()->json($publicacaoId);
    }

    public function listar() {

        $publicacoes = Publicacao::where('dataHoraExclusao', null)->get()->toArray();

        $model = $this->cast($publicacoes, PublicacaoDetalheModel::class);

        return response()->json($model);
    }

    public function listarByCursoId($id = null) {
        $results = null;

        if (!$id) {
            $results = DB::select('select * from publicacao');
            return $results;
        } else {
            $results = DB::select('select publicacaoId,
                                          titulo,
                                          descricao,
                                          valor,
                                          pathimagem,
                                          name
                                    from  publicacao 
                                     join users 
                                       on userId = id 
                                    where cursoId = :id',['id'=> $id]);
            return $results;
        }
    }

    public function alterar(Request $request, $publicacaoId) {

        $model = $this->cast($request, PublicacaoCriacaoModel::class);

        $condition = [
            'publicacaoId' => $publicacaoId,
            'dataHoraExclusao' => null
        ];
        $publicacao = Publicacao::where($condition)->first();

        if (\is_null($publicacao))
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
        if ($publicacao->tags != $model->tags) {

            $publicacao->tags = (\is_null($model->tags) || empty(trim($model->tags))) ? 
                $publicacao->tags : $model->tags;
        }

        // Detalhes tecnicos
        if ($publicacao->detalhesTecnicos != $model->detalhesTecnicos) {

            $publicacao->detalhesTecnicos = strlen(trim($model->detalhesTecnicos)) == 0 ?
                null : trim($model->detalhesTecnicos);
        }
        
        // Imagem
        if ($publicacao->pathImagem != $model->pathImagem) {
        
            $publicacao->pathImagem = (\is_null($model->pathImagem) || empty(trim($model->pathImagem))) ? 
                $publicacao->pathImagem : trim($model->pathImagem);
        }

        $publicacao->save();

        return response(null, 200);
    }

    public function excluir($publicacaoId) {

        $condition = [
            'publicacaoId' => $publicacaoId,
            'dataHoraExclusao' => null
        ];

        $publicacao = Publicacao::where($condition)->first();

        if (\is_null($publicacao))
            throw new \Exception("Publicação não encontrada");

        $publicacao->dataHoraExclusao = \date($this->dataHoraFormat);

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