<?php
namespace Commande\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Commande\Model\Commande;
use Commande\Form\CommandeForm;
use Zend\Session\Container;

class CommandeController extends AbstractActionController
{
	protected $commandeTable;

	public function getCommandeTable()
	{
		if(!$this->commandeTable)
		{
			$sm = $this->getServiceLocator();
			$this->commandeTable = $sm->get('Commande\Model\CommandeTable');
		}
		return $this->commandeTable;
	}

	public function indexAction()
	{
		$session = new Container('user');
		return new ViewModel(array(
			'commandes' => $this->getCommandeTable()->fetchAll(),
		));
	}

	public function addAction()
	{
		$session = new Container('user');

		$form = new CommandeForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$commande = new Commande();
			$form->setInputFilter($commande->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid())
			{
				$commande->exchangeArray($form->getData());
				$this->getCommandeTable()->saveCommande($commande);

				return $this->redirect()->toRoute('commande');
			}
		}
		return array('form' => $form);
	}

	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('commande', array(
				'action' => 'add'
				));
		}

		try{
			$commande = $this->getCommandeTable()->getCommande($id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('commande', array(
				'action' => 'index'
				));
		}

		$form = new CommandeForm();
		$form->bind($commande);
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$form->setInputFilter($commande->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid())
			{
				$this->getCommandeTable()->saveCommande($commande);

				return $this->redirect()->toRoute('commande');
			}	
		}

		return array(
			'id' => $id,
			'form' => $form,
			);
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('commande');
		}

		$request = $this->getRequest();
		if($request->isPost())
		{
			$del = $request->getPost('del', 'No');

			if($del == 'Yes')
			{
				$id = (int) $request->getPost('id');
				$this->getCommandeTable()->deleteCommande($id);
			}

			return $this->redirect()->toRoute('commande');
		}

		return array(
			'id' => $id,
			'commande' => $this->getCommandeTable()->getCommande($id),
			);
	}

	public function commanderAction()
	{
		$session = new Container('user');

		if(!isset($_SESSION['user']['username']))
		{
			return $this->redirect()->toRoute('user', array('action' => 'login'));
		}

		$datas = $_SESSION['user']['panier'];
		return new ViewModel(array('commande' => $datas));
	}

	public function confirmAction()
	{
		$session = new Container('user');
		$datas = $_SESSION['user']['panier'];

		$commande = new Commande();
		$commande->exchangeArray($datas);
		$this->getCommandeTable()->saveCommande($commande);	
	
		return $this->redirect()->toRoute('produit', array('action' => 'viewpanier'));
	}
}