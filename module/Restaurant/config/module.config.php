<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'Restaurant\Controller\Restaurant' => 'Restaurant\Controller\RestaurantController',
         ),
     ),
     'router' => array(
        'routes' => array(
            'restaurant' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/restaurant[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        ),
                    'defaults' => array(
                        'controller' => 'Restaurant/Controller/Restaurant',
                        'action' => 'index',
                        ),
                    ),
                ),
            ),
        ),
     'view_manager' => array(
         'template_path_stack' => array(
             'restaurant' => __DIR__ . '/../view',
         ),
     ),
 );