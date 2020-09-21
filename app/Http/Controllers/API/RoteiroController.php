<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoteiroResource;
use App\Roteiro;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoteiroPost;

class RoteiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RoteiroResource::collection(Roteiro::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoteiroPost $request)
    {
        $inputs = json_decode($request->getContent(), true);

        $return = [
            'success' => true
        ];

        try {
            Roteiro::create($inputs);
        } catch (\Throwable $th) {
            $return = [
                'success' => false
            ];
        }

        return response()->json($return);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Roteiro  $roteiro
     * @return \Illuminate\Http\Response
     */
    public function show(Roteiro $roteiro)
    {
        return new RoteiroResource($roteiro);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Roteiro  $roteiro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Roteiro $roteiro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Roteiro  $roteiro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Roteiro $roteiro)
    {
        //
    }
}
