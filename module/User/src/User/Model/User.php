<?php

namespace User\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Crypt\Password\Bcrypt;


class User
{
	public $id;
	public $username;
	public $password;
	public $email;
	public $role;
	public $status;
	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->username = (!empty($data['username'])) ? $data['username'] : null;
		$this->password = (!empty($data['password'])) ? sha1($data['password']) : null;
		$this->email = (!empty($data['email'])) ? $data['email'] : null;
		$this->role = (!empty($data['role'])) ? $data['role'] : '1';
		$this->status = (!empty($data['status'])) ? $data['status'] : 'active';
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
				'name'     => 'username',
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

			// $inputFilter->add(array(
			// 	'name'     => 'password',
			// 	'required' => true,
			// 	));

			$inputFilter->add(array(
				'name' => 'email',
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					),
				'validators' => array(
					array(
						'name'    => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min'      => 5,
							'max'      => 60,
							),
						),
					),
				));

			// $inputFilter->add(array(
			// 	'name'     => 'role',
			// 	'required' => true,
			// 	'filters' => array(
			// 		array('name' => 'Int'),
			// 		),
			// 	));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}