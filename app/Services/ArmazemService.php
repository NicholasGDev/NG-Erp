<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Armazem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArmazemService
{
    public function __construct(protected Armazem $model) {}

    public function index(): LengthAwarePaginator
    {
        return $this->model->with('posicoes')->paginate();
    }

    public function show(int $id): Armazem
    {
        return $this->model->with('posicoes')->findOrFail($id);
    }

    public function store(array $data): Armazem
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Armazem
    {
        $armazem = $this->model->findOrFail($id);
        $armazem->update($data);
        return $armazem;
    }

    public function destroy(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }
}
