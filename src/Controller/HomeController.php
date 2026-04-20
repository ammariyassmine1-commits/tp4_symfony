<?php
namespace App\Controller;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
    #[Route('/send-email', name: 'app_send_email')]
public function sendEmail(MailerInterface $mailer): Response
{
    $email = (new Email())
        ->from('hello@example.com')
        ->to('you@example.com')
        ->subject('Nouvel Article !')
        ->text('Un nouvel article a été publié sur le blog.')
        ->html('<p>Un nouvel article a été publié sur le blog.</p>');

    $mailer->send($email);

    return $this->redirectToRoute('app_home');
}
}