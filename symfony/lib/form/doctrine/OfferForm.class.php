<?php

/**
 * Offer form.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class OfferForm extends BaseOfferForm
{
  public function configure()
  {
    $experiences  = sfConfig::get('app_offer_experiences');
    $types        = sfConfig::get('app_offer_types');

    $this->useFields(array('category_id', 'type', 'description', 'experience'));
    $this->setWidget('type', new sfWidgetFormChoice(array('choices'=>$types)));
    $this->setValidator('type', new sfValidatorChoice(array('choices'=>array_keys($types))));
    $this->setWidget('experience', new sfWidgetFormChoice(array('choices'=>$experiences)));
    $this->setValidator('experience', new sfValidatorChoice(array('choices'=>array_keys($experiences))));
  }

  public function save($con=null)
  {
    $user = sfContext::getInstance()->getUser()->getObject();
    $obj = $this->getObject();
    $obj->setProjectId((int)$user->getProjectId());
    $obj->setUserId((int)$user->getId());
    return parent::save($con);
  }
}
