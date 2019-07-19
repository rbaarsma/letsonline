<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FilterAdminMemberForm extends FilterForm
{
  public function setup()
  {
    $choices = array(
      0=>'All active users',
      1=>'All deactivated users',
      'all'=>'All users'
    );
    $this->setWidgets(array(
      'deleted' => new sfWidgetFormChoice(array("choices"=>$choices), array("help"=>"Show deactivated users")),
      'user_or_email' => new sfWidgetFormInputText()
    ));

    parent::setup();
  }

  public function configure()
  {
    $this->getWidgetSchema()->setHelps(array(
        'deleted' => "Show deactivated users",
        'user_or_email' => "Filter by User or Email"
     ));

    parent::configure();
  }
}