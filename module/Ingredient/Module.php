<?php

namespace Ingredient;

use Ingredient\Model\Ingredient;
use Ingredient\Model\IngredientTable;
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
				'Ingredient\Model\IngredientTable' => function($sm) {
					$tableGateway = $sm->get('IngredientTableGateway');
					$table = new IngredientTable($tableGateway);
					return $table;
				},
				'IngredientTableGateway' => function($sm){
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Ingredient());
					return new TableGateway('ingredient', $dbAdapter, null, $resultSetPrototype);
				},
			),
		);
	}
}

?>