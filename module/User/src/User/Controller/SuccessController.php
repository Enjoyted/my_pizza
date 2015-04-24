<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
 
class SuccessController extends AbstractActionController
{
    public function indexAction()
    {
        if (! $this->getServiceLocator()
                 ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        if($_SESSION['user']['status'] == 'blocked')
        {
        	$blocked = "<p style='color:red'> Attention ! Votre compte est actuellement block&eacute;
        	, de ce fait votre acces au site est fortement restreint. </p>";
            return new ViewModel(array(
        		'blocked' => $blocked,
        	));
        }
        return new ViewModel();
    }
}

?>