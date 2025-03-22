<?php
namespace App\Repositories;

use App\Models\Signature;
use App\Models\Signer;
use App\Models\Document;

class SignatureRepository
{
    private Signature $model;

    public function __construct(Signature $model)
    {
        $this->model = $model;
    }

    public function store(Signer $signer, $data): void
    {
        $signer->signatures()->createMany($data);
    }

}
