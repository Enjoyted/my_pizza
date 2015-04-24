<?php
namespace Ingredient\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ingredient\Model\Ingredient;
use Ingredient\Form\IngredientForm;
use Zend\Session\Container;

class IngredientController extends AbstractActionController
{
	protected $ingredientTable;

	public function getIngredientTable()
	{
		if(!$this->ingredientTable)
		{
			$sm = $this->getServiceLocator();
			$this->ingredientTable = $sm->get('Ingredient\Model\IngredientTable');
		}
		return $this->ingredientTable;
	}

	public function indexAction()
	{
		return new ViewModel(array(
			'ingredients' => $this->getIngredientTable()->fetchAll(),
		));
	}

	public function addAction()
	{
		$form = new IngredientForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$ingredient = new Ingredient();
			$form->setInputFilter($ingredient->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid())
			{
				$ingredient->exchangeArray($form->getData());

				$this->getIngredientTable()->saveIngredient($ingredient);
				return $this->redirect()->toRoute('ingredient');
			}
		}
		return array('form' => $form);
	}

	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', '0');
		if(!$id)
		{
			return $this->redirect()->toRoute('ingredient', array(
				'action' => 'add'
				));
		}

		try{
			$ingredient = $this->getIngredientTable()->getIngredient($id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('ingredient', array(
				'action' => 'index'
				));
		}

		$form = new IngredientForm();
		$form->bind($ingredient);
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->getRequest();
		if($request->isPost())
		{
			$form->setInputFilter($ingredient->getInputFilter());
			$form->setData($request->getPost());

			if($form->isValid())
			{
				$this->getIngredientTable()->saveIngredient($ingredient);

				return $this->redirect()->toRoute('ingredient');
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
			return $this->redirect()->toRoute('ingredient');
		}

		$request = $this->getRequest();
		if($request->isPost())
		{
			$del = $request->getPost('del', 'No');

			if($del == 'Yes')
			{
				$id = (int) $request->getPost('id');
				$this->getIngredientTable()->deleteIngredient($id);
			}

			return $this->redirect()->toRoute('ingredient');
		}

		return array(
			'id' => $id,
			'ingredient' => $this->getIngredientTable()->getIngredient($id),
			);
	}
}