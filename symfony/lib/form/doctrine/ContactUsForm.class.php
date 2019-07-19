<?php
/* 
 * @package    letsonline
 * @subpackage front
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 */

class ContactUsForm extends BaseForm
{
  public function setup()
  {
    $choices = array(''=>'', 1 => 'System Administrator');
    $projects = Doctrine_Core::getTable('Project')->createQuery('p')->where('active = ?', true)->execute();
    foreach ($projects as $project)
      $choices[$project->getId()] = $project->getName();

    $this->setWidgets(array(
      'from'    => new sfWidgetFormInputText(),
      'to'      => new sfWidgetFormChoice(array('choices'=>$choices)),
      'message' => new sfWidgetFormTextarea(),
    ));

    unset($choices['']);
    $this->setValidators(array(
      'from'    => new sfValidatorEmail(),
      'to'      => new sfValidatorChoice(array('choices' => array_keys($choices))),
      'message' => new sfValidatorString(array('max_length' => 1000)),
    ));

    $this->getValidatorSchema()->setMessages(array(
      'from' => array('invalid' => 'This is not a valid e-mail address'),
      'message' => array('max_length'=>'The message is too long: it should be no more than 1000 characters.')
    ));

    $this->getWidgetSchema()->setLabels(array(
      'from'    => 'Your Email',
      'to'      => 'Choose the recipient',
      'message' => 'Your message'
    ));

    $this->getWidgetSchema()->setHelp('message', 'No more than 1000 characters');

    $this->getWidgetSchema()->setNameFormat('contact[%s]');

    parent::setup();
  }
}

?>
