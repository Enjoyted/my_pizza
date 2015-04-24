<?php

namespace Produit\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Produit
{
	public $id;
	public $name;
	public $prix;
	public $path;
	public $id_restaurant;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->name = (!empty($data['name'])) ? $data['name'] : null;
		$this->prix = (!empty($data['prix'])) ? $data['prix'] : null;
		// $this->path = (!empty($data['path'])) ? $data['path'] : null;
		$this->id_restaurant = (!empty($data['id_restaurant'])) ? $data['id_restaurant'] : null;
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
				'name'     => 'name',
				'required' => true,
				'filters'  => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 2,
							'max'      => 50,
							),
						),
					),
				));

			$inputFilter->add(array(
				'name' => 'prix',
				'required' => true,
				'filters'  => array(
					array('name' => 'Int'),
					),
				));
			$inputFilter->add(array(
				'name' => 'id_restaurant',
				'required' => true,
				'filters' => array(
					array('name' => 'Int'),
					),
				));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}