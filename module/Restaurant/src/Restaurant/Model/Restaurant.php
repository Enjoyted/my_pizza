<?php

namespace Restaurant\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Restaurant
{
	public $id;
	public $name;
	public $adresse;
	public $tel;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->name = (!empty($data['name'])) ? $data['name'] : null;
		$this->adresse = (!empty($data['adresse'])) ? $data['adresse'] : null;
		$this->tel = (!empty($data['tel'])) ? $data['tel'] : null;
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
				'name'     => 'adresse',
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
							'max'      => 100,
							),
						),
					),
				));

			$inputFilter->add(array(
				'name' => 'tel',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 10,
							'max'      => 15,
							),
						),
					),
				));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}