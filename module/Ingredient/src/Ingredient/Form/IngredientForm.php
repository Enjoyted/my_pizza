<?php

namespace Ingredient\Form;

use Zend\Form\Form;

class IngredientForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('ingredient');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'id_produit',
            'type' => 'Text',
            'options' => array(
                'label' => 'Id_Produit',
            ),
        ));
        $this->add(array(
            'name' => 'id_fourniture',
            'type' => 'Text',
            'options' => array(
                'label' => 'Id_Fourniture',
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