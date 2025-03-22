<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use setasign\Fpdi\Fpdi;

class FpdfService
{
    public function loadPdf(): FPDI
    {
        return new FPDI('P', 'mm');
    }

    public function addPage(FPDI $pdf, int $pageNo): void
    {
        $templateId = $pdf->importPage($pageNo);
        $size = $pdf->getTemplateSize($templateId);
        $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
        $pdf->useTemplate($templateId);
    }

    public function drawRectangle(FPDI $pdf, array $signature): void
    {
        $height = $signature['height'] ?? 15;
        $width = ($height / 100) * 150;

        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Rect($signature['coordinate_x'], $signature['coordinate_y'], $width, $height);
    }

    public function addSignature(FPDI $pdf, string $signaturePath, $signature, $ip): void
    {
        $imageSize = $this->calculateImageSize($signaturePath, $signature->height, $signature->width);
        $pdf->Image(
            storage_path("app/" . $signaturePath),
            $signature->coordinate_x + 5,
            $signature->coordinate_y,
            $imageSize['width'],
            $imageSize['height']
        );

//        $this->drawFrame($pdf, $signature->coordinate_x, $signature->coordinate_y, $imageSize['height'], $imageSize['width'], $ip);

    }

    private function drawFrame($pdf, $x, $y, $height, $width, $bottomText)
    {

        $radius = 0.5; // Radius for rounded corners

        // Set border color and width
        $pdf->SetDrawColor(0, 0, 255); // Set border color to blue
        $borderWidth = 0.3;
        $pdf->SetLineWidth($borderWidth); // Set border width

        $widthSize = $width / 5;

        $pdf->Line($x + $radius, $y, $x + $widthSize - $radius, $y);
        $pdf->Line($x + $widthSize - $radius, $y + $height, $x + $radius, $y + $height); // Bottom side
        $pdf->Line($x + $radius, $y + $height, $x, $y + $height - $radius); // Bottom left curve

        $pdf->Line($x, $y + $height - $radius, $x, $y + $radius); // Left side
        $pdf->Line($x, $y + $radius, $x + $radius, $y); // Top left curve

        // Set font for the text
        $pdf->SetFont('Arial', '', 8);

        // Calculate position for the text
        $bottomTextWidth = $pdf->GetStringWidth($bottomText);
//        $topTextWidth = $pdf->GetStringWidth($topText);
        $textX = $x + $widthSize;
//        $topTextX = $x + ($width - $topTextWidth) / 2;
        $textY = $y + $height;

        // Add the text
        $pdf->Text($textX + 1, $textY + 1.2, $bottomText);
//        $pdf->Text($textX + 1, $y + 1.2, $topText);
    }

    public function savePdf(FPDI $pdf, string $filename, string $path): string
    {
        $fullPath = storage_path("app/{$path}{$filename}");
        $directory = dirname($fullPath);

        // Check if the directory exists, if not, create it
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true); // The 'true' flag allows creation of nested directories
        }

        $pdf->Output($fullPath, 'F');
        return $filename;
    }


    private function calculateImageSize(string $path, ?int $height, ?int $width): array
    {
        $image = Image::make(storage_path("app/" . $path));
        $ratio = $image->width() / $image->height();

        $height = $height ?? 15;
        $width = $width ?? ($height * $ratio);

        return compact('height', 'width');
    }

}
