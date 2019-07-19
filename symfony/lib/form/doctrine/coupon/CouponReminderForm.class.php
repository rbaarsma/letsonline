<?php

/**
 * Coupon form.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CouponReminderForm extends CouponForm
{
    public function configure()
    {
      // sender
      $user_list = new sfWidgetFormDoctrineChoice(array(
          'model' => $this->getRelatedModelName('Sender'),
          'add_empty' => true,
          'table_method'=>'getOthers'
      ), array('no_translation'=>true));
      $this->setWidget('sender_id', $user_list);
      $this->user_choices = $user_list->getChoices();
      $user_validator = new sfValidatorChoice(array('choices' => array_keys($this->user_choices), 'required'=>true));
      $this->setValidator('sender_id', $user_validator);
      $this->getValidator('sender_id')->setMessages(array(
          'required'  => 'Please specify who this reminder should be sent to.'
      ));

      $this->getWidgetSchema()->setHelp('sender_id', "You are reminding <b>this person</b> to make a payment to you.");
      $this->getWidgetSchema()->setHelp('receiver_id', "<b>You</b> will receive the payment when the reminder is accepted.");

      // success message
      $this->getValidatorSchema()->addMessage("success", "Reminder sent to %s");

      parent::configure();
    }

    public function save($con=null)
    {
      $this->getObject()->setReceiverId((int)$this->getUser()->getId());
      $this->getObject()->setConfirmed(false);
      return parent::save($con);
    }
  
    public function getTo()
    {
      $user = $this->user_choices[$this->getObject()->getSenderId()];
      return $user;
    }
}
