<?php

/**
 * User form.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserEmailMemberForm extends UserChooseForm
{
  public function setup()
  {
    parent::setup();
    $user_email = sfContext::getInstance()->getUser()->getObject()->getEmail();

    $this->setWidget('from',    new sfWidgetFormInputText(array('default'=>$user_email), array('disabled'=>'disabled')));
    $this->getWidget('from')->setAttribute('value', $user_email);

    $this->setWidget('subject', new sfWidgetFormInputText());
    $this->setWidget('text',    new sfWidgetFormTextArea(array(), array('rows' => 10,'class' => 'textarea')));

    //$this->setValidator('from', new sfValidatorChoice(array('choices'=>array($user_email))));
    //$this->setValidator('from', new sfValidatorPass());
    $this->setValidator('subject', new sfValidatorString(array('max_length'=>50, 'required'=>true)));
    $this->setValidator('text', new sfValidatorString(array('max_length'  => 1000,'required'    => true)));

    $this->getWidgetSchema()->setLabels(array(
      'user' => "To",
      'text' => "Message"
    ));

    // from first
    $this->getWidgetSchema()->setPositions(array("from","user","subject","text"));

    $this->getValidatorSchema()->addMessage('confirm', 'Your e-mail has been sent');

    $this->widgetSchema->setNameFormat('user[%s]');
  }
}
