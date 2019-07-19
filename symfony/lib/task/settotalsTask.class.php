<?php

class settotalsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'letsonline';
    $this->name             = 'set-totals';
    $this->briefDescription = 'Set totals for ALL users';
    $this->detailedDescription = <<<EOF
The [letsonline:set-totals|INFO] sets the proper LETS totals for ALL users.

Call it with:

  [php symfony letsonline:set-totals|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $users = Doctrine_Core::getTable('User')->createQuery()->execute();
    foreach ($users as $user)
    {
      $user->setTotalReceived();
      $user->setTotalPayed();
      $user->save();
    }

    $this->log(sprintf("Totals set for %d users.", $users->count()));
  }
}
