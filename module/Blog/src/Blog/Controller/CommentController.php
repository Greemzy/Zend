<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 14/02/2016
 * Time: 15:21
 */

namespace Blog\Controller;

use Zend\Mail\Message;
use Zend\Mvc\Controller\AbstractActionController;
use AuthDoctrine\Entity\Comment;
use Doctrine\ORM\Query;



class CommentController extends  AbstractActionController
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

    // Add content to this method:
    public function submitAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $comment = new Comment();
            $article = $request->getPost('article');

            if(!isset($article) || empty($article)){
                return $this->redirect();
            }
            $id_article = intval($article);
            $article = $this->getEntityManager()->getRepository('AuthDoctrine\Entity\Article')->find($id_article);

            if($article != null){
                $user = $this->identity();
                $email = "";
                if(is_null($user)){
                    $email = $request->getPost('email');
                    $comment->setComUserId(0);
                }
                else{
                    $email = $user->getUsrEmail();
                    $comment->setComUserId($user->getUsrId());
                }

                $text = $request->getPost('text');

                if(!isset($email) || empty($email)){
                    $this->flashMessenger()->addMessage('Your email is empty');
                    return $this->redirect()->toRoute('articleSlug', array( 'category' => $article->getCategorie(), 'name' => $article->getSlug()));
                }
                if(!isset($text) || empty($text)){
                    $this->flashMessenger()->addMessage('Your comment is empty');
                    return $this->redirect()->toRoute('articleSlug', array( 'category' => $article->getCategorie(), 'name' => $article->getSlug()));
                }

                $comment->setComEmail($email);
                $comment->setComArticleId($article->getId());
                $comment->setComText($text);
                $comment->setComStatus("waiting");
                $comment->setComCreatedDate(new \DateTime("now"));
                $comment->setComUpdateDate(new \DateTime("now"));
                $this->getEntityManager()->persist($comment);
                $this->getEntityManager()->flush();
                $this->sendInformationEmail($comment);

                // Redirect to list of albums
                return $this->redirect()->toRoute('articleSlug', array( 'category' => $article->getCategorie(), 'name' => $article->getSlug()));
            }
        }
    return $this->redirect();
    }

    public function sendInformationEmail($comment)
    {
        $article = $this->getEntityManager()->find('AuthDoctrine\Entity\Article', $comment->getComArticleId());
        if(!is_null($article)) {
            $link = $this->getRequest()->getServer('HTTP_HOST') . $this->url()->fromRoute('articleSlug', array('category' => $article->categorie, 'name' => $article->slug));
            $html = "Thanks you for your comment on article <a href='" . $link . "'>" . $article->title . "</a>";
            $transport = $this->getServiceLocator()->get('mail.transport');
            $message = new Message();
            $bodyPart = new \Zend\Mime\Message();
            $bodyMessage = new \Zend\Mime\Part($html);
            $bodyMessage->type = 'text/html';
            $bodyPart->setParts(array($bodyMessage));
            $this->getRequest()->getServer();  //Server vars
            $message->addTo($comment->getComEmail())
                ->addFrom('sageot.m@gmail.com')
                ->setSubject('Your comment')
                ->setBody($bodyPart)
                ->setEncoding('UTF-8');
            $transport->send($message);
        }
        return false;
    }

}