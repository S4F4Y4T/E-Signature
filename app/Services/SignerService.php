<?php

namespace App\Services;

use App\Constants\SignerType;
use App\Jobs\NotifySigner;
use App\Repositories\SignatureRepository;
use App\Repositories\SignerRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class SignerService
{
    protected SignerRepository $repository;
    protected SignatureRepository $signatureRepository;

    public function __construct(SignerRepository $repository, SignatureRepository $signatureRepository)
    {
        $this->repository = $repository;
        $this->signatureRepository = $signatureRepository;
    }

    public function getSigner(string $shortUrl, Request $request): Model|Builder
    {
        $validator = $this->validateRequest($shortUrl);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $signer = $this->repository->verify(
            $shortUrl,
            $request->name,
            $request->email,
            $request->otp
        );

        if (!$signer) {
            throw new \Exception('Invalid signer.', Response::HTTP_NOT_FOUND);
        }

        return $signer;
    }


    public function validateRequest($shortUrl): \Illuminate\Validation\Validator
    {
        return Validator::make(['short_url' => $shortUrl], [
            'short_url' => [
                'required',
                'string',
                Rule::exists('signers')->where(function ($query) use ($shortUrl) {
                    $query->where('short_url', $shortUrl);
                }),
            ],
        ]);
    }

    public function store($document, $validated): array
    {
        $invitationUrl = [];

        //merge sender with signers
        $validated['signers'][] = [
            'name' => $validated['sender_name'],
            'email' => $validated['sender_email'],
            'type' => SignerType::SENDER,
            'signatures' => []
        ];

        foreach ($validated['signers'] as $signerData)
        {
            $shortURL = Str::random(12);
            $signerData['short_url'] = $shortURL;

            $signer = $this->repository->store($document, $signerData);

            if(!empty($signerData['signatures']))
            {
                $this->signatureRepository->store($signer, $signerData['signatures']);
            }

            $signerType = isset($signerData['type']) && $signerData['type'] === SignerType::SENDER ? 'sender' : 'signers';

            $invitationUrl[$signerType][$signer['email']] = config('app.url').$shortURL;
        }

        return $invitationUrl;
    }

}
