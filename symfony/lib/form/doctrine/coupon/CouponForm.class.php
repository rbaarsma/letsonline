<?php

/**
 * Coupon form.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class AmountWidget extends sfWidgetFormInputText
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $input = parent::render($name, $value, $attributes, $errors);
    return $input ." ". sfContext::getInstance()->getUser()->getProjectCurrency();
  }
}

// make sure the dutch seperator: komma(,), is also understood
class AmountValidator extends sfValidatorNumber
{
  public function doClean($value)
  {
    // note that if the amount becomes 10.000.00 because of this replace
    // it will still be invalid due to the sfValidatorNumber doClean function
    return parent::doClean(str_replace(",",".",$value));
  }
}

class CouponForm extends BaseCouponForm
{
  public function configure()
  {
    $this->setWidget('amount', new AmountWidget(array(), array("style"=>"width: 3em;")));
    $this->setValidator('amount', new AmountValidator(array('required' => true, 'min'=>0.01)));

    // labels
    $this->getWidgetSchema()->setLabels(array(
      'sender_id'       => ('From'),
      'receiver_id'     => ('To'),
      'amount'          => ('Amount'),
      'date'            => ('Date'),
      'description'     => ('Description')
    ));

    // validation messages
    $this->getValidator('amount')->setMessages(array(
       'required'   => 'Please specify the amount.',
       'invalid'    => 'Amount should be a number.',
       'min'        => 'The amount must be above 0.'
    ));
    $this->getValidator('date')->setMessages(array(
        'required'  => 'Please specify the transaction date.',
        'max'       => 'You can not specify a date in the future.',
        'min'       => "Transaction should not be older than 1 year",
        'bad_format'=> 'This is an incorrect date.'
    ));
    $this->getValidator('description')->setMessages(array(
        'required'  => 'Please specify a description of the event.'
    ));
  }

  // make sure project_id is set in stone
  public function save($con=null)
  {
    $this->getObject()->setProjectId((int)$this->getUser()->getProjectId());
    return parent::save($con);
  }
}
