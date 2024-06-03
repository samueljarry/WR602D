<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends AbstractController
{
    #[Route('/history', name: 'app_history')]
    public function index(): Response
    {
        // Supposons que vous ayez une méthode pour obtenir l'ID de l'utilisateur actuel
        $userId = $this->getUser()->getId();

        // Chemin vers le répertoire des fichiers de l'utilisateur
        $directoryPath = $this->getParameter('kernel.project_dir') . '/public/pdfs/' . $userId;

        // S'assurer que le répertoire existe et est lisible
        if (is_dir($directoryPath)) {
            // Récupérer la liste des fichiers
            $files = array_diff(scandir($directoryPath), ['.', '..']);

            // Optionnel: transformer le chemin relatif en URL publiable
            $fileUrls = [];
            $displayedUrls = [];
            foreach ($files as $file) {
                $fileUrls[] = $_ENV['BASE_PATH'] . '/public/pdfs/' . $userId . '/' . $file;
                $displayedUrls[] = $userId . '/' . $file;
            }
        } else {
            // Gérer le cas où le répertoire n'existe pas
            $fileUrls = [];
        }

        return $this->render('history/index.html.twig', [
            'controller_name' => 'HistoryController',
            'files' => $fileUrls,
            'displayedUrls' => $displayedUrls
        ]);
    }
}
