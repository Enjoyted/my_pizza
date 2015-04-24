<?php

namespace Produit\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
 
class ForbiddenController extends AbstractActionController
{
    public function indexAction()
    {
    	if(isset($_SESSION['user'])) { 
			if(isset($_SESSION['role'])) {
				if($_SESSION['role'] == '3') {
        			return $this->redirect()->toRoute('produit');
        		}	
        	}
        }
        else {
        	return new ViewModel();
    	}
    }
}

?>