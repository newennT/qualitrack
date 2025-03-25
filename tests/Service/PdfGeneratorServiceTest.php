<?php

namespace App\Tests\Service;

use App\Service\PdfGeneratorService;
use Nucleos\DompdfBundle\Factory\DompdfFactoryInterface;
use Dompdf\Dompdf;
use PHPUnit\Framework\TestCase;

class PdfGeneratorServiceTest extends TestCase
{
    private PdfGeneratorService $pdfGeneratorService;
    private $dompdfFactoryMock;
    private $dompdfMock;

    protected function setUp(): void
    {
        $this->dompdfFactoryMock = $this->createMock(DompdfFactoryInterface::class);

        $this->dompdfMock = $this->createMock(Dompdf::class);

        $this->dompdfFactoryMock->method('create')
            ->willReturn($this->dompdfMock);

        $this->pdfGeneratorService = new PdfGeneratorService($this->dompdfFactoryMock);
    }

    public function testGetPdf(): void
    {
        $htmlContent = "<html><body><h1>Test PDF</h1></body></html>";

        $this->dompdfMock->expects($this->once())
            ->method('loadHtml')
            ->with($this->equalTo($htmlContent));
        
        $this->dompdfMock->expects($this->once())
            ->method('setPaper')
            ->with('A4');
        
        $this->dompdfMock->expects($this->once())
            ->method('render');
        
        $this->dompdfMock->expects($this->once())
            ->method('output')
            ->willReturn('fake-pdf-content');

        $pdf = $this->pdfGeneratorService->getPdf($htmlContent);

        $this->assertEquals('fake-pdf-content', $pdf);
    }
}