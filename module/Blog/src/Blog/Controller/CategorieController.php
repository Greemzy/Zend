<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 02/02/2016
 * Time: 10:55
 */

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategorieController extends  AbstractActionController
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
        $name = $this->params()->fromRoute('category', 0);
        $articles = $this->getEntityManager()->getRepository('AuthDoctrine\Entity\Article')->findBy(array('categorie' => $name));
        return new ViewModel(array(
            'articles' => $articles
        ));
    }
}