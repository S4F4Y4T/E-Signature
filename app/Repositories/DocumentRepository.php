<?php
namespace App\Repositories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DocumentRepository
{
    private Document $model;

    public function __construct(Document $model)
    {
        $this->model = $model;
    }

    public function store($data): Model|Builder
    {
        return $this->model->query()->create($data);
    }
}
