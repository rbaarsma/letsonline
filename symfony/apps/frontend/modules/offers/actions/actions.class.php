<?php

/**
 * offers actions.
 *
 * @package    letsonline
 * @subpackage offers
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class offersActions extends sfActions
{
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
   *
   * @param sfWebRequest $request
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->loadOffers($request);
    $this->categories = Doctrine_Core::getTable('Category')->getTranslatedAndOrdered();
  }

  public function executePersonal(sfWebRequest $request)
  {
    $this->offers = Doctrine_Core::getTable('Offer')->getUserOffers($this->getUser()->getObject());
    $this->form = new OfferForm();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->offer = $this->getRoute()->getObject();
  }

  public function executeList(sfWebRequest $request)
  {
    $this->loadOffers($request);
    return $this->renderPartial('offers/list', array(
        'offers'  => $this->offers,
        'pager'   => $this->pager,
    ));
  }

  public function executeUserOffers(sfWebRequest $request)
  {
    $slug = $request->getParameter('user');
    $this->user = Doctrine_Core::getTable('User')->findOneBySlug($slug);
    $this->forward404If(!$this->user);
    $this->offers = Doctrine_Core::getTable('Offer')->getUserOffers($this->user);
  }

  public function loadOffers(sfWebRequest $request)
  {
    $query = Doctrine_Core::getTable('Offer')->getOffersQuery($this->getUser()->getObject());

    if ($filter_type = $request->getParameter('type'))
      if (in_array($filter_type, array('offer','request')))
        $query->addWhere('type = ?', $filter_type);
    if ($filter_cat = (int)$request->getParameter('category_id'))
      $query->addWhere('category_id = ?', (int)$filter_cat);
    if ($filter_search = $request->getParameter('description'))
      $query->addWhere('u.display_name LIKE ? OR description LIKE ?', array("%$filter_search%","%$filter_search%"));

    $this->pager  = $this->getPager($query);
    $this->form   = new FilterOfferForm();
    $this->offers = $this->pager->getResults();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new OfferForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new OfferForm();
    $this->processForm($request, $this->form);

    $this->offers = Doctrine_Core::getTable('Offer')->getUserOffers($this->getUser()->getObject());
    $this->setTemplate('personal');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($offer = Doctrine_Core::getTable('Offer')->find(array($request->getParameter('id'))), sprintf('Object offer does not exist (%s).', $request->getParameter('id')));
    $this->form = new OfferForm($offer);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($offer = Doctrine_Core::getTable('Offer')->find(array($request->getParameter('id'))), sprintf('Object offer does not exist (%s).', $request->getParameter('id')));
    $this->form = new OfferForm($offer);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $offer = $this->getRoute()->getObject();
    $this->forward404Unless($offer->getUserId() == $this->getUser()->getObject()->getId());
    $offer->delete();
    $i18n = $this->getContext()->getI18N();
    $this->getUser()->setFlash('confirm', $i18n->__("You have deleted your %type%: %description%", array(
      "%type%"=>strtolower($offer->getType()),
      "%description%"=>$offer->getDescription()
    )));
    $this->redirect('personal_offers');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $text = $form->getObject()->isNew() ? "Added" : "Saved";

      $offer = $form->save();
      $i18n = $this->getContext()->getI18N();
      
      $this->getUser()->setFlash('confirm', $i18n->__("$text %type%: %description%", array(
        "%type%"=>strtolower($form->getObject()->getType()),
        "%description%"=>$form->getObject()->getDescription()
      )));

      $this->redirect('personal_offers', array("id"=>$form->getObject()->getId()));
    }
  }
}
