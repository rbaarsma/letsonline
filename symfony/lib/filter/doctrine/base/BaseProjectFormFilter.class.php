<?php

/**
 * Project filter form base class.
 *
 * @package    letsonline
 * @subpackage filter
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProjectFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'email'         => new sfWidgetFormFilterInput(),
      'currency_name' => new sfWidgetFormFilterInput(),
      'city'          => new sfWidgetFormFilterInput(),
      'country'       => new sfWidgetFormFilterInput(),
      'language'      => new sfWidgetFormFilterInput(),
      'slug'          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'          => new sfValidatorPass(array('required' => false)),
      'email'         => new sfValidatorPass(array('required' => false)),
      'currency_name' => new sfValidatorPass(array('required' => false)),
      'city'          => new sfValidatorPass(array('required' => false)),
      'country'       => new sfValidatorPass(array('required' => false)),
      'language'      => new sfValidatorPass(array('required' => false)),
      'slug'          => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('project_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Project';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'name'          => 'Text',
      'email'         => 'Text',
      'currency_name' => 'Text',
      'city'          => 'Text',
      'country'       => 'Text',
      'language'      => 'Text',
      'slug'          => 'Text',
    );
  }
}
