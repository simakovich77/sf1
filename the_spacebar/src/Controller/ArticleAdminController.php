<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/new")
     */
    public function new(EntityManagerInterface $em)
    {
        $article = new Article();
        $article->setTitle('Why Asteroids Taste Like Bacon')
            ->setSlug('why-asteroids-taste-like-bacon'.rand(100, 999))
            ->setContent(
<<<EOF
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
EOF
        )
        ;
        //publish most articles
        if (rand(1, 10) > 2) {
            $article->setPublishedAT(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }

        $em->persist($article);
        $em->flush();
        return new Response(sprintf(
            'Hiya! New article id: #%d slug: %s',
                $article->getId(),
                $article->getSlug()
            )

        );
    }

}