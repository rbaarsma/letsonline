<?php

/**
 * Offer
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Lets Online
 * @subpackage model
 * @author     Rein Baarsma
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Offer extends BaseOffer
{
  /**
   *
   * @return string $untranslated_english_experience
   */
  public function getExperience()
  {
    $experiences = sfConfig::get('app_offer_experiences');
    return isset($experiences[$this->_get("experience")])
            ? $experiences[$this->_get("experience")] : $experiences['n/a'];
  }

  /**
   *
   * @return string $untranslated_english_type
   */
  public function getType()
  {
    $types = sfConfig::get('app_offer_types');
    return isset($types[$this->_get("type")]) ? $types[$this->_get("type")] : '';
  }

}