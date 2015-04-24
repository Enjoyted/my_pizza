<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'Fourniture\Controller\Fourniture' => 'Fourniture\Controller\FournitureController',
         ),
     ),
     'router' => array(
        'routes' => array(
            'fourniture' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/fourniture[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        ),
                    'defaults' => array(
                        'controller' => 'Fourniture/Controller/Fourniture',
                        'action' => 'index',
                        ),
                    ),
                ),
            ),
        ),
     'view_manager' => array(
         'template_path_stack' => array(
             'fourniture' => __DIR__ . '/../view',
         ),
     ),
 );