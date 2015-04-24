<?php

namespace Ingredient\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Ingredient
{
	public $id;
	public $id_produit;
	public $id_fourniture;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->id_produit = (!empty($data['id_produit'])) ? $data['id_produit'] : null;
		$this->id_fourniture = (!empty($data['id_fourniture'])) ? $data['id_fourniture'] : null;
	}

	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();

			$inputFilter->add(array(
				'name'     => 'id',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
					),
				));

			$inputFilter->add(array(
				'name'     => 'id_produit',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
					),
				));
			$inputFilter->add(array(
				'name'     => 'id_fourniture',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
					),
				));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}