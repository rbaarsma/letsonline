<?php

/**
 * Coupon form.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CouponPaymentForm extends CouponForm
{
  public function configure()
  {
    // receiver
    $user_list = new sfWidgetFormDoctrineChoice(array(
        'model' => $this->getRelatedModelName('Receiver'),
        'add_empty' => true,
        'table_method'=>'getOthers'
    ), array('no_translation'=>true));
    $this->setWidget('receiver_id', $user_list);
    $this->user_choices = $user_list->getChoices();
    $user_validator = new sfValidatorChoice(array('choices' => array_keys($this->user_choices), 'required'=>true));
    $this->setValidator('receiver_id', $user_validator);
    $this->getValidator('receiver_id')->setMessages(array(
        'required'  => 'Please specify who should receive this payment.'
    ));

    $this->getWidgetSchema()->setHelp('sender_id', "When making a payment you are always the sender.");
    $this->getWidgetSchema()->setHelp('receiver_id', "You can choose who you'd like to send this payment to.");

    // success message
    $this->getValidatorSchema()->addMessage("success", "Payment made to %s");

    parent::configure();
  }

  public function save($con=null)
  {
    $this->getObject()->setSenderId((int)$this->getUser()->getId());
    $this->getObject()->setConfirmed(true);
    return parent::save($con);
  }

  public function getTo()
  {
    $user = $this->user_choices[$this->getObject()->getReceiverId()];
    return $user;
  }
}
