<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'Produit\Controller\Produit' => 'Produit\Controller\ProduitController',
         ),
     ),
     'router' => array(
        'routes' => array(
            'produit' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/produit[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        ),
                    'defaults' => array(
                        'controller' => 'Produit/Controller/Produit',
                        'action' => 'index',
                        ),
                    ),
                ),
            ),
        ),
     'view_manager' => array(
         'template_path_stack' => array(
             'produit' => __DIR__ . '/../view',
         ),
     ),
 );