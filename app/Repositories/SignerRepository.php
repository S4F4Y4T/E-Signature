<?php
namespace App\Repositories;

use App\Constants\SignerType;
use App\Models\Signer;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SignerRepository
{
    private Signer $model;

    public function __construct(Signer $model)
    {
        $this->model = $model;
    }

    public function store($document, $data){
        return $document->signers()->create($data);
    }

    public function update($signer, $data)
    {
        return $signer->update($data);
    }

    public function history($name, $email, $type): Collection|array
    {
        return $this->model->query()
            ->select('signature')
            ->selectRaw('MAX(created_at) as latest_created_at')
            ->where('name', $name)
            ->where('email', $email)
            ->where('type', $type)
            ->whereNotNull('signature')
            ->groupBy('signature')
            ->orderByDesc('latest_created_at')
            ->take(6)
            ->get();
    }

    public function verify($shortUrl, $name, $email, $otp): Model|Builder|null
    {
        return Signer::with([
            'document', 'document.signers' => function($query) {
                $query->where('type', '!=', SignerType::SENDER);
            }, 'signatures'
        ])->where('short_url', $shortUrl)
            ->where('name', $name)
            ->where('email', $email)
            ->where(function ($query) use ($otp) {
                $query->where('otp', $otp)
                    ->orWhere('type', SignerType::SENDER);
            })
            ->first();
    }

}
