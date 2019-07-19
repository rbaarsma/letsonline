<?php

/**
 * members actions.
 *
 * @package    letsonline
 * @subpackage members
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class admin_membersActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $query =  Doctrine_Core::getTable('User')->getFromProjectQuery($this->getUser()->getObject());
    $this->form = new FilterAdminMemberForm();

    // if unset it will become 0 (thus all active users)
    $deleted = $request->getParameter('deleted');
    if ($deleted != 'all')
      $query->addWhere('deleted = ?', (int)$deleted);

    if ($var = $request->getParameter('user_or_email'))
    {
      $query->addWhere("display_name LIKE ? OR email LIKE ? OR first_name LIKE ? OR last_name LIKE ?",
        array("%$var%", "%$var%", "%$var%", "%$var%"));
    }

    $this->pager = $this->getPager($query);

    if ($request->isXmlHttpRequest())
      return $this->renderPartial('admin_members/list', array(
        'users' => $this->pager->getResults(),
        'pager' => $this->pager,
      ));
  }

  /*
  public function executeResetPassword(sfWebRequest $request)
  {
    $this->form = new UserChooseForm();
    if ($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT))
      $this->doResetPassword($request, $form);
  }

  public function doResetPassword(sfWebRequest $request, UserChooseForm $form)
  {
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
      $id = (int)$form->getValue('user');
      $user = Doctrine_Core::getTable('User')->find($id);
      $user->setPassword( Utils::getInstance()->generatePassword() );
      $user->save();

      $i18n = $this->getContext()->getI18N();
      
      $mailer   = $this->getMailer();
      $to       = $user->getEmail();
      $this->getUser()->setCulture($user->getLanguage());
      $subject  = $i18n->__("Your LETSOnline Password has been reset.");

      // render email text from partial
      $email_text = $this->getPartial("admin_members/email_reset_password.txt", array(
        "password" => $user->getPassword(),
        "from"    => $this->getUser()->getObject()->getEmail()
      ));

      $mailer->composeAndSend($from, $to, $subject, $email_text);
      $this->getUser()->setCulture($this->getUser()->getObject()->getLanguage());

      $this->getUser()->setFlash('confirm', $i18n->__("Password of %user% has been reset. An e-mail with the new password has been sent to %email%",
        array("%user%"=>$user->getDisplayName(), "%email%"=>$user->getEmail()))
      );
      $this->redirect('admin_members_reset_password');
    }
  }
   *
   */

  public function executeFind(sfWebRequest $request)
  {
    $this->form = new UserChooseForm();
  }

  public function executeHack(sfWebRequest $request)
  {
    $form = $this->form = new UserChooseForm();
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
      $id = (int)$form->getValue('user');
      $user = Doctrine_Core::getTable('User')->find($id);
      $this->getUser()->setAuth($user);
      $i18n = $this->getContext()->getI18N();
      $this->getUser()->setFlash('confirm', $i18n->__("You have changed your identity to %user%", array("%user%"=>$user->getDisplayName())));
      $this->redirect('transactions');
    }
    $this->setTemplate('find');
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new UserForm();
  }

  public function executeActivate(sfWebRequest $request)
  {
    $user = $this->getRoute()->getObject();
    $this->forward404Unless($user);
    $user->activate();
    $i18n = $this->getContext()->getI18N();
    $this->getUser()->setFlash('confirm', $i18n->__("Reactivated user: %user%", array("%user%"=>$user->getDisplayName())));
    $this->redirect('admin_members');
  }

  public function executeDeactivate(sfWebRequest $request)
  {
    $user = $this->getRoute()->getObject();
    $this->forward404Unless($user);
    $user->deactivate();
    $i18n = $this->getContext()->getI18N();
    $this->getUser()->setFlash('confirm', $i18n->__("Deactivated user: %user%", array("%user%"=>$user->getDisplayName())));
    //$this->redirect('admin_members');
    $this->executeIndex($request);
    $this->setTemplate('index');
  }

  public function executeAddresses(sfWebRequest $request)
  {
    $this->users = Doctrine_Core::getTable('User')->getActive($this->getUser()->getObject());
  }

  public function executeTransactions(sfWebRequest $request)
  {
    $this->users = Doctrine_Core::getTable('User')->getTransactionTotals($this->getUser()->getObject());
  }

  public function executeEmail(sfWebRequest $request)
  {
    $this->users = Doctrine_Core::getTable('User')->getActive($this->getUser()->getObject());
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $form = new UserForm();
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

    if ($form->isValid())
    {
      $user = $form->save();
      $i18n = $this->getContext()->getI18N();

	$mailer   = $this->getMailer();
	$from = $this->getUser()->getObject()->getEmail();
	$to = $user->getEmail();
	
	if (empty($_POST['user']['password']))
		$_POST['user']['password'] = substr(md5(uniqid()), 0, 8);
	
	$project_id = $this->getUser()->getObject()->getProjectId();
	$ruilkring = Doctrine_Core::getTable('Project')->find($project_id)->getName();
	$subject  = $i18n->__("Welkom bij $ruilkring");

	// render email text from partial
	$email_text = $this->getPartial("admin_members/new_user.txt", array(
		"name"  		=> $user->getFirstName(),
		"username" 		=> $user->getLogin(),
		"password" 		=> $_POST['user']['password'],
		"ruilkring" 	=> $ruilkring,
		"sendername" 	=> $this->getUser()->getObject()->getFullName(),
	));

	$mailer->composeAndSend($from, $to, $subject, $email_text);

      $this->getUser()->setFlash('confirm', $i18n->__("Data saved and email sent to new user"));
      $this->redirect('admin_members');
    }

	$this->form = $form;
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($user = Doctrine_Core::getTable('User')->find(array($request->getParameter('id'))), sprintf('Object user does not exist (%s).', $request->getParameter('id')));
    $this->form = new UserForm($user);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($user = Doctrine_Core::getTable('User')->find(array($request->getParameter('id'))), sprintf('Object user does not exist (%s).', $request->getParameter('id')));
    $this->form = new UserForm($user);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($user = Doctrine_Core::getTable('User')->find(array($request->getParameter('id'))), sprintf('Object user does not exist (%s).', $request->getParameter('id')));
    $user->delete();

    $this->redirect('admin_members/index');
  }

  /**
   * Returns an initialized myDoctrinePager
   * @param Doctrine_Query $query
   * @return myDoctrinePager
   */
  public function getPager(Doctrine_Query $query)
  {
    $pager = new myDoctrinePager($query);
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();
    return $pager;
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

    if ($form->isValid())
    {
      $user = $form->save();
      $i18n = $this->getContext()->getI18N();
      $this->getUser()->setFlash('confirm', $i18n->__("Data saved"));

      $this->redirect('admin_members');
    }
  }
}
