<?php
/**
 * Created by PhpStorm.
 * User: Ivan_Simakovich1
 * Date: 6/3/2019
 * Time: 5:37 PM
 */
namespace App\Controller;
use App\Service\MarkdownHelper;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Michelf\MarkdownInterface;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Article;

class ArticleController extends AbstractController
{
  public function __construct(bool $isDebug)
  {
    //dump($isDebug);die();
      $this->isDebug = $isDebug;
  }

    /**
   * @Route("/", name="app_homepage")
   */
  public function homepage()
  {
    return $this->render('article/homepage.html.twig');
  }

  /**
   * @Route("/news/{slug}", name="article_show")
   */
  public function show($slug, SlackClient $slack, EntityManagerInterface $em)
  {
      if($slug == 'khaan')
      {
          $slack->sendMessage('Khan', 'Ah, Kirk, my old friend...');
      }

      $repository = $em->getRepository(Article::class);
      $article = $repository->findOneBy(['slug' => $slug]);

      if(!$article) {
          throw $this->createNotFoundException(sprintf('No article for slug "%s"', $slug));
      }

      dump($article); die();
    //dump($isDebug);die();
    $comments = [
      'First generation: 1937 – 1946',
      'Second generation: 1947 – 1962',
      'Third generation: 1963 - present',
    ];

    $articleContent = <<<EOF

Eniac Computer
The **first substantial** computer was the giant 
ENIAC machine by John W. Mauchly and J. Presper Eckert 
at the University of Pennsylvania. ENIAC (Electrical Numerical 
Integrator and [Calculator](https://www.drupal.org/)) used a word of 10 decimal digits instead 
of binary ones like previous automated calculators/computers. ENIAC 
was also the first machine to use more than 2,000 vacuum tubes, using 
nearly 18,000 **vacuum tubes**. Storage of all those vacuum tubes and the 
machinery required to keep the cool took up over 167 square meters 
(1800 square feet) of floor space. Nonetheless, it had punched-card 
input and output and arithmetically had 1 multiplier, 1 divider-square 
rooter, and 20 adders employing decimal "ring counters," which served as 
adders and also as quick-access (0.0002 seconds) read-write register storage.
EOF;

    //  dump($cache); die;


    //dump($markdown); die;

    //$articleContent = $markdown->transform($articleContent);

    $articleContent = $markdownHelper->parse($articleContent);

    return $this->render('article/show.html.twig', [
      'article' => $article,
      'comments' => $comments,
    ]);
  }
  /**
   * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
   */
  public function toggleArticleHeart($slug, LoggerInterface $logger)
  {
    //TODO - actually heart/unheart the article

      $logger->info('Article is being hearted');

      return new JsonResponse(['hearts' => rand(5, 100)]);
  }
}