<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'User\Controller\User' => 'User\Controller\UserController',
             'User\Controller\Success' => 'User\Controller\SuccessController'
         ),
     ),
     'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/user[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        ),
                    'defaults' => array(
                        'controller' => 'User/Controller/User',
                        'action' => 'login',
                        ),
                    ),
                ),
                'success' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/success',
                        'defaults' => array(
                            '__NAMESPACE__' => 'User\Controller',
                            'controller'    => 'Success',
                            'action'        => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'default' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                'route'    => '/[:action]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                                'defaults' => array(
                                ),
                            ),
                        ),
                    ),
                ),
                'login' => array(
                    'type'    => 'Literal',
                    'options' => array(
                        'route'    => '/auth',
                        'defaults' => array(
                            '__NAMESPACE__' => 'User\Controller',
                            'controller'    => 'User',
                            'action'        => 'login',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'process' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                'route'    => '/[:action]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                                'defaults' => array(
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
     'view_manager' => array(
         'template_path_stack' => array(
             'user' => __DIR__ . '/../view',
         ),
     ),
 );