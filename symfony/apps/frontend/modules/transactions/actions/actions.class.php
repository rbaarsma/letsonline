<?php

/**
 * transactions actions.
 *
 * @package    letsonline
 * @subpackage transactions
 * @author     Rein Baarsma <rein@solidwebcode.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class transactionsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$table 	= Doctrine::getTable('Coupon');

    // TODO: refresh totals -- note that this should be a CRON or something.
    $table->getTotalReceived($this->getUser()->getObject());
    $table->getTotalPayed($this->getUser()->getObject());
    
  	$this->received_reminders 	= $table->getReceivedReminders($this->getUser()->getObject());
  	$this->sent_reminders       = $table->getSentReminders($this->getUser()->getObject());

    $query =  $table->getPaymentsQuery($this->getUser()->getObject());
    $this->pager = $this->getPager($query);
    $this->payments = $this->pager->getResults();
  }

  /**
   * List transactions with pager
   * @param sfWebRequest $request
   */
  public function executeList(sfWebRequest $request)
  {
    $query =  Doctrine_Core::getTable('Coupon')->getPaymentsQuery($this->getUser()->getObject());

    if ($filter_user = (int)$request->getParameter('user'))
      $query->addWhere('sender_id = ? OR receiver_id = ?', array((int)$filter_user, (int)$filter_user));
    if ($filter_search = $request->getParameter('description'))
      $query->addWhere('description LIKE ?', array("%$filter_search%"));

    $this->pager = $this->getPager($query);
    $this->payments = $this->pager->getResults();

    if ($request->isXmlHttpRequest())
      return $this->renderPartial('transactions/transactions', array(
        'payments'  => $this->payments,
        'pager'   => $this->pager,
      ));

    $this->form   = new FilterCouponForm();
  }

  /**
   * Show single transaction
   * @param sfWebRequest $request
   */
  public function executeShow(sfWebRequest $request)
  {
    $this->coupon = $this->getRoute()->getObject();
    $this->forward404If(!$this->coupon->isFrom($this->getUser()->getObject()));
  }

  /**
   * Confirm received reminder
   * @param sfWebRequest $request
   */
  public function executeConfirm(sfWebRequest $request)
  {
    $coupon = $this->getRoute()->getObject();
    $this->forward404Unless($coupon->isReceivedReminder($this->getUser()->getObject()));
    $coupon->setConfirmed(true);
    $coupon->save();

    $i18n = $this->getContext()->getI18N();
    $message = $i18n->__("You have confirmed your payment to %user%", array('%user%'=>$coupon->getReceiver()));
    $this->getUser()->setFlash('confirm', $message);

    $this->redirect("transactions");
  }

  /**
   * delete sent reminder
   * @param sfWebRequest $request
   */
  public function executeDelete(sfWebRequest $request)
  {
    $coupon = $this->getRoute()->getObject();
    $this->forward404Unless($coupon->isSentReminder($this->getUser()->getObject()));

    $coupon->delete();

    $i18n = $this->getContext()->getI18N();
    $message = $i18n->__("You have deleted your reminder to %user%", array('%user%'=>$coupon->getSender()));
    $this->getUser()->setFlash('confirm', $message);

    $this->redirect("transactions");
  }

  /**
   * send payment
   * @param sfWebrequest $request
   */
  public function executePayment(sfWebrequest $request)
  {
    $this->type = "payment";
    $this->form = new CouponPaymentForm();
    $this->showForm($request, $this->form, "reminder_id");
  }

  /**
   * send reminder
   * @param sfWebrequest $request
   */
  public function executeReminder(sfWebrequest $request)
  {
    $this->type = "reminder";
    $this->form = new CouponReminderForm();
    $this->showForm($request, $this->form, "sender_id");
  }

  /**
   * actually process the form to send payment
   * @param sfWebrequest $request
   */
  public function executeCreatePayment(sfWebrequest $request)
  {
    $this->type = "payment";
    $this->form = new CouponPaymentForm();
    $this->processForm($request, $this->form);
  }

  /**
   * actually process the form to send reminder
   * @param sfWebrequest $request
   */
  public function executeCreateReminder(sfWebrequest $request)
  {
    $this->type = "reminder";
    $this->form = new CouponReminderForm();
    $this->processForm($request, $this->form);
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

  /**
   * Helper for send payment/reminder
   * @param sfWebrequest $request
   * @param sfForm $form
   */
  public function showForm(sfWebrequest $request, sfForm $form)
  {
    if ($username = $request->getParameter("user_name"))
    {
      if ($user = Doctrine_Table::getTable('User')->findOneByLoginAndProjectId($username, $this->getUser()->getObject()->getProjectId()))
        $this->redirect404();
      
      $form->setMyId($this->getUser()->getObject()->getId());
      $form->setOtherId($user->getId());
    }
    $this->setTemplate('coupon');
  }

  /**
   * Helper to process form of create payment/reminder
   * @param sfWebRequest $request
   * @param sfForm $form
   */
  public function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
      $form->save();
      $message = sprintf($form->getValidatorSchema()->getMessage('success'), $form->getTo());
      $this->getUser()->setFlash('confirm', $message);
      $this->redirect("transactions");
    }
    $this->setTemplate('coupon');  
  }
}