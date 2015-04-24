<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'Ingredient\Controller\Ingredient' => 'Ingredient\Controller\IngredientController',
         ),
     ),
     'router' => array(
        'routes' => array(
            'ingredient' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/ingredient[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        ),
                    'defaults' => array(
                        'controller' => 'Ingredient/Controller/Ingredient',
                        'action' => 'index',
                        ),
                    ),
                ),
            ),
        ),
     'view_manager' => array(
         'template_path_stack' => array(
             'ingredient' => __DIR__ . '/../view',
         ),
     ),
 );