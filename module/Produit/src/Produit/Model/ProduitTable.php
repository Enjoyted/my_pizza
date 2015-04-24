<?php

namespace Produit\Model;

use Zend\Db\TableGateway\TableGateway;

class ProduitTable
{
	protected $tableGateway;

	public function __construct(TableGateway $TableGateway)
	{
		$this->tableGateway = $TableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function fetchAllById($id)
	{
		$resultSet = $this->tableGateway->select(array('id' => $id));
		return $resultSet;
	}

	public function fetchAllByRest($id)
	{
		$resultSet = $this->tableGateway->select(array('id_restaurant' => $id));
		return $resultSet;
	}

	public function getProduit($id)
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

	public function saveProduit(Produit $produit)
	{
		$data = array(
			'name' => $produit->name,
			'prix' => $produit->prix,
			'id_restaurant' => $produit->id_restaurant,
			);
		$id = (int) $produit->id;
		if($id == 0)
		{
			$this->tableGateway->insert($data);
		}
		else
		{
			if($this->getProduit($id))
			{
				$this->tableGateway->update($data, array('id' => $id));
			}
			else
			{
				throw new \Exception('Produit id does not exist');
			}
		}
	}

	public function deleteProduit($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}