<?php

class ImageValidatorFile extends sfValidatorFile
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->setOption('validated_file_class', 'ImageValidatedFile');
    $this->setOption('required', false);
    $this->setOption('mime_types', array('image/jpeg', 'image/png', 'image/gif'));


    $this->addOption('file_name', null);
    //$this->addOption('preserve_ratio', true);
    //$this->addOption('resize_formats', array());
  }

  protected function doClean($value)
  {
    $validatedFile = parent::doClean($value);
    $validatedFile->setFileName($this->getOption('file_name'));
    //$validatedFile->setResizeFormats($this->getOption('resize_formats'));
    //$validatedFile->setPreserveRation($this->getOption('preserve_ratio'));

    return $validatedFile;
  }
}

?>
