<?php

/**
 * front actions.
 *
 * @package    letsonline
 * @subpackage front
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class frontActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeContact(sfWebRequest $request)
  {
    $form = new ContactUsForm();

    // handle post
    if ($request->isMethod(sfWebRequest::POST) || $request->isMethod(sfWebRequest::PUT))
    {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid())
      {
        // prepare values
        $from_email = (string)$form->getValue('from');
        $project_id = (int)$form->getValue('to');
        $to_email   = Doctrine_Core::getTable('Project')->find($project_id)->getEmail();
        $to_user    = Doctrine_Core::getTable('User')->findOneByProjectIdAndEmail($project_id, $to_email);
        if ($to_user === false)
          $to_user = Doctrine_Core::getTable('User')->find(1);
        $subject    = "[System] $from_email through Contact Us Form";
        $message    = (string)$form->getValue('message');

        // send message
        $result = $this->sendMessage($from_email, $to_user, $to_email, $project_id, $subject, $message);
        if ($result === 1)
        {
          $i18n = $this->getContext()->getI18N();
          $this->getUser()->setFlash('confirm', $i18n->__("Your message has been sent"));
          $this->redirect('contact_us');
        }
        else throw new Exception("Unexpected mail error (frontend/front/actions/actions.php composeAndSend): $result");
      }
    }

    $this->form = $form;
    $this->setLayout('home');
  }

  public function sendMessage($from_email, $to_user, $to_email, $project_id, $subject, $message)
  {
    Doctrine_Core::getTable('Message')->createMessage($project_id, null, $to_user, $subject, $message);

    // send email
    $mailer = $this->getMailer();

    // render email text from partial
    $email_text = $this->getPartial("user/email.txt", array(
      "message" => $message,
      "from"    => $from_email
    ));

    $result = $mailer->composeAndSend($from_email, $to_email, $subject, $email_text);
    return $result;
  }
}
