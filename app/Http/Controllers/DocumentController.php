<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Jobs\NotifySigner;
use App\Services\DocumentService;
use App\Services\SignerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    private DocumentService $documentService;
    private SignerService $signerService;

    public function __construct(DocumentService $documentService, SignerService $signerService)
    {
        $this->documentService = $documentService;
        $this->signerService = $signerService;
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $documentPath = $this->documentService->upload($validated);
            $document = $this->documentService->store($documentPath, $validated, $request);
            $signers = $this->signerService->store($document, $validated);

            dispatch(new NotifySigner($signers['signers']));

            // If all operations succeed, commit the transaction
            DB::commit();

            return response()->json($signers);
        } catch (\Exception $e) {
            // If any operation fails, roll back the transaction
            DB::rollback();
            Log::error($e->getMessage());
            Log::error($e);

            return response()->json(['message' => 'An error occurred while processing your request'], 500);
        }
    }
}
