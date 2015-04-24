<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'Commande\Controller\Commande' => 'Commande\Controller\CommandeController',
         ),
     ),
     'router' => array(
        'routes' => array(
            'commande' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/commande[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        ),
                    'defaults' => array(
                        'controller' => 'Commande/Controller/Commande',
                        'action' => 'index',
                        ),
                    ),
                ),
            ),
        ),
     'view_manager' => array(
         'template_path_stack' => array(
             'commande' => __DIR__ . '/../view',
         ),
     ),
 );