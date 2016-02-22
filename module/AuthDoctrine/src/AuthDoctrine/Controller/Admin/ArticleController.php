<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 16/02/2016
 * Time: 22:45
 */

namespace AuthDoctrine\Controller\Admin;

use Application\View\Helper\CustomTools;
use AuthDoctrine\Entity\Article;
use AuthDoctrine\Entity\Log;
use AuthDoctrine\Entity\Tag;
use AuthDoctrine\Form\ArticleFilter;
use Blog\Model\StarsApi;
use Blog\Model\VideoApi;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use AuthDoctrine\Form\ArticleForm;
use Doctrine\ORM\Query;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\View\Model\JsonModel;

class ArticleController extends AbstractActionController
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
        return new ViewModel(array('rowset' => $this->getEntityManager()->getRepository('AuthDoctrine\Entity\Article')->findAll()));
    }

    public function createAction()
    {
        $form = new ArticleForm();
        try{
            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            $article = new Article;
            $form->setHydrator(new DoctrineHydrator($entityManager,'AuthDoctrine\Entity\Article'));
            $form->bind($article);
            $tags = '';
            foreach ($article->tags as $tag){
                $tags .= $tag->getTitle().";";
            }

            $form->get('tagsInline')->setValue($tags);
            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setInputFilter(new ArticleFilter($this->getServiceLocator()));
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $newtags = $form->get('tagsInline')->getValue();
                    $tagsExplode = explode(";", $newtags);
                    foreach($tagsExplode as $tagEx)
                    {
                        $find = $this->getEntityManager()->getRepository('AuthDoctrine\Entity\Tag')->findBy(array('title' => $tagEx));
                        if(is_null($find)){
                            $newtag = new Tag();
                            $newtag->setTitle($tagEx);
                            $this->getEntityManager()->persit($newtag);
                        }
                    }
                    $this->prepareData($article);
                    $this->getEntityManager()->persist($article);
                    $this->getEntityManager()->flush();
                    return $this->redirect()->toRoute('admin/default', array('controller' => 'article', 'action' => 'index'));
                }
            }

        }catch (\Exception $e)
        {
            $log = new Log();
            $log->setMessage($e->getMessage());
            $log->setType("admin");
            $log->setUserId(0);
            $this->getEntityManager()->persist($log);
            $this->getEntityManager()->flush();
        }
        return new ViewModel(array('form' => $form));

    }

    public function updateAction(){

        try{
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) {
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'article',
                    'action' => 'add'
                ));
            }

            $article = $this->getEntityManager()->find('AuthDoctrine\Entity\Article', $id);
            if (!$article) {
                return $this->redirect()->toRoute('admin/default', array(
                    'controller' => 'article',
                    'action' =>  'index'
                ));
            }

            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            $form = new ArticleForm();
            $form->setHydrator(new DoctrineHydrator($entityManager,'AuthDoctrine\Entity\Article'));
            $form->bind($article);
            $tags = '';
            foreach ($article->tags as $tag){

                $tags .= $tag->getTitle().";";
            }

            $form->get('tagsInline')->setValue($tags);
            $form->get('submit')->setAttribute('value', 'Edit');
            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setInputFilter(new ArticleFilter());
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $newtags = $form->get('tagsInline')->getValue();
                    $tagsExplode = explode(";", $newtags);
                    foreach($tagsExplode as $tagEx)
                    {
                        $find = $this->getEntityManager()->getRepository('AuthDoctrine\Entity\Tag')->findBy(array('title' => $tagEx));
                        if(is_null($find)){
                            $newtag = new Tag();
                            $newtag->setTitle($tagEx);
                            $this->getEntityManager()->persit($newtag);
                        }
                    }
                    $this->getEntityManager()->flush();
                    return $this->redirect()->toRoute('admin/default', array(
                        'controller' => 'article',
                        'action' => 'index'
                    ));
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
            );
        }catch (\Exception $e)
        {
            $log = new Log();
            $log->setMessage($e->getMessage());
            $log->setType("admin");
            $log->setUserId(0);
            $this->getEntityManager()->persist($log);
            $this->getEntityManager()->flush();
        }
        return $this->redirect()->toRoute('home');
    }

    public function deleteAction()
    {
        try{
            $id = $this->params()->fromRoute('id');
            if ($id) {
                $article = $this->getEntityManager()->find('AuthDoctrine\Entity\Article', $id);
                if ($article) {
                    $this->getEntityManager()->remove($article);
                    $this->getEntityManager()->flush();
                }
            }
        }catch (\Exception $e)
        {
            $log = new Log();
            $log->setMessage($e->getMessage());
            $log->setType("admin");
            $log->setUserId(0);
            $this->getEntityManager()->persist($log);
            $this->getEntityManager()->flush();
        }

        return $this->redirect()->toRoute('admin/default', array('controller' => 'article', 'action' => 'index'));
    }

    public function prepareData($article)
    {
        try{
            $star = new StarsApi();
            $tools = new CustomTools();
            $auth = new AuthenticationService();
            $title = $tools($article->getTitle());
            $article->setSlug($title);
            $article->setUpdated_at(new \DateTime());
            $article->setCreated_at(new \DateTime());
            $article->setThumb(" ");

            $thumb = $star->getInfoToStar($article->getStar());
            if(is_array($thumb) && array_key_exists('star_thumb', $thumb)){
                $article->setThumb($thumb['star_thumb']);
            }

            $user = $auth->getIdentity();
            if(is_array($user) && array_key_exists('usrId', $user)) {
                $article->setAuthor($user['usrId']);
            }
        }catch (\Exception $e)
        {
            $log = new Log();
            $log->setMessage($e->getMessage());
            $log->setType("admin");
            $log->setUserId(0);
            $this->getEntityManager()->persist($log);
            $this->getEntityManager()->flush();
        }

        return $article;
    }

    public function videoSearchAction()
    {
        try{
            $request = $this->getRequest();
            if ($request->isPost())
            {
                $category = $request->getPost('categories');
                $stars = $request->getPost('stars');
                $resultApi = false;
                if(!empty($category) && !empty($stars))
                {
                    $arraystars = array($stars);
                    $callApi = new VideoApi();
                    $resultApi = $callApi->getVideoBySearch($arraystars, $category);
                }

                $data = new JsonModel(array(
                    'success' => true,
                    'result' => $resultApi
                ));
                return $data;
            }
        }catch (\Exception $e)
        {
            $log = new Log();
            $log->setMessage($e->getMessage());
            $log->setType("admin");
            $log->setUserId(0);
            $this->getEntityManager()->persist($log);
            $this->getEntityManager()->flush();
        }

        return false;
    }
}