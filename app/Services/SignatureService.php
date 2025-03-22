<?php

namespace App\Services;

use App\Constants\SignerType;
use App\Repositories\SignatureRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;
use Stevebauman\Location\Facades\Location;

class SignatureService
{
    protected SignatureRepository $repository;
    private FpdfService $pdfService;

    public function __construct(SignatureRepository $repository, FpdfService $pdfService)
    {
        $this->repository = $repository;
        $this->pdfService = $pdfService;
    }

    /**
     * @throws Exception
     */
    public function store($request, $signer): string
    {
        $signature = $this->storeSignature($request);
        $this->updateSignerDetails($request, $signer, $signature);

        $signedDoc = $this->setSignature($signer, $signature);
        $this->updateDocumentStatus($signer);

        return $signedDoc;
    }

    private function storeSignature($request)
    {
        $signatureType = $request->input('signatureType');

        return $signatureType === 'history'
            ? $request->input('signatureHistory')
            : $this->storeBase64Signature($request->input("signature" . ucfirst($signatureType)));
    }

    private function updateSignerDetails($request, $signer, $signaturePath): void
    {
        foreach ($signer->signatures ?? [] as $signature) {
            $signature->signature = $signaturePath;
            $signature->save();
        }

        $ipLocation = Location::get($request->getClientIp())?->countryName ?? '';
        $ipAddress = $request->header('CF-Connecting-IP') ?? $request->getClientIp() ?? '0.0.0.0';

        $signer->update([
            'signed_time' => now(),
            'ip_address' => $ipAddress,
            'location' => $ipLocation,
            'signed' => 1,
            'signature' => $signaturePath,
        ]);
    }

    private function updateDocumentStatus($signer): void
    {
        $signer->document->increment('total_signed');

        $signer->document->update(['signed' => 1, 'last_signed_time' => Carbon::now()]);

        if ($signer->document->total_signed >= $signer->document->total_signer) {
            $signer->document->update([
                'status' => 'Completed',
                'completed_signed' => 1,
            ]);
        }
    }


    private function storeBase64Signature($signature): string
    {
        $image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signature)))
            ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode('png', 80);

        $signaturePath = config('documents.storage_paths.signature') . uniqid() . '.png';
        Storage::put($signaturePath, $image);

        return $signaturePath;
    }

    private function setSignature($signer, $signaturePath): string
    {

        try {
            $document = $signer->document;
            $isSigned = $document->signed === 1;

            $signedSource = storage_path(
                "app/" . ($isSigned ? $document->signed_path : $document->original_path) . '/' .
                ($isSigned ? $document->signed_filename : $document->original_filename)
            );

            $pdf = $this->pdfService->loadPdf();
            $pageCount = $pdf->setSourceFile($signedSource) - ($isSigned ? 1 : 0);


            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

                $this->pdfService->addPage($pdf, $pageNo);

                if (!empty($signer->signatures)) {
                    foreach ($signer->signatures as $signature) {
                        if ($pageNo == $signature->page_no) {
                            $this->pdfService->addSignature($pdf, $signaturePath, $signature, $signer->ip_address);
                        }
                    }
                }
            }

            $cert = $this->generateSignatureCertificate($signer);
            $pdf->setSourceFile($cert);
            $this->pdfService->addPage($pdf, 1);

            $this->pdfService->savePdf($pdf, $document->original_filename, config('documents.storage_paths.signed'));

            $this->deleteFileIfExists(config('documents.storage_paths.certificate') . basename($cert));
            $this->deleteFileIfExists(config('documents.storage_paths.marked') . $signer->mark_coordinate_filename);
            $this->clearMarkCoordinates($signer);

            $document->update([
                'signed_filename' => $document->original_filename,
                'signed_path' => config('documents.storage_paths.signed')
            ]);

            return URL::to(Storage::url(config('documents.storage_paths.signed') . $document->original_filename . '?v=' . time()));

        } catch (Exception $e) {
            throw new Exception('PDF generation failed: ' . $e->getMessage());
        }
    }

    private function deleteFileIfExists($path): void
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }

    private function clearMarkCoordinates($signer): void
    {
        $signer->update([
            'mark_coordinate_filename' => '',
            'mark_coordinate_path' => ''
        ]);
    }

    private function generateSignatureCertificate($signer): string
    {
        $signer->load(['document.signers' => fn($query) => $query->where('type', '!=', SignerType::SENDER)]);

        $pdf = Pdf::setOption([
            'dpi' => 140,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultFont' => 'Open Sans'
        ])->loadView('certificate', ['data' => $signer->document]);

        $outputPath = Storage::path(
            config('documents.storage_paths.certificate') . 'cert_' . uniqid() . '.pdf'
        );

        $pdf->save($outputPath);
        return $outputPath;
    }
}
