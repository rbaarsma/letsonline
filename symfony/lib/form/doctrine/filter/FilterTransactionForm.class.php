<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FilterCouponForm extends FilterForm
{
  public function setup()
  {
    // get all active users and add 'all users' option.
    $choices = array();
    $choices[''] = "All Users";
    $users = Doctrine_Core::getTable('User')->getOthers();
    foreach ($users as $user)
      $choices[$user->getId()] = $user->__toString();

    $this->setWidgets(array(
      'user'        => new sfWidgetFormChoice(array('choices'=>$choices)),
      'description' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'user'        => new sfValidatorChoice(array('choices'=>array_keys($choices))),
      'description' => new sfValidatorString(array('max_length' => 50, 'required' => true)),
    ));

    parent::setup();
  }

  public function configure()
  {
    $this->getWidgetSchema()->setHelps(array(
       'user'  => 'To or from this user',
       'description'  => 'Find by description:'
    ));

    parent::configure();
  }
}