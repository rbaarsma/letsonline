<?php

class harderwijkImportusersTask extends sfBaseTask
{
  protected $category_conversion = array(
    1 => 2,
    2 => 4,
    3 => 13,
    4 => 5,
    5 => 14,
    6 => 22,
    7 => 23,
    8 => 21,
    9 => 9,
    10 => 11,
    11 => 10,
    12 => 12,
    13 => 3,
    14 => 16,
    15 => 17,
    16 => 20,
    17 => 7,
    18 => 15,
    21 => 2,
    22 => 4,
    23 => 13,
    24 => 5,
    25 => 14,
    26 => 22,
    27 => 23,
    28 => 21,
    29 => 9,
    30 => 11,
    31 => 10,
    32 => 12,
    33 => 3,
    34 => 16,
    35 => 17,
    36 => 20,
    37 => 7,
    38 => 15
  );
  protected $codes = array(
    "P" => "prof",
    "S" => "trained",
    "H" => "hobby",
    "" => "n/a"
  );
  protected $types = array(
    "aanbod" => "offer",
    "vraag" => "request"
  );

  protected $offers = array(),
            $users = array();

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
    $this->name             = 'import-offers';
    $this->briefDescription = 'Import harderwijk offers & requests from csv.';
    $this->detailedDescription = <<<EOF
The [import-offers|INFO] imports all users from CSV.

CSV needs the following 9 columns:
user_id, first_name, between_name, last_name, address, zip_code, phone_home, phone_mobile, email

The file name is relative to the symfony directory.

Call it with:
  [php symfony import-offers csvfile (projectslug)|INFO]

Example:
  [php symfony import-offers data/import/harderwijk-users.csv|INFO]
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

    // preload users
    $result = Doctrine_Core::getTable('User')->createQuery('u')->select('u.no')->where("project_id = ?", $project->getId())->fetchArray();
    foreach ($result as $array)
      $this->users[$array['no']] = $array['id'];

    // delete all first
    $result = Doctrine_Query::create()->delete()->from("Offer")->where("project_id = ?", $project->getId())->execute();
    $this->logSection("db", "offers of project 3 removed");

    // process csv
    $row = 0;
    if (($handle = fopen($file, "r")) !== FALSE)
    {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
      {
        $row++;

        if (count($data) != 10 || !is_numeric($data[0]))
          continue;
        
        list($category, $type, $lidnr, $code, $description) = $data;

        $category_id  = $this->category_conversion[$category];
        $user_id      = @$this->users[$lidnr];
        $experience   = $this->codes[$code ? substr($code, 0, 1) : ""];
        $type         = $this->types[$type];

        if (!$user_id)
        {
          $this->logSection("skip", "Skipped row $row: lidnr $lidnr is not found.");
          continue;
        }

        if (!$category_id || !$experience || !$type)
          throw new Exception("Invalid row: ".print_r($data, true));

        $offer = new Offer();
        $offer->setProjectId($project->getId());
        $offer->setCategoryId($category_id);
        $offer->setUserId($user_id);
        $offer->setType($type);
        $offer->setExperience($experience);
        $offer->setDescription($description);
        $offer->save();

        $this->logSection("import", sprintf("Offer (%s,%s,%s,%s,%s) imported.", $category_id, $user_id, $type,$experience, $description));
      }
      fclose($handle);
    }
    else
    {
      throw new Exception("Could not open file handle for reading.");
    }

    $this->log("$row rows succesfully imported");
  }

}
