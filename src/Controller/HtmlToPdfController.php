<?php

namespace App\Controller;

use App\Constants\Endpoints;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\PdfGenerationService;

class HtmlToPdfController extends AbstractController
{
    private Filesystem $fs;
    private PdfGenerationService $service;
    public function __construct(PdfGenerationService $service)
    {
        $this->fs = new Filesystem();
        $this->service = $service;
    }

    #[Route('/html-to-pdf', name: 'app_html_to_pdf')]
    public function index(): Response
    {
        //$pdf = $this->service->fromUrl('https://fr.wikipedia.org/wiki/Hello_world');

        //$path = $this->getParameter('kernel.project_dir').'/public/output.pdf';
        //$this->fs->dumpFile($path, $pdf);

        $form = $this->createFormBuilder()
            ->add('url', null, ['required' => true])
            ->getForm();

        return $this->render('pdf/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/generate-from-url', name: 'pdf_from_url')]
    public function generatePdf(Request $request): Response {
        $form = $this->createFormBuilder()
            ->add('url', null, ['required' => true])
            ->getForm();

        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form->getData()['url'];
            $pdf = $this->service->fromUrl($url);

            //return $this->redirectToRoute('pdf_generated_success');
            return new Response($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="output.pdf"'
            ]);
        }

        return $this->render('pdf/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
