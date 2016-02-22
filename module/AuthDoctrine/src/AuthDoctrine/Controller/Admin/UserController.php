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
use AuthDoctrine\Form\UserForm;
use AuthDoctrine\Form\UserFilter;
use AuthDoctrine\Entity\User;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class UserController extends AbstractActionController
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
        return new ViewModel(array('rowset' => $this->getEntityManager()->getRepository('AuthDoctrine\Entity\User')->findAll()));
    }

    public function createAction()
    {
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $user = new User;
        $form = new UserForm();
        $form->setHydrator(new DoctrineHydrator($entityManager,'AuthDoctrine\Entity\User'));
        $form->bind($user);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new UserFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->prepareData($user);
                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array('controller' => 'user', 'action' => 'index'));
            }
        }

        return new ViewModel(array('form' => $form));
    }

    // U - Update
    public function updateAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'user',
                'action' => 'add'
            ));
        }

        $user = $this->getEntityManager()->find('AuthDoctrine\Entity\User', $id);
        if (!$user) {
            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'user',
                'action' =>  'index'
            ));
        }
        $form = new UserForm();
        $form->bind($user);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new UserFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->prepareData($user);
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array('controller' => 'user', 'action' => 'index'));
            }
        }

        return new ViewModel(array('form' => $form, 'id' => $id));
    }

    // D - delete
    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        if ($id) {
            $user = $this->getEntityManager()->find('AuthDoctrine\Entity\User', $id);
            if ($user) {
                $this->getEntityManager()->remove($user);
                $this->getEntityManager()->flush();
            }
        }

        return $this->redirect()->toRoute('admin/default', array('controller' => 'user', 'action' => 'index'));
    }

    public function prepareData($user)
    {

        $user->setUsrActive($user->getUsrActive());
        $user->setUsrPasswordSalt($this->generateDynamicSalt());
        $user->setUsrPassword($this->encriptPassword(
            $this->getStaticSalt(),
            $user->getUsrPassword(),
            $user->getUsrPasswordSalt()
        ));
        $user->setUsrlId($user->getUsrlId());
        $user->setUsrRegistrationDate(new \DateTime());
        $user->setUsrRegistrationToken(md5(uniqid(mt_rand(), true)));
        $user->setUsrEmailConfirmed($user->getUsrEmailConfirmed());
        return $user;
    }

    public function generateDynamicSalt()
    {
        $dynamicSalt = '';
        for ($i = 0; $i < 50; $i++) {
            $dynamicSalt .= chr(rand(33, 126));
        }
        return $dynamicSalt;
    }

    public function getStaticSalt()
    {
        $staticSalt = '';
        $config = $this->getServiceLocator()->get('Config');
        $staticSalt = $config['static_salt'];
        return $staticSalt;
    }

    public function encriptPassword($staticSalt, $password, $dynamicSalt)
    {
        return $password = md5($staticSalt . $password . $dynamicSalt);
    }
}