<?php

namespace Commande\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Commande
{
	public $username;
	public $status;
	public $items;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		foreach($_SESSION['user']['panier'] as $value)
		{
			$result += $value->id . ";";
		}
		$this->username = (!empty($data['username'])) ? $data['username'] : $_SESSION['user']['username'];
		$this->status = (!empty($data['status'])) ? $data['status'] : 'new';
		$this->items = (!empty($data['items'])) ? $data['items'] : $result;
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
				'name'     => 'quantity',
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