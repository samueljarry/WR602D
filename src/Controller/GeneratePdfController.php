<?php

namespace App\Controller;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\PdfGenerationService;
use \DateTime;

class GeneratePdfController extends AbstractController
{
    private Filesystem $fs;
    private PdfGenerationService $service;
    private SubscriptionRepository $subscriptionRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PdfGenerationService $service, SubscriptionRepository $subscriptionRepository, EntityManagerInterface $entityManager)
    {
        $this->fs = new Filesystem();
        $this->service = $service;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/html-to-pdf', name: 'app_html_to_pdf')]
    public function index(): Response
    {
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

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if($user->getGeneratedPdfCount() >= $user->getSubscriptionId()->getPdfLimit()) {
                return $this->redirectToRoute('upgrade_subscription');
            }

            $url = $form->getData()['url'];
            $pdf = $this->service->fromUrl($url);
            $currentDate = new DateTime();
            $currentDate->format('Y-m-d H:i:s');

            $user->setGeneratedPdfCount($user->getGeneratedPdfCount() + 1);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $publicPath = $this->getParameter('kernel.project_dir').'/public/pdfs/'.$user->getId().'/'.$currentDate->getTimestamp().'.pdf';
            $this->fs->dumpFile($publicPath, $pdf);

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
