<?php

class myDoctrineRecord extends sfDoctrineRecord
{
  public function save(Doctrine_Connection $conn = null)
  {
    if ($this->getTable()->getTableName() !== "lets_category")
      if ($this->getTable()->hasColumn('project_id') && (int)$this->getProjectId() === 0)
        throw new Exception("column project_id left unset on ".$this->getTable()->getTableName().print_r($this->_data, true));

    parent::save($conn);
  }
}

?>
