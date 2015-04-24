<?php

namespace Restaurant\Form;

use Zend\Form\Form;

class RestaurantForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('restaurant');

		$this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'name',
            ),
        ));
        $this->add(array(
            'name' => 'adresse',
            'type' => 'Text',
            'options' => array(
                'label' => 'Adresse',
            ),
        ));
        $this->add(array(
            'name' => 'tel',
            'type' => 'Text',
            'options' => array(
                'label' => 'Telephone',
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