<?php
namespace Produit\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Produit\Model\Produit;
use Produit\Form\ProduitForm;
use Zend\Session\Container;

class ProduitController extends AbstractActionController
{
	protected $produitTable;
	protected $fournitureTable;

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

	public function getProduitTable()
	{
		if(!$this->produitTable)
		{
			$sm = $this->getServiceLocator();
			$this->produitTable = $sm->get('Produit\Model\ProduitTable');
		}
		return $this->produitTable;
	}

	public function getFournitureTable()
	{
		if(!$this->fournitureTable)
		{
			$sm = $this->getServiceLocator();
			$this->fournitureTable = $sm->get('Fourniture\Model\FournitureTable');
		}
		return $this->fournitureTable;
	}

	public function indexAction()
	{
    	$ret = null;
    	if (($ret = $this->init()) != null)
    		return ($ret);

		if(isset($_SESSION['user'])) 
		{ 
			if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == '3') 
			{
				return new ViewModel(array(
					'produits' => $this->getProduitTable()->fetchAll(),
				));
			} 
			else 
			{
				return $this->redirect()->toRoute('forbidden');
			}
		}
		else
		{
			return $this->redirect()->toRoute('forbidden');
		}
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

		$form = new ProduitForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$produit = new Produit();
			$form->setInputFilter($produit->getInputFilter());
			$form->setData($request->getPost());
			if($form->isValid())
			{
				$produit->exchangeArray($form->getData());
				$this->getProduitTable()->saveProduit($produit);

				return $this->redirect()->toRoute('produit');
			}
		}
		return array('form' => $form);
	}

	public function addlinkAction()
	{
		$fournitures = $this->getFournitureTable()->fetchAll();

		return new ViewModel(array('fournitures' => $fournitures));
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
			return $this->redirect()->toRoute('produit', array(
				'action' => 'add'
				));
		}

		try{
			$produit = $this->getProduitTable()->getProduit($id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('produit', array(
				'action' => 'index'
				));
		}

		$form = new ProduitForm();
		$form->bind($produit);
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$form->setInputFilter($produit->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid())
			{
				$this->getProduitTable()->saveProduit($produit);

				return $this->redirect()->toRoute('produit');
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
			return $this->redirect()->toRoute('produit');
		}

		$request = $this->getRequest();
		if($request->isPost())
		{
			$del = $request->getPost('del', 'No');

			if($del == 'Yes')
			{
				$id = (int) $request->getPost('id');
				$this->getProduitTable()->deleteProduit($id);
			}

			return $this->redirect()->toRoute('produit');
		}

		return array(
			'id' => $id,
			'produit' => $this->getProduitTable()->getProduit($id),
			);
	}

	public function panierAction()
	{
		$session = new Container('user');

		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('restaurant');
		}
		if(!isset($_SESSION['user']['panier']))
		{
			$_SESSION['user']['panier'] = array();
		}
		$produit = $this->getProduitTable()->getProduit($id);
		$panier = $_SESSION['user']['panier'];
		array_push($panier, $produit);
		$session->offsetSet('panier', $panier);
		
		return $this->redirect()->toRoute('restaurant', array('action' => 'carte', 'id' => $produit->id_restaurant));
	}

	public function viewpanierAction()
	{
		$session = new Container('user');
		if(!isset($_SESSION['user']['tmp']))
		{
			$session->offsetSet('tmp', $_SESSION['user']['panier']);
		}

		$id_resto = $_SESSION['user']['last_resto'];
		$tmp = $_SESSION['user']['tmp'];
		if($session->offsetExists('panier'))
		{
			foreach($_SESSION['user']['panier'] as $k => $v)
			{
				if($v->id_restaurant != $id_resto)
				{
					unset($_SESSION['user']['panier'][$k]);
				}
			}
			$produits = $_SESSION['user']['panier'];
			
			$total = 0;
			foreach ($produits as $key => $value) {
				$total += $value->prix;
			}

			return new ViewModel(array(
				'produits' => $produits,
				'total' => $total,
				'tmp' => $tmp,
				'id_resto' => $id_resto,
			));
		}
		else
		{
			$error = "Veuillez nous excuser mais il n'y a rien dans votre panier ! :)";
			return new ViewModel(array(
				'error' => $error,
				'tmp' => $tmp,
				'id_resto' => $id_resto,
			));
		}
	}

	public function removeItemAction()
	{
		$session = new Container('user');
		$id = (int) $this->params()->fromRoute('id');
		
		$tmp = $_SESSION['user']['tmp'];
		$panier = $_SESSION['user']['panier'];
		unset($panier[$id]);
		unset($tmp[$id]);

		$_SESSION['user']['panier'] = $panier;
		$_SESSION['user']['tmp'] = $tmp;
		return $this->redirect()->toRoute('produit', array('action' => 'viewpanier'));
	}

	public function cleanPanierAction()
	{
		$session = new Container('user');

		$_SESSION['user']['panier'] = NULL;
		$_SESSION['user']['tmp'] = NULL;
		$this->redirect()->toRoute('produit', array('action' => 'viewpanier'));
	}
}