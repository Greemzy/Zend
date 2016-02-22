<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 16/02/2016
 * Time: 22:45
 */

namespace AuthDoctrine\Controller\Admin;

use AuthDoctrine\Entity\Comment;
use AuthDoctrine\Form\CommentFilter;
use Zend\Mail\Message;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use AuthDoctrine\Form\CommentForm;
use Doctrine\ORM\Query;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class CommentController extends AbstractActionController
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
        return new ViewModel(array('rowset' => $this->getEntityManager()->getRepository('AuthDoctrine\Entity\Comment')->findAll()));
    }

    public function createAction()
    {
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $comment = new Comment;
        $form = new CommentForm();
        $form->setHydrator(new DoctrineHydrator($entityManager,'AuthDoctrine\Entity\Article'));
        $form->bind($comment);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new CommentFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getEntityManager()->persist($comment);
                $this->getEntityManager()->flush();
                return $this->redirect()->toRoute('admin/default', array('controller' => 'comment', 'action' => 'index'));
            }
        }
        return new ViewModel(array('form' => $form));
    }

    public function updateAction(){

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'article',
                'action' => 'add'
            ));
        }

        $comment = $this->getEntityManager()->find('AuthDoctrine\Entity\Comment', $id);
        if (!$comment) {
            return $this->redirect()->toRoute('admin/default', array(
                'controller' => 'comment',
                'action' =>  'index'
            ));
        }

        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $form = new CommentForm();
        $form->setHydrator(new DoctrineHydrator($entityManager,'AuthDoctrine\Entity\Comment'));
        $form->bind($comment);
        $form->get('submit')->setAttribute('value', 'Edit');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new CommentFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getEntityManager()->flush();
                if($comment->getComStatus() == 'valid')
                {
                    $this->sendConfirmationEmail($comment);
                }
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'comment',
                    'action' => 'index'
                ));
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        if ($id) {
            $article = $this->getEntityManager()->find('AuthDoctrine\Entity\Comment', $id);
            if ($article) {
                $this->getEntityManager()->remove($article);
                $this->getEntityManager()->flush();
            }
        }

        return $this->redirect()->toRoute('admin/default', array('controller' => 'comment', 'action' => 'index'));
    }

    public function sendConfirmationEmail($comment)
    {
        $article = $this->getEntityManager()->find('AuthDoctrine\Entity\Article', $comment->getComArticleId());
        if(!is_null($article)) {
            $link = $this->getRequest()->getServer('HTTP_HOST') . $this->url()->fromRoute('articleSlug', array('category' => $article->categorie, 'name' => $article->slug));
            $html = "Congratulations, your comment has been validated on article <a href='" . $link . "'>" . $article->title . "</a>";
            $transport = $this->getServiceLocator()->get('mail.transport');
            $message = new Message();
            $bodyPart = new \Zend\Mime\Message();
            $bodyMessage = new \Zend\Mime\Part($html);
            $bodyMessage->type = 'text/html';
            $bodyPart->setParts(array($bodyMessage));
            $this->getRequest()->getServer();  //Server vars
            $message->addTo($comment->getComEmail())
                ->addFrom('sageot.m@gmail.com')
                ->setSubject('Congratulations, your comment has been validated')
                ->setBody($bodyPart)
                ->setEncoding('UTF-8');
            $transport->send($message);
        }
        return false;
    }
}