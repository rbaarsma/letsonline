<?php

class ImageValidatedFile extends sfValidatedFile
{
  protected $file_name = null;

  public function setFileName($name)
  {
    if (is_null($name))
      $name = $this->getOriginalName();

    // to be on the safe side
    $name = str_replace(array("/", "\\", ":", "#", "'","?"), "", $name);
    $name = str_replace(array(" ", "__", "#","&"), "_", $name);

    // ensure strickt jpg
    $parts = explode(".",$name);
    $name = $parts[0] . ".jpg";

    // should normally not happen
    if (empty($name))
      $name = "n_a";

    $this->file_name = $name;
  }

  public function getFileName()
  {
    if (is_null($this->file_name))
      throw new Exception("file_name is empty. Try use ImageValidatedFile together with ImageValidatorFile");

    return (string)$this->file_name;
  }

  public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777)
  {
    // get max_width, max_height from size
    list($max_width, $max_height) = explode("x",sfConfig::get('app_avatar_size')); // ex. 100x100

    $filename = $this->getFileName();

    // create thumbnail
    $thumbnail = new sfThumbnail($max_width, $max_height, false, false, 90);
    $thumbnail->loadFile($this->getTempName());
    $thumbnail->save($this->getPath()."/".$filename);

    return $filename."?".time(); // add time so browser will update newer image with same name
  }
}

?>
