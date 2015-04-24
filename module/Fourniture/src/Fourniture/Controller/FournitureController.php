<?php
namespace Fourniture\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Fourniture\Model\Fourniture;
use Fourniture\Form\FournitureForm;
use Zend\Session\Container;

class FournitureController extends AbstractActionController
{
	protected $fournitureTable;

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
		return new ViewModel(array(
			'fournitures' => $this->getFournitureTable()->fetchAll(),
		));
	}

	public function addAction()
	{
		$form = new FournitureForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$fourniture = new Fourniture();
			$form->setInputFilter($fourniture->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid())
			{
				$fourniture->exchangeArray($form->getData());
				$this->getFournitureTable()->saveFourniture($fourniture);

				return $this->redirect()->toRoute('fourniture');
			}
		}
		return array('form' => $form);
	}

	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('fourniture', array(
				'action' => 'add'
				));
		}

		try{
			$fourniture = $this->getFournitureTable()->getFourniture($id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('fourniture', array(
				'action' => 'index'
				));
		}

		$form = new FournitureForm();
		$form->bind($fourniture);
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$form->setInputFilter($fourniture->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid())
			{
				$this->getFournitureTable()->saveFourniture($fourniture);

				return $this->redirect()->toRoute('fourniture');
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
			return $this->redirect()->toRoute('fourniture');
		}

		$request = $this->getRequest();
		if($request->isPost())
		{
			$del = $request->getPost('del', 'No');

			if($del == 'Yes')
			{
				$id = (int) $request->getPost('id');
				$this->getFournitureTable()->deleteFourniture($id);
			}

			return $this->redirect()->toRoute('fourniture');
		}

		return array(
			'id' => $id,
			'fourniture' => $this->getFournitureTable()->getFourniture($id),
			);
	}
}