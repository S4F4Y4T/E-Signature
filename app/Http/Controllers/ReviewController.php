<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreSignature;
use App\Repositories\SignatureRepository;
use App\Repositories\SignerRepository;
use App\Services\DocumentService;
use App\Services\SignerService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    protected SignerRepository $signerRepository;
    protected SignerService $signerService;
    protected SignatureRepository $signatureRepository;
    protected DocumentService $documentService;

    public function __construct(
        SignerService $signerService,
        SignerRepository $signerRepository,
        SignatureRepository $signatureRepository,
        DocumentService $documentService,
    ){
        $this->signerService = $signerService;
        $this->signerRepository = $signerRepository;
        $this->signatureRepository = $signatureRepository;
        $this->documentService = $documentService;
    }

    public function document(StoreSignature $request, $shortUrl): JsonResponse
    {
        try{
            $signer = $this->signerService->getSigner($shortUrl, $request);
            $verifyCoordination = $this->documentService->markCoordination($signer);

            return response()->json(
                [
                    'message' => 'success',
                    'signed' => $verifyCoordination['signed'],
                    'document' => $verifyCoordination['document'],
                    'data' => $signer->document],
                200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

}
