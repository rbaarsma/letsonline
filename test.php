<?PHP
ini_set("display_errors", 1);
error_reporting(E_ALL);


echo realpath("../") ;
die;


echo realpath(dirname(__FILE__));
echo is_dir(realpath(dirname(__FILE__)."\.."));
die;

?>