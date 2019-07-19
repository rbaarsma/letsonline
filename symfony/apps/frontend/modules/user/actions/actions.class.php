<?php

/**
 * user actions.
 *
 * @package    letsonline
 * @subpackage user
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->user = Doctrine_Core::getTable('User')->find($this->getUser()->getObject()->getId());
    $this->offers = Doctrine_Core::getTable('Offer')->getUserOffers($this->getUser()->getObject());
  }

  public function executeAlbum(sfWebRequest $request)
  {
    $this->users = Doctrine_Core::getTable('User')->getActive($this->getUser()->getObject());
  }

  public function executeLogin(sfWebRequest $request)
  {
    if ($this->getUser()->isAuthenticated())
      $this->redirect('transactions');

    $this->form = new UserLoginForm();

    $this->setLayout("home");
  }
 
  public function executeList(sfWebrequest $request)
  {
    $this->users = Doctrine_Core::getTable('User')->getActive($this->getUser()->getObject());
  }

  public function executeProfile(sfWebRequest $request)
  {
    $this->user = $this->getRoute()->getObject();
    $this->forward404If($this->user->getProjectId() != $this->getUser()->getObject()->getProjectId());
    $this->offers = Doctrine_Core::getTable('Offer')->getUserOffers($this->user);
  }

  public function executeEmail(sfWebRequest $request)
  {
    $this->form = new UserEmailMemberForm();

    if ($request->getParameter('slug'))
    {
      $user = $this->getRoute()->getObject();
      $this->form->setDefault('user', $user->getId());
    }
    if ($offer_id = (int)$request->getParameter('offer'))
    {
      $offer = Doctrine_Core::getTable('Offer')->find($offer_id);
      $i18n = $this->getContext()->getI18N();
      $this->form->setDefault('subject', sprintf("%s: %s",
        $i18n->__($offer->getType()),
        $offer->getDescription()
      ));
    }
    if ($coupon_id = (int)$request->getParameter('coupon'))
    {
      $coupon = Doctrine_Core::getTable('Coupon')->find($coupon_id);
      if ($coupon->isConfirmed() === false)
      {
        $i18n = $this->getContext()->getI18N();
        $this->form->setDefault('subject', sprintf("%s: %s",
          $i18n->__("Reminder"),
          $coupon->getDescription()
        ));
      }
    }
  }

  public function executeSendEmail(sfWebRequest $request)
  {
    $form = $this->form = new UserEmailMemberForm();

    $form->bind($request->getParameter($form->getName()));

    if ($form->isValid())
    {
      $this->sendMessage($form->getValue('user'), $form->getValue('subject'), $form->getValue('text'));
    }
    $this->setTemplate('email');
  }

  public function sendMessage($id, $subject, $message)
  {
    // create message in database
    Doctrine_Core::getTable('Message')->createMessage($this->getUser()->getProjectId(), $this->getUser()->getId(), $id, $subject, $message);

    // send email
    $mailer = $this->getMailer();
    $from   = $this->getUser()->getObject()->getEmail();
    $to     = Doctrine_Core::getTable('User')->find($id)->getEmail();

    // render email text from partial
    $email_text = $this->getPartial("user/email.txt", array(
      "message" => $message,
      "from"    => $from
    ));

    $mailer->composeAndSend($from, $to, $subject, $email_text);

    $this->getUser()->setFlash('confirm', $this->form->getValidatorSchema()->getMessage('confirm'));
    
    $this->redirect('user/email');
  }
 
  public function executeDoLogin(sfWebRequest $request)
  {
    $this->form = new UserLoginForm();
    $this->processLoginForm($request, $this->form);
  }

  public function executeUploadAvatar(sfWebRequest $request)
  {
    // list of valid extensions, ex. array("jpeg", "xml", "bmp")
    $allowedExtensions = array("jpg","jpeg","png","gif");
    // max file size in bytes
    $sizeLimit = 2 * 1024 * 1024;

    $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
	
    $dir = sfConfig::get('sf_web_dir').'/uploads/avatars/';
    if (substr(dirname(__FILE__), 0, 1) == "/")
      $dir = $dir;
    else
      $dir = str_replace ('/', "\\", $dir);

    $user_obj = $this->getUser()->getObject();
    $result = $uploader->handleUpload($dir, $user_obj->getId(), true);

    if (isset($result['success']))
    {
      // save in db
      $user_obj->setAvatar($user_obj->getId().".jpg?".time());
      $user_obj->save();
    }

    // to pass data through iframe you will need to encode all html tags
    $this->result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
  }

  public function processLoginForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()));

    if ( $this->getUser()->login($form, $request) )
        $this->redirect('homepage');

    // errors are set through userFlash
    $this->redirect('user_login');
  }
  
  public function executeLogout(sfWebRequest $request)
  {
    $this->getUser()->logout();
    $this->redirect("user/login");
  }

  public function getFormType(sfWebRequest $request)
  {
    $form_type = $request->getParameter('form_type');
    return $form_type ? $form_type : "data";
  }

  public function getForm(sfWebRequest $request, User $user)
  {
    switch ($this->getFormType($request))
    {
      case "data":
        return new UserDataForm($user);
      case "email":
        return new UserEmailForm($user);
      case "password":
        return new UserPasswordForm($user);
      case "profile":
        return new UserProfileForm($user);
      default:
        $this->forward404();
    }
  }

  public function executeAjaxEdit(sfWebRequest $request)
  {
    $id = $this->getUser()->getObject()->getId();
    $user = Doctrine_Core::getTable('User')->find(array($id));
    $this->form = $this->getForm($request, $user);

    $this->setTemplate("ajax".ucfirst($this->getFormType($request))."Form");
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));

    $id = $this->getUser()->getObject()->getId();
    $user = Doctrine_Core::getTable('User')->find(array($id));
    
    $this->form = $this->getForm($request, $user);
    $this->processForm($request, $this->form);

    $this->setTemplate("ajax".ucfirst($this->getFormType($request))."Form");
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $user = $form->save();
      $this->getUser()->setFlash('confirm', $form->getValidatorSchema()->getMessage('confirm'));
      if ($form instanceof UserDataForm)
        $this->getUser()->setCulture( $form->getObject()->getLanguage() );

      $this->redirect("user_ajax_edit", array("form_type"=>$this->getFormType($request)));
    }
  }
}