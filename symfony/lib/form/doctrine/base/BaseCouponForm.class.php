<?php

/**
 * Coupon form base class.
 *
 * @method Coupon getObject() Returns the current form's model object
 *
 * @package    letsonline
 * @subpackage form
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCouponForm extends BaseFormDoctrine
{
  protected $user = null;

  public function setup()
  {
    $this->setUser(sfContext::getInstance()->getUser()->getObject());

    $options = array('choices'=>array($this->user->getId()=>$this->user->__toString()));
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'receiver_id' => new sfWidgetFormChoice($options, array("disabled"=>"disabled", array('no_translation'=>true))),
      'sender_id'   => new sfWidgetFormChoice($options, array("disabled"=>"disabled", array('no_translation'=>true))),
      'amount'      => new sfWidgetFormInputText(),
      'date'        => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'amount'      => new sfValidatorInteger(),
      'date'        => new sfValidatorDate(array('required' => true, 'min'=>strtotime("-1 year"), 'max'=>time())),
      'description' => new sfValidatorString(array('max_length' => 255, 'required' => true)),
    ));
    
    $this->widgetSchema->setNameFormat('coupon[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  /**
   *
   * @return User $user
   */
  public function getUser()
  {
    return $this->user;
  }

  public function setUser(User $user)
  {
    $this->user = $user;
  }

  /**
   *
   * @return string
   */
  public function getModelName()
  {
    return 'Coupon';
  }

}
