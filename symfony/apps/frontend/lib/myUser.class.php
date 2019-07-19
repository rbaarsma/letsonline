<?php

/**
 * myUser
 *
 * @package    Lets Online
 * @subpackage model
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 */
class myUser extends sfBasicSecurityUser
{
  protected $user;

  /**
   *
   * @param UserLoginForm $form
   * @return boolean $success
   */
  public function login(UserLoginForm $form, sfWebRequest $request)
  {
    $login    = $form->getValue('login');
    $password = $form->getValue('password');

    // set first validatino error as userflash
    if (!$form->isValid())
    {
      $error = current($form->getErrorSchema()->getErrors());
      $this->setFlash('error', $error );
      return false;
    }

    // data valid, check with db
    $user_obj = Doctrine_Core::getTable('User')->findOneByLogin($login);
    
    if (empty($user_obj))
    {
      $this->setFlash('error', $form->getValidator('login')->getMessage('notfound'));
      return false;
    }

    if ($user_obj->getDeleted())
    {
      $this->setFlash('error', $form->getValidator('login')->getMessage('deleted'));
      return false;
    }

    $secret_password = md5(sfConfig::get('app_password_secret').$password);

    // still need to check password
    $real_pass = $user_obj->getPassword();
    if ($real_pass != $secret_password)
    {
      // some backwards compatability
      if (strlen($real_pass) <= 16)
      {
        // user has PASSWORD() hashed password instead of new md5 method
        $query = Doctrine_Query::create()
                ->select("OLD_PASSWORD('".mysql_escape_string($password)."') AS 'password'")
                ->from('User u')
                ->limit(1);
        echo $old_pass = current(current($query->fetchArray()));
        echo "<br/>$real_pass";
        if ($old_pass == $real_pass)
        {
          // correct authentication.. update user password to prevent this annoyance
          $user_obj->setPassword($password);
          $user_obj->save();
        }
        else
        {
          $this->setFlash('error', $form->getValidator('password')->getMessage('invalid'));
          return;
        }
      }
      else
      {
        $this->setFlash('error', $form->getValidator('password')->getMessage('invalid'));
        return;
      }
    }
    
    $this->setAuth($user_obj);

    $this->clearCredentials();
    $this->addCredentials($user_obj->getCredentials());

    $this->loadProjectData($user_obj);

    // set culture
    $language = $user_obj->getLanguage();
    if (empty($language))
      $language = $request->getPreferredCulture(sfConfig::get('accept-languages'));
    $this->setCulture($language);

    $user_obj->setLastLogin(date("Y-m-d H:i:s"));
    $user_obj->setLogins($user_obj->getLogins()+1);
    $user_obj->save();

    return true;
  }

  public function setAuth(User $user_obj)
  {
    $this->setAuthenticated(true);

    $this->setAttribute("id", $user_obj->getId());
  }

  /**
   * Logout
   */
  public function logout()
  {
    $this->setAuthenticated(false);
  }

  /**
   * get User object
   * @return User $myuser
   */
  public function getObject()
  {
    if (is_null($this->user))
      $this->loadObject();

    return $this->user;
  }

  /**
   * set $this->user and do error handling
   */
  public function loadObject()
  {
    if ($this->isAuthenticated())
    {
      $id = (int)$this->getAttribute('id');
      if ($id)
        $this->user = Doctrine_Core::getTable('User')->find($id);

      if (!$this->user)
      {
        $this->logout();
        throw new Exception("Invalid User: $id");
      }
    }
  }

  // load project data into session on login.. we don't need to retrieve it every call
  public function loadProjectData(User $user)
  {
    $project = Doctrine_Core::getTable('Project')->find($user->getProjectId());
    $this->setAttribute('project_name', $project->getName());
    $this->setAttribute('project_currency_name', $project->getCurrencyName());
  }

  public function getProjectCurrency()
  {
    return $this->getAttribute('project_currency_name');
  }

  public function getProjectId()
  {
    return $this->getObject()->getProjectId();
  }

  public function getProjectName()
  {
    return $this->getAttribute('project_name');
  }

  // quick function
  public function getId()
  {
    if (!$this->isAuthenticated() || !$this->user)
      throw new Exception("Not logged in (properly)");
    return $this->user->getId();
  }
}