<?php

/**
 * User form.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserLoginForm extends BaseUserForm
{
  public function configure()
  {
    $this->useFields(array('login', 'password'));

    $this->getValidator('login')->setMessages(array(
      'required'  => "Please fill in your user name.",
      'invalid'   => "Invalid login. Please use only letters and _ or -.",
      'notfound'  => "No such user.",
      'deleted'   => "Your account is suspended."
    ));
    $this->getValidator('password')->setMessages(array(
      'required'  => "Please fill in your password.",
      'invalid'   => "Incorrect password"
    ));
  }
}
