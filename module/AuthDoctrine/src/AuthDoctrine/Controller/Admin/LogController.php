<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 16/02/2016
 * Time: 22:45
 */

namespace AuthDoctrine\Controller\Admin;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use AuthDoctrine\Form\ArticleForm;
use Doctrine\ORM\Query;

class LogController extends AbstractActionController
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
        return new ViewModel(array('rowset' => $this->getEntityManager()->getRepository('AuthDoctrine\Entity\Log')->findAll()));
    }
}