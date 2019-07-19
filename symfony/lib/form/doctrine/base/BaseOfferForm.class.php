<?php

/**
 * Offer form base class.
 *
 * @method Offer getObject() Returns the current form's model object
 *
 * @package    letsonline
 * @subpackage form
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseOfferForm extends BaseFormDoctrine
{
  public function setup()
  {
    $categories = Doctrine_Core::getTable($this->getRelatedModelName('Category'))->getTranslatedAndOrdered();

    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      //'user_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false)),
      //'category_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => false, 'table_method'=>'getTranslatedAndOrdered')),
      'category_id' => new sfWidgetFormChoice(array('choices'=>$categories)),
      'type'        => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormInputText(),
      'experience'  => new sfWidgetFormInputText(),
      //'created_at'  => new sfWidgetFormDateTime(),
      //'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      //'user_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      //'category_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Category'))),
      'category_id' => new sfValidatorChoice(array('choices'=>array_keys($categories))),
      'type'        => new sfValidatorString(array('max_length' => 255)),
      'description' => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'experience'  => new sfValidatorString(array('max_length' => 255)),
      //'created_at'  => new sfValidatorDateTime(),
      //'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('offer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Offer';
  }

}
