<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\User;
use User\Model\UserTable;
use User\Form\UserForm;
use User\Form\LoginForm;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Sql\Sql;

class UserController extends AbstractActionController
{
	protected $userTable;
    protected $form;
    protected $authservice;

    public function init()
    {
    	$session = new Container('user');
    	if(isset($_SESSION['user']))
    	{
    		if(isset($_SESSION['user']['username']) && $_SESSION['user']['status'] == 'blocked')
    		{
    			return $this->redirect()->toRoute('blocked');
    		}
    	}
    	return (null);
    }

    public function getAuthService()
    {
    	if(!$this->authservice)
    	{
    		$this->authservice = $this->getServiceLocator()->get('AuthService');
    	}
    	return $this->authservice;
    }

	public function getUserTable()
	{
		if(!$this->userTable)
		{
			$sm = $this->getServiceLocator();
			$this->userTable = $sm->get('User\Model\UserTable');
		}
		return $this->userTable;
	}

	public function indexAction()
	{
    	if(!$this->getAuthService()->hasIdentity())
    	{
    		return $this->redirect()->toRoute('login');
    	}
    	$session = new Container('user');
    	if(isset($_SESSION['user']) && $_SESSION['user']['role'] != '3')
    	{
    		return $this->redirect()->toRoute('user');
    	}
		return new ViewModel(array(
			'users' => $this->getUserTable()->fetchAll(),
		));
	}

	public function registerAction()
	{
		if($this->getAuthService()->hasIdentity())
		{
			return $this->redirect()->toRoute('success');			
		}
		$form = new UserForm();
		$form->get('submit')->setValue('Register');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$user = new User();
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());
			if($form->isValid())
			{
				$user->exchangeArray($form->getData());
				$this->getUserTable()->saveUser($user);

				return $this->redirect()->toRoute('user');
			}
		}
		return array('form' => $form);
	}


	public function loginAction()
	{
		if($this->getAuthService()->hasIdentity())
		{
			return $this->redirect()->toRoute('success');
		}

		$form = new LoginForm();
		return array(
			'form' => $form,
			'messages' => $this->flashmessenger()->getMessages(),
			);		
	}

	public function authenticateAction()
    {
        $form       = new LoginForm();
        $redirect = 'login';
        
        $request = $this->getRequest();
        if ($request->isPost()){
            $form->setData($request->getPost());
            if ($form->isValid()){
                //check authentication...
                $this->getAuthService()->getAdapter()
                                       ->setIdentity($request->getPost('username'))
                                       ->setCredential($request->getPost('password'));
                                        
                $result = $this->getAuthService()->authenticate();
                $user = $this->getAuthService()
                					->getAdapter()
                					->getResultRowObject();
                $session = new Container('user');
        		$session->offsetSet('username', $request->getPost('username'));
        		$session->offsetSet('id', $user->id);
        		$session->offsetSet('role', $user->role);
        		$session->offsetSet('status', $user->status);
                foreach($result->getMessages() as $message)
                {
                    $this->flashmessenger()->addMessage($message);
                }
                 
                if ($result->isValid()) {
                	$user = $this->getAuthService()
                					->getAdapter()
                					->getResultRowObject();
                    $redirect = 'success';
                }
            }
        }
        return $this->redirect()->toRoute($redirect);
    }

    public function logoutAction()
    {
    	$session = new Container('user');
    	$session->getManager()->getStorage()->clear('user');
        $this->getAuthService()->clearIdentity();
         
        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }

    public function addAction()
    {
    	$ret = null;
    	if (($ret = $this->init()) != null)
    		return ($ret);
    	
    	if(!$this->getAuthService()->hasIdentity())
    	{
    		return $this->redirect()->toRoute('login');
    	}
    	$session = new Container('user');
    	if(isset($_SESSION['user']) && $_SESSION['user']['role'] != '3')
    	{
    		return $this->redirect()->toRoute('forbidden');
    	}

    	$current_user = $_SESSION['user']['id'];
    	$usere = $this->getUserTable()->getUser($current_user);

		$form = new UserForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$user = new User();
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid())
			{
				$user->exchangeArray($form->getData());
				$this->getUserTable()->saveUser($user);

				return $this->redirect()->toRoute('user', array('action' => 'index'));
			}
		}
		return array('form' => $form);
    }

    public function blockUserAction()
    {
    	$ret = null;
    	if (($ret = $this->init()) != null)
    		return ($ret);
    	
    	if(!$this->getAuthService()->hasIdentity())
    	{
    		return $this->redirect()->toRoute('login');
    	}
    	$session = new Container('user');
    	if(isset($_SESSION['user']) && $_SESSION['user']['role'] != '3')
    	{
    		return $this->redirect()->toRoute('forbidden');
    	}
		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('user');
		}

		$user = $this->getUserTable()->getUser($id);
		$user->status = 'blocked';
		$this->getUserTable()->saveUser($user);

		return $this->redirect()->toRoute('user', array('action' => 'index'));
    }

    public function unblockUserAction()
    {
    	$ret = null;
    	if (($ret = $this->init()) != null)
    		return ($ret);

    	if(!$this->getAuthService()->hasIdentity())
    	{
    		return $this->redirect()->toRoute('login');
    	}
    	$session = new Container('user');
    	if(isset($_SESSION['user']) && $_SESSION['user']['role'] != '3')
    	{
    		return $this->redirect()->toRoute('forbidden');
    	}
		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('user');
		}

		$user = $this->getUserTable()->getUser($id);
		$user->status = 'active';
		$this->getUserTable()->saveUser($user);

		return $this->redirect()->toRoute('user', array('action' => 'index'));
    }

	public function editAction()
	{
    	$ret = null;
    	if (($ret = $this->init()) != null)
    		return ($ret);

    	if(!$this->getAuthService()->hasIdentity())
    	{
    		return $this->redirect()->toRoute('login');
    	}
    	$session = new Container('user');
    	if(isset($_SESSION['user']) && $_SESSION['user']['role'] != '3')
    	{
    		return $this->redirect()->toRoute('forbidden');
    	}
		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('user', array(
				'action' => 'add'
				));
		}

		try{
			$user = $this->getUserTable()->getUser($id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('user', array(
				'action' => 'index'
				));
		}

		$form = new UserForm();
		$form->bind($user);
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());
			if($form->isValid())
			{
				$this->getUserTable()->saveUser($user);

				return $this->redirect()->toRoute('user', array('action' => 'index'));
			}	
		}

		return array(
			'id' => $id,
			'form' => $form,
			);
	}

	public function deleteAction()
	{
    	$ret = null;
    	if (($ret = $this->init()) != null)
    		return ($ret);

    	if(!$this->getAuthService()->hasIdentity())
    	{
    		return $this->redirect()->toRoute('login');
    	}
    	$session = new Container('user');
    	if(isset($_SESSION['user']) && $_SESSION['user']['role'] != '3')
    	{
    		return $this->redirect()->toRoute('forbidden');
    	}
		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('user');
		}

		$request = $this->getRequest();
		if($request->isPost())
		{
			$del = $request->getPost('del', 'No');

			if($del == 'Yes')
			{
				$id = (int) $request->getPost('id');
				$this->getUserTable()->deleteUser($id);
			}

			return $this->redirect()->toRoute('user', array('action' => 'index'));
		}

		return array(
			'id' => $id,
			'user' => $this->getUserTable()->getUser($id),
			);
	}
}