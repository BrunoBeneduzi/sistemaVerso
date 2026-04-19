<?php

namespace App\Http\Controllers;

use App\Services\SincronizacaoService;
use Illuminate\Http\Request;

class SincronizacaoController extends Controller
{
    public function __construct(
        private readonly SincronizacaoService $service
    ) {}

    public function sincronizarProdutos()
    {
        $resultado = $this->service->sincronizarProdutos();
        return response()->json($resultado, 200);
    }

    public function sincronizarPrecos()
    {
        $resultado = $this->service->sincronizarPrecos();
        return response()->json($resultado, 200);
    }

    public function listarProdutosPrecos(Request $request)
    {
        $porPagina = $request->query('per_page', 10);
        $resultado = $this->service->listarProdutosComPrecos($porPagina);
        return response()->json($resultado, 200);
    }
}
