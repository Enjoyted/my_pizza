<?php

namespace Ingredient\Model;

use Zend\Db\TableGateway\TableGateway;

class IngredientTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getIngredient($id)
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

	public function saveIngredient(Ingredient $ingredient)
	{
		$data = array(
			'id_produit' => $ingredient->id_produit,
			'id_fourniture' => $ingredient->id_fourniture,
			);
		$id = (int) $ingredient->id;
		if($id == 0)
		{
			$this->tableGateway->insert($data);
		}
		else
		{
			if($this->getIngredient($id))
			{
				$this->tableGateway->update($data, array('id' => $id));
			}
			else
			{
				throw new \Exception('Ingredient id does not exist');
			}
		}
	}

	public function deleteIngredient($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}