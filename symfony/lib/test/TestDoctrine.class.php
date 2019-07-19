<?php

class TestDoctrine
{
  private function __construct() {}

  static public function init()
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration( 'frontend', 'test', true);
    new sfDatabaseManager($configuration);
  }

  static public function loadFixtures()
  {
    // messages causes some problem with the relation, so we'll empty it first
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->query('DELETE FROM lets_message');
    unset($dbh);

    Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures');
  }

  static public function loadBaseUsers($array)
  {
    $q = Doctrine_Core::getTable('User')->createQuery('u')->whereIn('u.id', $array);
    return $q->execute();
  }
}

?>
