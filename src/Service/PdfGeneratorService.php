<?php 

namespace App\Service;

use Nucleos\DompdfBundle\Factory\DompdfFactoryInterface;

class PdfGeneratorService
{
    public function __construct(
        private readonly DompdfFactoryInterface $factory,
    ) {
    } 

    public function getPdf(string $html): string 
    {
        return $this->output($html);
    }

    public function output(string $html): string 
    {
        $dompdf = $this->factory->create();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();

        return $dompdf->output();
    }
}