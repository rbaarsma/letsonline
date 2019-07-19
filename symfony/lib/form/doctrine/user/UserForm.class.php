<?php

/**
 * User form.
 *
 * @package    letsonline
 * @subpackage form
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserForm extends BaseUserForm
{
  public function configure()
  {
    $this->useFields(array(
      'display_name',
      'login',
      'email',
      'password',
      'first_name',
      'last_name',
      'address_line',
      'zip_code',
      'city',
      'country',
      'phone_home',
      'phone_work',
      'phone_mobile',
      'birth_date',
      'credentials'
    ));

    //if (!$this->getObject()->isNew())
    //  unset($this['password']);

    $this->getValidator('password')->setOption('required', false);

    $this->getValidatorSchema()->setPostValidator(new sfValidatorAnd(array(
      new sfValidatorDoctrineUnique(array('model'=>'User', 'column'=>'login')),
      new sfValidatorDoctrineUnique(array('model'=>'User', 'column'=>array('display_name','project_id'))),
    )));

    $this->getWidgetSchema()->setLabel('login', 'Login (username)');
    $this->getWidgetSchema()->setHelps(array(
      'credentials' => "Admin users need credentials for different parts of the admin section. Check here where this specific admin user is allowed. If this is not an admin you can leave it empty.",
      'password' => $this->getObject()->isNew() ? "Password has been automatically generated." : "Leave password empty to not change it.",
      'display_name' => "How other users recognize this user in the system. (max 30 characters, needs to be unique)",
      'login' => 'The username typed when logging in. Always lowercase, no spaces and needs to be unique.'
    ));

    $this->setJavaScripts(array("/js/generate_password.js"));
  }

  public function save($conn = null)
  {
    // editing users will also be for the same projectId
    $project_id = sfContext::getInstance()->getUser()->getObject()->getProjectId();
    $this->getObject()->setProjectId($project_id);

    $q = Doctrine_Core::getTable('User')->createQuery('u')->select('u.no')->where('project_id = ?', $project_id)->orderBy('no DESC')->limit(1);
    $last_no = $q->fetchOne()->getNo();
    $this->getObject()->setNo($last_no+1);

    return parent::save($conn);
  }
}
