<?php

namespace User;

use User\Model\User;
use User\Model\UserTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Crypt\Password\Bcrypt;


class Module
{
	public function getAutoloaderConfig()
	{
		return array(
             'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
             ),
             'Zend\Loader\StandardAutoloader' => array(
                 'namespaces' => array(
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                 ),
             ),
         );
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				'User\Model\UserTable' => function($sm) {
					$tableGateway = $sm->get('UserTableGateway');
					$table = new UserTable($tableGateway);
					return $table;
				},
				'UserTableGateway' => function($sm){
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new User());
					return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
				},
				'AuthService' => function($sm) {
	                    //My assumption, you've alredy set dbAdapter
	                    //and has users table with columns : user_name and pass_word
	                    //that password hashed with md5
		            $dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 
                        'user','username','password','sha1(?)');
		             
		            $authService = new AuthenticationService();
		            $authService->setAdapter($dbTableAuthAdapter);
		              
		            return $authService;
        		},
			),
		);
	}
}

?>