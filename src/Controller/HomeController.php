<?php
namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(RequestStack $requestStack, ArticleRepository $articleRepository): Response
    {
        $session = $requestStack->getSession();
        $nbVisites = $session->get('nb_visites', 0);
        $session->set('nb_visites', $nbVisites + 1);

        $derniersArticles = $articleRepository->findLastPublished(3);

        return $this->render('home/index.html.twig', [
            'nb_visites' => $nbVisites + 1,
            'articles' => $derniersArticles,
        ]);
    }
}