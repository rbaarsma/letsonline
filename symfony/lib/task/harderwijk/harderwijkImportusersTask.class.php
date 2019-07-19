<?php

class harderwijkImportusersTask extends sfBaseTask
{
  protected $logins = array(),
            $display_names = array(),
            $lid_nos = array();

  protected function configure()
  {
    //add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'The csv file with the users.'),
      new sfCommandArgument('project', sfCommandArgument::OPTIONAL, 'The project slug for whom the users are', 'harderwijk'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'harderwijk';
    $this->name             = 'import-users';
    $this->briefDescription = 'Import users from csv.';
    $this->detailedDescription = <<<EOF
The [import-users|INFO] imports all users from CSV.

CSV needs the following 9 columns:
user_id, first_name, between_name, last_name, address, zip_code, phone_home, phone_mobile, email

The file name is relative to the symfony directory.

Call it with:
  [php symfony import-users csvfile (projectslug)|INFO]

Example:
  [php symfony import-users data/import/harderwijk-users.csv|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // check the file
    $file = $arguments['file'];
    if (substr($file, -4) != ".csv")
      throw new Exception("Supplied file should be .csv");

    if (!is_file($file))
      throw new Exception("File not found: $file");

    if (!is_readable($file))
      throw new Exception("File not readable: $file");

    // check the project
    $project = Doctrine_Core::getTable('Project')->findOneBySlug($arguments['project']);
    if (!$project)
      throw new Exception("Unknown project: {$arguments['project']}");

   // preload logins to know uniqueness
    $result = Doctrine_Core::getTable('User')->createQuery('u')->select('u.login')->fetchArray();
    foreach ($result as $array)
      $this->logins[] = $array['login'];

    // preload display_names
    $result = Doctrine_Core::getTable('User')->createQuery('u')->select('u.display_name, u.no')->where('project_id = ?', $project->getId())->fetchArray();
    foreach ($result as $array)
    {
      $this->display_names[] = $array['display_name'];
      $this->lid_nos[] = $array['no'];
    }

    // process csv
    $first=false;
    if (($handle = fopen($file, "r")) !== FALSE)
    {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
      {
        if (!$first)
        {
          $first=true;
          continue;
        }

        $num = count($data);
        if ($num != 9)
          throw new Exception("Should be 9 columns: lidnr, voornaam, tussen, achternaam, adres, postcode,telvast,telmobiel,email");

        list($lidnr, $voornaam, $tussen, $achternaam, $adres, $postcode,$telvast,$telmobiel,$email) = $data;

        if (in_array($lidnr, $this->lid_nos))
        {
          $this->logSection("skip", sprintf("Import user #%s skipped, already present.", $lidnr));
          continue;
        }

        $user = new User();
        $user->setLogin( $this->generateLogin($project->getId(), $lidnr, $voornaam, $tussen, $achternaam) );
        $user->setNo($lidnr);
        $user->setProjectId($project->getId());
        $user->setDisplayName( $this->generateDisplayName($voornaam, $achternaam) );
        $user->setLanguage($project->getLanguage());
        $user->setPassword( $this->generatePassword() );
        $user->setEmail($email);
        $user->setFirstName($voornaam);
        $user->setLastName($tussen." ".$achternaam);
        $user->setAddressLine($adres);
        $user->setZipCode($postcode);
        $user->setPhoneHome($telvast);
        $user->setPhoneMobile($telmobiel);
        $user->setCity($project->getCity());
        $user->setCountry($project->getCountry());
        $user->save();
        $this->logSection("import", sprintf("User %s (#%s) imported.", $user->getLogin(), $user->getNo()));
      }
      fclose($handle);
    }

    $this->log("Import Succesful");
  }

  protected function generateLogin($project_id, $lidnr, $voornaam, $tussen, $achternaam)
  {
    $login = strtolower(substr($voornaam, 0, 1).$tussen.$achternaam);
    if (in_array($login, $this->logins))
      $login .= $lidnr;
    if (in_array($login, $this->logins))
      $login .= $project_id;
    if (in_array($login, $this->logins))
      throw new Exception("Strange.. login $login already exists");

    $this->logins[] = $login;
    return $login;
  }

  protected function generateDisplayName($voornaam, $achternaam)
  {
    $display_name = ucfirst($voornaam);
    if (in_array($display_name, $this->display_names))
      $display_name .= " ".strtoupper(substr($achternaam, 0, 1));
    if (in_array($display_name, $this->display_names))
      $display_name .= strtolower(substr($achternaam, 1));
    if (in_array($display_name, $this->display_names))
      throw new Exception("Strange.. display name $display_name already exists.");

    $this->display_names[] = $display_name;
    return $display_name;
  }
}
