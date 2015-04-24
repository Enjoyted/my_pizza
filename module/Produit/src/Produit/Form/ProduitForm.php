<?php

namespace Produit\Form;

use Zend\Form\Form;

class ProduitForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('produit');

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
        // $this->add(array(
        //     'type' => 'Zend\Form\Element\MultiCheckbox',
        //     'name' => 'ingredient',
        //     'options' => array(
        //         'empty_option' => 'Choose ingredients !',
        //         'label' => 'Ingredient',
        //         'value_options' => array(
        //             '1' => 'Oeufs',
        //             '2' => 'Emmental',
        //             '3' => 'Mozarella',
        //             '4' => 'Cantal',
        //             '5' => 'Oignon',
        //             '6' => 'Steak Hache',
        //             '7' => 'Merguez',
        //             '8' => 'Peperonni',
        //             '9' => 'Sauce tomate',
        //             '10' => 'Sauce creme fraiche',
        //             '11' => 'Creme caramel',
        //             '12'  => 'Fondant chocolat',
        //             '13' => 'Ile Flotante',
        //             '14' => 'Salade',
        //             '15' => 'Tomates',
        //             '16' => 'Poulet',
        //             '17' => 'Thon',
        //             '18' => 'Endives',
        //             '19' => 'Piment',
        //             '20' => 'Mais',
        //             '21' => 'Concombres',
        //             '22' => 'Coca cola',
        //             '23' => 'Orangina',
        //             '24' => 'Ice Tea',
        //             '25' => 'Eau',
        //             '26' => 'Sprite',
        //             '27' => 'Jus de Pomme',
        //             '28' => 'Pate a pizza',
        //             '29' => 'Boite a pizza',
        //             '30' => 'Boite a salade',
        //         ),
        //     )
        // ));
        $this->add(array(
            'name' => 'prix',
            'type' => 'Text',
            'options' => array(
                'label' => 'Prix',
                ),
            ));
        $this->add(array(
            'name' => 'id_restaurant',
            'type' => 'Text',
            'options' => array(
                'label' => 'Restaurant',
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