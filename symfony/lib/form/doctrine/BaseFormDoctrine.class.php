<?php

/**
 * Project form base class.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class BaseFormDoctrine extends sfFormDoctrine
{
  public function setup()
  {
    $help_format  = "<div class='help'>%help%</div>";
    $row_format   = "<tr>\n<th>%label%</th>\n<td>%help%%error%%field%%hidden_fields%</td>\n</tr>\n";
    //$row_format = "<div class='field_left'>%label%</div><div class='field_right'>%error%%help%%field%%hidden_fields%</div>";

    $this->getWidgetSchema()->getFormFormatter()->setHelpFormat($help_format);
    $this->getWidgetSchema()->getFormFormatter()->setRowFormat($row_format);
  }

  // http://pastebin.com/uzvqbAXm
  protected $formStylesheets = array();
  protected $formJavascripts = array();

  /**
   * Set Stylesheets paths for the form.
   * If they are already set, add them.
   *
   * @param $formStylesheets
   * @return void
   */
  protected function setStylesheets(array $formStylesheets)
  {
    if (count($this->formStylesheets) > 0)
    {
      $this->formStylesheets = array_merge($this->formStylesheets, $formStylesheets);
    }
    else
    {
      $this->formStylesheets = $formStylesheets;
    }
  }

  /**
   * Set JavaScript paths for the form.
   * If they are already set, add them.
   *
   * @param $formStylesheets
   * @return void
   */
  protected function setJavaScripts(array $formJavascripts)
  {
    if (count($this->formJavascripts) > 0)
    {
      $this->formJavascripts = array_merge($this->formJavascripts, $formJavascripts);
    }
    else {
      $this->formJavascripts = $formJavascripts;
    }
  }

  /**
   * Gets the Stylesheets paths associated with the form.
   *
   * @see scr/www/application/lib/vendor/symfony/lib/form/sfForm#getStylesheets()
   */
  public function getStylesheets()
  {
    return array_unique(array_merge($this->formStylesheets, parent::getStylesheets()));
  }

  /**
   * Gets the JavaScript paths associated with the form.
   *
   * @see scr/www/application/lib/vendor/symfony/lib/form/sfForm#getJavaScripts()
   */
  public function getJavaScripts()
  {
    return array_unique(array_merge($this->formJavascripts, parent::getJavaScripts()));
  }
}
