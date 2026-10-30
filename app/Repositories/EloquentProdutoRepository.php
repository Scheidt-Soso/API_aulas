<?php

namespace App\Repositories;

use App\Models\Produto;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentProdutoRepository implements ProdutoRepositoryInterface
{
    public function create(array $dados): Produto
    {
        return Produto::create($dados);
    }

    public function update(Produto $produto, array $dados): Produto
    {
        $produto->update($dados);

        return $produto->refresh();
    }

    public function delete(Produto $produto): void
    {
        $produto->delete();
    }

    public function search(array $filtros = [], int $porPagina = 10): LengthAwarePaginator
    {
        return Produto::query()
            ->when($filtros['search'] ?? null,
                fn ($query, $pedaco) => $query->where('nome', 'LIKE', "%{$pedaco}%"))
            ->when($filtros['preco_min'] ?? null,
                fn ($query, $valor) => $query->where('preco', '>=', $valor))
            ->when($filtros['preco_max'] ?? null,
                fn ($query, $valor) => $query->where('preco', '<=', $valor))
            ->paginate($porPagina);
    }
}
