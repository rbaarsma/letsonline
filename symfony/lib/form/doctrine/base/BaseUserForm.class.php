<?php

/**
 * User form base class.
 *
 * @method User getObject() Returns the current form's model object
 *
 * @package    letsonline
 * @subpackage form
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $credentials = sfConfig::get('app_user_credentials');
    $yesno = array(
      false => "No",
      true => "Yes"
    );

    $years =array();
    for ($y=1900; $y<date("Y"); $y++)
      $years[$y] = $y;

    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'login'         => new sfWidgetFormInputText(),
      'language'      => new sfWidgetFormI18nChoiceLanguage(array('culture'=>$this->getObject()->getLanguage(),'languages'=>sfConfig::get('app_accept_languages'))),
      'credentials'   => new sfWidgetFormChoice(array('choices'=>$credentials, 'expanded'=>true, 'multiple'=>true)),
      'password'      => new sfWidgetFormInputPassword(),
      'email'         => new sfWidgetFormInputText(),
      'display_name'  => new sfWidgetFormInputText(),
      'first_name'    => new sfWidgetFormInputText(),
      'last_name'     => new sfWidgetFormInputText(),
      'address_line'  => new sfWidgetFormInputText(),
      'zip_code'      => new sfWidgetFormInputText(),
      'city'          => new sfWidgetFormInputText(),
      'country'       => new sfWidgetFormI18nChoiceCountry(array('culture'=>$this->getObject()->getLanguage())),
      'phone_home'    => new sfWidgetFormInputText(),
      'phone_mobile'  => new sfWidgetFormInputText(),
      'phone_work'    => new sfWidgetFormInputText(),
      'birth_date'    => new sfWidgetFormI18nDate(array('years'=>$years,'culture'=>sfContext::getInstance()->getUser()->getCulture())),
      'personal_text' => new sfWidgetFormTextarea(array(), array('rows'=>10, 'id'=>'profile_text')),
      'avatar'        => new sfWidgetFormInputFileAjax(array(
          'file_src'    => $this->getObject()->getAvatarLink(),
          'is_image'    => true,
          'with_delete' => true
      )),
      'show_email'    => new sfWidgetFormChoice(array('choices'=>$yesno)),
      'show_phone'    => new sfWidgetFormChoice(array('choices'=>$yesno)),
      'show_address'  => new sfWidgetFormChoice(array('choices'=>$yesno)),
      'show_age'      => new sfWidgetFormChoice(array('choices'=>$yesno)),
    ));

    $phone_validator = new sfValidatorRegex(array('pattern'=>'/^[ 0-9()+-]{0,30}$/', 'trim'=>true, 'required'=>false));
    $zip_code_validator = new sfValidatorRegex(array('pattern'=>'/^[ 0-9a-zA-Z]+$/', 'trim'=>true, 'required'=>true));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'login'         => new sfValidatorAnd(array(
              new sfValidatorString(array('max_length' => 255)),
              new sfValidatorRegex(array('pattern'=>'/^[a-z_-]+$/', 'trim'=>true))
      )),
      'language'      => new sfValidatorI18nChoiceLanguage(array('required'=>true)),
      'credentials'   => new sfValidatorChoice(array('choices'=>array_keys($credentials), 'multiple'=>true, 'required'=>false)),
      'password'      => new sfValidatorString(array('required'=>true)),
      'email'         => new sfValidatorEmail(array('max_length' => 255, 'required' => true)),
      'first_name'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'display_name'  => new sfValidatorAnd(array(
              new sfValidatorString(array('max_length' => 30, 'required'=>true)),
              new sfValidatorRegex(array('pattern'=>'/^[a-z0-9-| ]+$/i', 'trim'=>true, 'required'=>true))
      )),
      'last_name'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'address_line'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'zip_code'      => $zip_code_validator,
      'city'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'country'       => new sfValidatorI18nChoiceCountry(),
      'phone_home'    => $phone_validator,
      'phone_mobile'  => clone $phone_validator,
      'phone_work'    => clone $phone_validator,
      'birth_date'    => new sfValidatorDate(array('required' => false)),
      'personal_text' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'avatar'        => new ImageValidatorFile(array(
                          'path'       => sfConfig::get('sf_upload_dir').'/avatars',
                          'file_name'  => $this->getObject()->getId()
                         )),
      'show_email'    => new sfValidatorBoolean(array('required' => false)),
      'show_phone'    => new sfValidatorBoolean(array('required' => false)),
      'show_address'    => new sfValidatorBoolean(array('required' => false)),
      'show_age'      => new sfValidatorBoolean(array('required' => false)),
    ));

    // will automatically be with i18n translation
    $this->getWidgetSchema()->setLabels(array(
      'display_name'  => 'Display name',
      'avatar'        => 'Upload avatar',
      'personal_text' => 'Personal text',
      'show_email'    => 'Show e-mail?',
      'show_phone'    => 'Show phone?',
      'show_address'  => 'Show address?',
      'show_age'      => 'Show your age?',
      'language'      => 'Language',
      'email'         => 'E-mail',
      'password'      => 'Password',
      'first_name'    => 'First Name',
      'last_name'     => 'Last Name',
      'email'         => 'Email',
      'address_line'  => 'Address',
      'zip_code'      => 'Zip Code',
      'city'          => 'City',
      'country'       => 'Country',
      'phone_home'    => 'Phone (Home)',
      'phone_work'    => 'Phone (Work)',
      'phone_mobile'  => 'Phone (Mobile)',
    ));

    $phone_invalid_msg = 'Please use a dash(-) instead of space( )';
    $this->getValidatorSchema()->setMessages(array(
      'phone_home'    => array('invalid'=>$phone_invalid_msg),
      'phone_work'    => array('invalid'=>$phone_invalid_msg),
      'phone_mobile'  => array('invalid'=>$phone_invalid_msg),
    ));

    $this->widgetSchema->setNameFormat('user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }

  // make sure some objects are not modified
  public function save($conn = null)
  {
    // unset some shit that shouldn't be modified
    unset(
      $this['session'],
      $this['user_level'],
      $this['logins'],
      $this['last_login'],
      $this['joined'],
      $this['deleted'],
      $this['deleted_on']
    );

    // also set user culture if language changes
    $language = $this->getObject()->getLanguage();
    sfContext::getInstance()->getUser()->setCulture($language);

    return parent::save($conn);
  }
}