<?php

namespace Commande\Model;

use Zend\Db\TableGateway\TableGateway;

class CommandeTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet->buffer();
	}

	public function getCommande($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row)
		{
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function saveCommande(Commande $commande)
	{
		$data = array(
			'username' => $commande->username,
			'status' => $commande->status,
			'items' => $commande->items,
			);
		
			$this->tableGateway->insert($data);
	}

	public function deleteCommande($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}