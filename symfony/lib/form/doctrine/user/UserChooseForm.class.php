<?php

/**
 * User form.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserChooseForm extends BaseForm
{
  public function setup()
  {
    $user = sfContext::getInstance()->getUser();
    if ($user->hasCredential('super_admin'))
    {
      $query = Doctrine_Core::getTable('User')->createQuery('u');
      $method = "getUserWithProject";
    }
    else
    {
      $query = Doctrine_Core::getTable('User')->
                createQuery('u')->
                where('project_id = ?', $user->getObject()->getProjectId())->
                andWhere('u.id != ?', $user->getId());
      $method = "__toString";
    }

    $this->setWidgets(array(
      'user' => new sfWidgetFormDoctrineChoice(array(
        'model'         => 'User',
        'order_by'       => array('display_name', 'asc'),
        'multiple'      => false,
        'add_empty'     => true,
        'method'        => $method,
        'query'         => $query
      ), array('no_translation'=>true)),
    ));

    $this->setDefault('user', '');

    $this->setValidators(array(
      'user' => new sfValidatorDoctrineChoice(array(
        'multiple'      => false,
        'model'         => 'User',
        'required'      => true,
        'query'         => $query
      )),
    ));

    $this->widgetSchema->setNameFormat('user[%s]');
  }
}
