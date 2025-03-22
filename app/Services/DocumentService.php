<?php

namespace App\Services;

use App\Constants\SignerType;
use App\Repositories\DocumentRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class DocumentService
{
    private DocumentRepository $repository;
    private FpdfService $pdfService;

    public function __construct(DocumentRepository $repository, FpdfService $pdfService)
    {
        $this->repository = $repository;
        $this->pdfService = $pdfService;
    }

    public function upload($validated)
    {
        return $validated['document']->storeAs(
            config('documents.storage_paths.original'),
            generateDocName($validated)
        );
    }

    public function store(string $documentPath, $validated, Request $request): Model|Builder
    {
        $data = [
            'sender_name' => $validated['sender_name'],
            'sender_email' => $validated['sender_email'],
            'sender_ip' => $request->header('CF-Connecting-IP') ?? $request->getClientIp() ?? '0.0.0.0',
            'original_filename' => basename($documentPath),
            'original_path' => dirname($documentPath),
            'short_url' => Str::random(12),
            'total_signer' => count($validated['signers']),
        ];

        return $this->repository->store($data);
    }

    public function markCoordination($signer): array
    {
        $document = $this->getDocumentPath($signer);
        $signer->update(['viewed_time' => now()]);

        return [
            'signed' => $signer->signed || $signer->type === SignerType::SENDER ? 1 : 0,
            'document' => $document ?? $this->drawCoordinate($signer)
        ];
    }

    private function getDocumentPath($signer): ?string
    {
        if (!empty($signer->mark_coordinate_filename)) {
            return URL::to(Storage::url(config('documents.storage_paths.marked') . "{$signer->mark_coordinate_filename}"));
        }

        if ($signer->signed || $signer->type === SignerType::SENDER)
        {
            $docType = $signer->document->signed
                ? config('documents.storage_paths.signed')
                : config('documents.storage_paths.original');

            return URL::to(Storage::url("{$docType}{$signer->document->signed_filename}"));
        }

        return null;
    }

    private function drawCoordinate($signer): string
    {
        $document = $this->draw($signer);

        $markedPath = config('documents.storage_paths.marked');

        $signer->update([
            'mark_coordinate_path' => $markedPath,
            'mark_coordinate_filename' => $document
        ]);

        return URL::to(Storage::url("{$markedPath}{$document}"));
    }

    private function getDocumentStoragePath($signer): string
    {
        $doc = $signer->document;
        $folder = $doc->signed
            ? config('documents.storage_paths.signed')
            : config('documents.storage_paths.original');

        $filename = $doc->signed ? $doc->signed_filename : $doc->original_filename;

        return Storage::path("{$folder}{$filename}");
    }

    public function draw($signer): string
    {
        $documentPath = $this->getDocumentStoragePath($signer);
        $pdf = $this->pdfService->loadPdf();
        $pageCount = $pdf->setSourceFile($documentPath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $this->pdfService->addPage($pdf, $pageNo);

            if (!empty($signer->signatures)) {
                foreach ($signer->signatures as $signature) {
                    if ($pageNo === $signature->page_no) {
                        $this->pdfService->drawRectangle($pdf, [
                            'coordinate_x' => $signature->coordinate_x,
                            'coordinate_y' => $signature->coordinate_y,
                            'height' => $signature->height
                        ]);
                    }
                }
            }
        }

        return $this->pdfService->savePdf($pdf, $signer->email . '_' . uniqid() . '.pdf', config('documents.storage_paths.marked'));
    }

}
