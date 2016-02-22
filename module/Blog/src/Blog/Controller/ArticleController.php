<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 02/02/2016
 * Time: 10:55
 */

namespace Blog\Controller;

use Blog\Model\VideoApi;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Entity\Comment;
use Blog\Entity\Article;
use Doctrine\ORM\EntityManager;

class ArticleController extends  AbstractActionController
{
    /**
     * @var DoctrineORMEntityManager
     */
    protected $em;

    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function indexAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        return new ViewModel(array(
            'articles' => $this->getEntityManager()->find('AuthDoctrine\Entity\Article', $id),
        ));
    }

    public function showAction(){
        $category = $this->params()->fromRoute('category', 0);
        if(!empty($category)) {
            $name = $this->params()->fromRoute('name', 0);
            if (!empty($name)) {
                $article = $this->getEntityManager()->getRepository('AuthDoctrine\Entity\Article')->findOneBy(array('slug' => $name));
                if ($article != null) {
                    $last_articles = $this->getEntityManager()->getRepository('AuthDoctrine\Entity\Article')->findBy(array('categorie' => $article->getCategorie()), array('created_at' => 'ASC'), 3);
                    $comments = $this->getEntityManager()->getRepository('AuthDoctrine\Entity\Comment')->findBy(array('comArticleId' => $article->getId(), 'comStatus' => 'valid'));
                    $categories = $this->getCategoriesRedundant();
                    $videoApi = new VideoApi();
                    $video = "";
                    if (!is_null($article->getVideo()) && $article->getVideo() != 0) {
                        $video = $videoApi->getVideoById($article->getVideo());
                    }
                    return new ViewModel(array(
                        'article' => $article,
                        'video' => $video,
                        'last_articles' => $last_articles,
                        'categories' => $categories,
                        'comments' => $comments,
                        'flashMessages' => $this->flashMessenger()->getMessages()
                    ));
                }
            }
            return $this->redirect()->toRoute('articleCategory', array('category' => $category));
        }
        return $this->redirect()->toRoute('home');
    }

    private function getCategoriesRedundant()
    {
        return $this->getEntityManager()->createQuery("SELECT COUNT(a.categorie) r, a.categorie FROM AuthDoctrine\Entity\Article a GROUP BY a.categorie ORDER BY r DESC")->getResult();
    }
}