<?php

namespace Commande;

use Commande\Model\Commande;
use Commande\Model\CommandeTable;
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
				'Commande\Model\CommandeTable' => function($sm) {
					$tableGateway = $sm->get('CommandeTableGateway');
					$table = new CommandeTable($tableGateway);
					return $table;
				},
				'CommandeTableGateway' => function($sm){
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Commande());
					return new TableGateway('commande', $dbAdapter, null, $resultSetPrototype);
				},
			),
		);
	}
}

?>