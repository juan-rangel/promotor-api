<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsuarioResource;
use App\Http\Requests\AuthUsuarioPost;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Usuario;
use Exception;

class UsuarioController extends Controller
{

    private $modelParametrizada;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->path() == 'api/usuarios' && $request->method() === 'GET') {
                $this->modelParametrizada = Usuario::query()
                    ->when($request->filled('email'), function ($query) use ($request) {
                        $query->where('email', '=', $request->email);
                    });
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UsuarioResource::collection($this->modelParametrizada->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
        return new UsuarioResource($usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        //
    }

    /**
     * Validate the user to login.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function auth(AuthUsuarioPost $request)
    {
        try {
            $usuario = Usuario::where('email', $request->email)->firstOrFail();
            $return['model'] = $usuario;
        } catch (\Throwable $th) {
            throw new ModelNotFoundException('não conseguimos localizar seu usuário');
        }

        try {
            $decryptedPassword = Crypt::decryptString($usuario->senha);
        } catch (\Throwable $th) {
            throw new DecryptException('seu cadastro não está atualizado');
        }

        if ($decryptedPassword !== $request->senha) {
            throw new Exception('senhas não conferem');
        }

        return new UsuarioResource($usuario);
    }
}
