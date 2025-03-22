<?php

namespace App\Http\Controllers;

use App\Constants\SignerType;
use App\Jobs\NotifySender;
use App\Repositories\SignatureRepository;
use App\Repositories\SignerRepository;
use App\Services\DocumentService;
use App\Services\SignatureService;
use App\Services\SignerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SignatureController extends Controller
{
    protected SignerRepository $signerRepository;
    protected SignerService $signerService;
    protected SignatureRepository $signatureRepository;
    protected DocumentService $documentService;
    protected SignatureService $service;

    public function __construct(
        SignerService $signerService,
        SignerRepository $signerRepository,
        SignatureRepository $signatureRepository,
        DocumentService $documentService,
        SignatureService $service,
    ){
        $this->signerService = $signerService;
        $this->signerRepository = $signerRepository;
        $this->signatureRepository = $signatureRepository;
        $this->documentService = $documentService;
        $this->service = $service;
    }

    public function signature(Request $request, $shortUrl): JsonResponse
    {
        try {
            $signer = $this->signerService->getSigner($shortUrl, $request);
            $document = $this->service->store($request, $signer);
            dispatch(new NotifySender($request->name, $request->email, $signer->document));
            $signer->load(['document.signers' => fn($query) => $query->where('type', '!=', SignerType::SENDER)]);

            return response()->json([
                'message' => 'Signature stored successfully',
                'signed' => 1,
                'signedDoc' => $document,
                'data' => $signer->document
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
