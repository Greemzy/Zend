<?php
namespace AuthDoctrine\Controller;


use AuthDoctrine\Entity\Log;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


use AuthDoctrine\Form\LoginForm;
use AuthDoctrine\Form\LoginFilter;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
	
    public function loginAction()
    {
		$form = new LoginForm();
		$form->get('submit')->setValue('Login');
		$messages = null;

		$request = $this->getRequest();
        if ($request->isPost()) {

			$form->setInputFilter(new LoginFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
				$data = $form->getData();
				$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
				$adapter = $authService->getAdapter();
				$adapter->setIdentityValue($data['username']);
				$adapter->setCredentialValue($data['password']);
				$authResult = $authService->authenticate();

				if ($authResult->isValid()) {
					echo "<h1>I am here</h1>";
					$identity = $authResult->getIdentity();
					$authService->getStorage()->write($identity);
					$time = 1209600; // 14 days 1209600/3600 = 336 hours => 336/24 = 14 days
					if ($data['rememberme']) {
						$sessionManager = new \Zend\Session\SessionManager();
						$sessionManager->rememberMe($time);
					}
					if($identity->getUsrlId() == 4){
						$log = new Log();
						$log->setMessage($identity->getUsrName()." connected");
						$log->setType("admin");
						$log->setUserId($identity->getUsrId());
						$this->getEntityManager()->persist($log);
						$this->getEntityManager()->flush();
					}
					return $this->redirect()->toRoute('home');
				}
				foreach ($authResult->getMessages() as $message) {
					$messages .= "$message\n"; 
				}
			}
		}
		return new ViewModel(array(
			'error' => 'Your authentication credentials are not valid',
			'form'	=> $form,
			'messages' => $messages,
		));
    }
	
	public function logoutAction()
	{
		$auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');

		if ($auth->hasIdentity()) {
			$identity = $auth->getIdentity();
		}
		$auth->clearIdentity();
		$sessionManager = new \Zend\Session\SessionManager();
		$sessionManager->forgetMe();
		return $this->redirect()->toRoute('auth-doctrine/default', array('controller' => 'index', 'action' => 'login'));
		
	}
	
	/**             
	 * @var Doctrine\ORM\EntityManager
	 */                
	protected $em;
	 
	public function getEntityManager()
	{
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		}
		return $this->em;
	}
}