<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\CategorieApi;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractActionController
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

    public function indexAction()
    {
        $category = $this->getEntityManager()->createQuery("SELECT DISTINCT(a.categorie) FROM AuthDoctrine\Entity\Article a")->getResult();

        //$cat = new CategorieApi();
        //$categories = $cat->fetchAll();
        $number = count($category) / 2;
        $categories = array_chunk($category,$number);

        $articles = $this->getEntityManager()->getRepository('AuthDoctrine\Entity\Article')->findAll();
        return new ViewModel(array(
            'categories' => $categories,
            'articles' => $articles
        ));
    }


}
