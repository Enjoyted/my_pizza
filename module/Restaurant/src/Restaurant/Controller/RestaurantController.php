<?php
namespace Restaurant\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Restaurant\Model\Restaurant;
use Restaurant\Form\RestaurantForm;
use Zend\Session\Container;

class RestaurantController extends AbstractActionController
{
	protected $restaurantTable;
	protected $produitTable;

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

	public function getRestaurantTable()
	{
		if(!$this->restaurantTable)
		{
			$sm = $this->getServiceLocator();
			$this->restaurantTable = $sm->get('Restaurant\Model\RestaurantTable');
		}
		return $this->restaurantTable;
	}

	public function getProduitTable()
	{
		if(!$this->produitTable)
		{
			$sm = $this->getServiceLocator();
			$this->produitTable = $sm->get('Produit\Model\ProduitTable');
		}
		return $this->produitTable;
	}

	public function indexAction()
	{
    	$ret = null;
    	if (($ret = $this->init()) != null)
    		return ($ret);

		$session = new Container('user');
		return new ViewModel(array(
			'restaurants' => $this->getRestaurantTable()->fetchAll(),
		));
	}

	public function addAction()
	{
    	$ret = null;
    	if (($ret = $this->init()) != null)
    		return ($ret);

    	$session = new Container('user');
    	if(isset($_SESSION['user']) && $_SESSION['user']['role'] != '3')
    	{
    		return $this->redirect()->toRoute('forbidden');
    	}

		$form = new RestaurantForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$restaurant = new Restaurant();
			$form->setInputFilter($restaurant->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid())
			{
				$restaurant->exchangeArray($form->getData());
				$this->getRestaurantTable()->saveRestaurant($restaurant);

				return $this->redirect()->toRoute('restaurant');
			}
		}
		return array('form' => $form);
	}

	public function editAction()
	{
    	$ret = null;
    	if (($ret = $this->init()) != null)
    		return ($ret);

    	$session = new Container('user');
    	if(isset($_SESSION['user']) && $_SESSION['user']['role'] != '3')
    	{
    		return $this->redirect()->toRoute('forbidden');
    	}

		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('restaurant', array(
				'action' => 'add'
				));
		}

		try{
			$restaurant = $this->getRestaurantTable()->getRestaurant($id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('restaurant', array(
				'action' => 'index'
				));
		}

		$form = new RestaurantForm();
		$form->bind($restaurant);
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$form->setInputFilter($restaurant->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid())
			{
				$this->getRestaurantTable()->saveRestaurant($restaurant);

				return $this->redirect()->toRoute('restaurant');
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

    	$session = new Container('user');
    	if(isset($_SESSION['user']) && $_SESSION['user']['role'] != '3')
    	{
    		return $this->redirect()->toRoute('forbidden');
    	}    	

		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('restaurant');
		}

		$request = $this->getRequest();
		if($request->isPost())
		{
			$del = $request->getPost('del', 'No');

			if($del == 'Yes')
			{
				$id = (int) $request->getPost('id');
				$this->getRestaurantTable()->deleteRestaurant($id);
			}

			return $this->redirect()->toRoute('restaurant');
		}

		return array(
			'id' => $id,
			'restaurant' => $this->getRestaurantTable()->getRestaurant($id),
			);
	}

	public function carteAction()
	{
    	$ret = null;
    	if (($ret = $this->init()) != null)
    		return ($ret);

		$session = new Container('user');

		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('restaurant');
		}
		$_SESSION['user']['last_resto']=$id;
		try{
			$produits = $this->getProduitTable()->fetchAllByRest($id);
		} catch(\Exception $ex) {
			return $this->redirect()->toRoute('restaurant', array(
			'action' => 'index'
			));
		}

		return array(
			'produits' => $produits,
		);
	}
}