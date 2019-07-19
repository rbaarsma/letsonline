<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class FilterForm extends BaseForm
{
  public function configure()
  {
    // filter has no label, but can instead use a help
    $help_format  = "<span class='filter_help'>%help%</span>";
    $row_format   = "<tr><td>%help%</td><td>%error%%field%%hidden_fields%</td></tr>\n";
    $this->getWidgetSchema()->getFormFormatter()->setHelpFormat($help_format);
    $this->getWidgetSchema()->getFormFormatter()->setRowFormat($row_format);

    // filter name format
    $this->getWidgetSchema()->setNameFormat('filter[%s]');
  }

  public function render($attributes = array())
  {
    $this->renderFilterJs();
    return parent::render($attributes);
  }

  public function renderFilterJs()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Asset');
    use_javascript('/js/jquery.js');
    use_javascript('/js/filter.js');

    // create an instance of the Filter class
    echo '<script type="text/javascript">';
    echo "filter.onload = function () {";

    // add the proper filter for each widget.
    foreach ($this->getWidgetSchema()->getPositions() as $field)
    {
      $widget = $this->getWidget($field);
      switch (true)
      {
        case $widget instanceof sfWidgetFormChoiceBase:
          echo "filter.addSelect('$field');";
          break;
        case $widget instanceof sfWidgetFormInputText:
          echo "filter.addInput('$field');";
          break;
        case $widget instanceof sfWidgetFormInputHidden:
          break;
        default:
          throw new Exception("Javascript Filter class (filter.js) does not support ".get_class($widget));
      }
    }

    echo "};";
    echo "</script>";
  }
}

?>
