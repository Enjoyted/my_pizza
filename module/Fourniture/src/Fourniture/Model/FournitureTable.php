<?php

namespace Fourniture\Model;

use Zend\Db\TableGateway\TableGateway;

class FournitureTable
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

	public function getFourniture($id)
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

	public function saveFourniture(Fourniture $fourniture)
	{
		$data = array(
			'name' => $fourniture->name,
			'quantity' => $fourniture->quantity,
			);
		$id = (int) $fourniture->id;
		if($id == 0)
		{
			$this->tableGateway->insert($data);
		}
		else
		{
			if($this->getFourniture($id))
			{
				$this->tableGateway->update($data, array('id' => $id));
			}
			else
			{
				throw new \Exception('Fourniture id does not exist');
			}
		}
	}

	public function deleteFourniture($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}