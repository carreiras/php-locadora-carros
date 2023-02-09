<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    private $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = $this->marca->all();

        return response()->json($marcas, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        /*
        Forma de acessar os valores passados:

        dd($request->nome);
        dd($request->get('nome'));
        dd($request->input('nome'));

        dd($request->imagem);
        dd($request->file('imagem'));
        */
        $image = $request->file('imagem');
        $image->store('imagens', 'public');
        dd('Upload de arquivos');

        // $marca = $this->marca->create($request->all());

        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'O recurso pesquisado não existe.'], 404);
        }

        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe.'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];

            // percorrendo todas as regras definidas no model
            foreach ($marca->rules() as $input => $regra) {
                // coletar apenas as regras aplicáveis aos parametros parciais da requisição PATCH
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }

        $marca->update($request->all());

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe.'], 404);
        }

        $marca->delete();

        return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
    }
}
