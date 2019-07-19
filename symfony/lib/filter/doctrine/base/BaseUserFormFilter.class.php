<?php

/**
 * User filter form base class.
 *
 * @package    letsonline
 * @subpackage filter
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'project_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Project'), 'add_empty' => true)),
      'no'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'login'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'language'       => new sfWidgetFormFilterInput(),
      'session'        => new sfWidgetFormFilterInput(),
      'credentials'    => new sfWidgetFormFilterInput(),
      'user_level'     => new sfWidgetFormFilterInput(),
      'password'       => new sfWidgetFormFilterInput(),
      'email'          => new sfWidgetFormFilterInput(),
      'display_name'   => new sfWidgetFormFilterInput(),
      'first_name'     => new sfWidgetFormFilterInput(),
      'last_name'      => new sfWidgetFormFilterInput(),
      'address_line'   => new sfWidgetFormFilterInput(),
      'zip_code'       => new sfWidgetFormFilterInput(),
      'city'           => new sfWidgetFormFilterInput(),
      'country'        => new sfWidgetFormFilterInput(),
      'phone_home'     => new sfWidgetFormFilterInput(),
      'phone_mobile'   => new sfWidgetFormFilterInput(),
      'phone_work'     => new sfWidgetFormFilterInput(),
      'birth_date'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'personal_text'  => new sfWidgetFormFilterInput(),
      'avatar'         => new sfWidgetFormFilterInput(),
      'show_email'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'show_phone'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'show_address'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'show_age'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'logins'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'last_login'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'old_lets'       => new sfWidgetFormFilterInput(),
      'total_payed'    => new sfWidgetFormFilterInput(),
      'total_received' => new sfWidgetFormFilterInput(),
      'deleted'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'deleted_on'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'project_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Project'), 'column' => 'id')),
      'no'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'login'          => new sfValidatorPass(array('required' => false)),
      'language'       => new sfValidatorPass(array('required' => false)),
      'session'        => new sfValidatorPass(array('required' => false)),
      'credentials'    => new sfValidatorPass(array('required' => false)),
      'user_level'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'password'       => new sfValidatorPass(array('required' => false)),
      'email'          => new sfValidatorPass(array('required' => false)),
      'display_name'   => new sfValidatorPass(array('required' => false)),
      'first_name'     => new sfValidatorPass(array('required' => false)),
      'last_name'      => new sfValidatorPass(array('required' => false)),
      'address_line'   => new sfValidatorPass(array('required' => false)),
      'zip_code'       => new sfValidatorPass(array('required' => false)),
      'city'           => new sfValidatorPass(array('required' => false)),
      'country'        => new sfValidatorPass(array('required' => false)),
      'phone_home'     => new sfValidatorPass(array('required' => false)),
      'phone_mobile'   => new sfValidatorPass(array('required' => false)),
      'phone_work'     => new sfValidatorPass(array('required' => false)),
      'birth_date'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'personal_text'  => new sfValidatorPass(array('required' => false)),
      'avatar'         => new sfValidatorPass(array('required' => false)),
      'show_email'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'show_phone'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'show_address'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'show_age'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'logins'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_login'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'old_lets'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'total_payed'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'total_received' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'deleted'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'deleted_on'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'           => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'project_id'     => 'ForeignKey',
      'no'             => 'Number',
      'login'          => 'Text',
      'language'       => 'Text',
      'session'        => 'Text',
      'credentials'    => 'Text',
      'user_level'     => 'Number',
      'password'       => 'Text',
      'email'          => 'Text',
      'display_name'   => 'Text',
      'first_name'     => 'Text',
      'last_name'      => 'Text',
      'address_line'   => 'Text',
      'zip_code'       => 'Text',
      'city'           => 'Text',
      'country'        => 'Text',
      'phone_home'     => 'Text',
      'phone_mobile'   => 'Text',
      'phone_work'     => 'Text',
      'birth_date'     => 'Date',
      'personal_text'  => 'Text',
      'avatar'         => 'Text',
      'show_email'     => 'Boolean',
      'show_phone'     => 'Boolean',
      'show_address'   => 'Boolean',
      'show_age'       => 'Boolean',
      'logins'         => 'Number',
      'last_login'     => 'Date',
      'old_lets'       => 'Number',
      'total_payed'    => 'Number',
      'total_received' => 'Number',
      'deleted'        => 'Boolean',
      'deleted_on'     => 'Date',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
      'slug'           => 'Text',
    );
  }
}
