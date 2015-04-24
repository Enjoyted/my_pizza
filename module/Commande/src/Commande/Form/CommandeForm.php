<?php

namespace Commande\Form;

use Zend\Form\Form;

class CommandeForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('commande');

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
            'name' => 'quantity',
            'type' => 'Text',
            'options' => array(
                'label' => 'Quantity',
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