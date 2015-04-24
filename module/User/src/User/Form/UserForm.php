<?php

namespace User\Form;

use Zend\Form\Form;

class UserForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('user');

		$this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'Username',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => 'Password',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'email',
            'options' => array(
                'label' => 'E-Mail',
                ),
            ));

        $this->add(array(
            'name' => 'role',
            'type' => 'Text',
            'options' => array(
                'label' => 'Role',
                ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
	}
}