<?php

/**
 * User form.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserProfileForm extends BaseUserForm
{
  public function configure()
  {
  	$this->useFields(array('avatar','personal_text','display_name','show_email','show_phone','show_address','show_age'));
    $this->getValidatorSchema()->offsetSet('avatar_delete', new sfValidatorPass());

    $this->getValidatorSchema()->setPostValidator(
      new sfValidatorDoctrineUnique(array('model'=>'User', 'column'=>array('display_name', 'project_id')))
    );

    // there should be an attribute to set so you can use %size% but I can't find it.
    $this->getWidgetSchema()->setHelp('avatar', sprintf("The image will be resized to %s", sfConfig::get('app_avatar_size')));

    $this->getWidgetSchema()->setHelps(array(
      'avatar'        => 'Image needs to a standard standing foto (4:3 relation)',
      'display_name'  => 'How other members will recognize you.',
      'show_email'    => 'Do you want other members to see your e-mail address?',
      'show_phone'    => 'Do you want other members to see your phone number(s)?',
      'show_address'  => 'Do you want other members to see your address?',
      'show_age'      => 'Do you want other members to see your age?',
    ));

    $this->getValidatorSchema()->addMessage('confirm', 'Your profile has been updated.');

    $this->getWidgetSchema()->setPositions(array('id','display_name','avatar','personal_text','show_email','show_phone','show_address','show_age'));
  }
}
