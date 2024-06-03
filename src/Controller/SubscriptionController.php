<?php

namespace App\Controller;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    function __construct(SubscriptionRepository $subscriptionRepository, EntityManagerInterface $entityManager)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/subscription', name: 'app_subscription')]
    public function index(): Response
    {
        $subscriptions = $this->subscriptionRepository->findAll();

        return $this->render('subscription/index.html.twig', [
            'subscriptions' => $subscriptions,
        ]);
    }

    #[Route('/update-subscription', name: 'update_subscription')]
    public function updateSubscription(Request $request): Response
    {
        $subscription_id = $request->query->get('subscription_id');
        $subscription = $this->subscriptionRepository->find($subscription_id);

        $user = $this->getUser();
        $user->setSubscriptionId($subscription);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('pdf_from_url');
    }

    #[Route('/upgrade-subscription', name: 'upgrade_subscription')]
    public function noMoreGenerationsLeft(): Response
    {
        $subscriptions = $this->subscriptionRepository->findAll();

        return $this->render('subscription/limit.html.twig', [
            'subscriptions' => $subscriptions,
        ]);
    }
}
