<?php

namespace Fourniture;

use Fourniture\Model\Fourniture;
use Fourniture\Model\FournitureTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

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
				'Fourniture\Model\FournitureTable' => function($sm) {
					$tableGateway = $sm->get('FournitureTableGateway');
					$table = new FournitureTable($tableGateway);
					return $table;
				},
				'FournitureTableGateway' => function($sm){
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Fourniture());
					return new TableGateway('fourniture', $dbAdapter, null, $resultSetPrototype);
				},
			),
		);
	}
}

?>