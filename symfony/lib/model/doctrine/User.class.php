<?php

/**
 * User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Lets Online
 * @subpackage model
 * @author     Rein Baarsma
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class User extends BaseUser
{
  /*
   * methods
   */
  
  public function activate()
  {
    $this->setDeleted(false);
    $this->save();
  }

  public function deactivate()
  {
    $this->setDeleted(true);
    $this->setDeletedOn(date("Y-m-d H:i:s"));
    $res = $this->save();
  }

  public function isActive()
  {
    return !$this->getDeleted();
  }

  // TODO: if this system ever gets busy and queries matter,
  //       then we'll need to change this to prevent the two extra queries
  public function updateTotals(Coupon $coupon)
  {
    // we could've added the below line.. but it's better to always get the sum
    // $this->_set['total_received'] += $coupon->isReceiver($this) ? $coupon->getAmount() : -$coupon->getAmount();

    $this->setTotalPayed();
    $this->setTotalReceived();
    $this->save();
  }

  /*
   * override Setters
   */

  public function setPassword($pass)
  {
    // never set empty password
    if (!$pass)
      return;

    $this->_set('password', $this->getHashedPassword($pass));
  }

  public function setZipCode($zipcode)
  {
    $this->_set('zip_code', str_replace(" ","",$zipcode));
  }

  public function setPhoneHome($phone)
  {
    $this->_set('phone_home', str_replace(" ","-",$phone));
  }
  public function setPhoneMobile($phone)
  {
    $this->_set('phone_mobile', str_replace(" ","-",$phone));
  }
  public function setPhoneWork($phone)
  {
    $this->_set('phone_work', str_replace(" ","-",$phone));
  }

  public function setTotalReceived()
  {
    $this->_set('total_received', CouponTable::getInstance()->getTotalReceived($this));
  }

  public function setTotalPayed()
  {
    $this->_set('total_payed', CouponTable::getInstance()->getTotalPayed($this));
  }

  /*
   * (override) Getters
   */

  public function getFormattedBirthDate()
  {
    return Utils::getInstance()->formatDate($this->getBirthDate());
  }
  public function getFormattedLastLogin()
  {
    return Utils::getInstance()->formatDate($this->getLastLogin());
  }
  public function getFormattedCreatedAt()
  {
    return Utils::getInstance()->formatDate($this->getCreatedAt());
  }

  public function getHashedPassword($pass)
  {
    $hash_pass = md5(sfConfig::get('app_password_secret').$pass);
    return $hash_pass;
  }

  public function getPhone()
  {
    if ($this->getPhoneHome())
      return $this->getPhoneHome();
    elseif ($this->getPhoneMobile())
      return $this->getPhoneMobile();
    else
      return $this->getPhoneWork();
  }

  public function getProfileUrl()
  {
    return url_for('profile', array('slug'=>$this->getSlug()));
  }

  public function getProfileLink()
  {
    //return link_to($this->getDisplayName(), 'profile', array('slug'=>$this->getSlug()));
    return Utils::getInstance()->showInfo($this->getDisplayName(), 'profile', array('slug'=>$this->getSlug()), false);
  }

  public function getAvatarLink()
  {
    return $this->getAvatar() ? "/uploads/avatars/".$this->getAvatar() : "/images/photo_missing.jpg";
  }

  public function getAvatarImage()
  {
    return "<img src='".$this->getAvatarLink()."' class='avatar' alt=\"".$this->getDisplayName()."\" title=\"".$this->getDisplayName()."\" />";
  }

  public function getAge()
  {
    $now = new DateTime();
    $birth_date = new DateTime($this->getBirthDate());
    return $now->diff($birth_date)->format("%y");
  }

  public function getFullName()
  {
    return $this->getFirstName()." ".$this->getLastName();
  }

  public function getBalance()
  {
    return $this->getTotalReceived() - $this->getTotalPayed() + $this->getOldLets();
  }

  public function getUserWithProject()
  {
    return $this->__toString() . " (#".$this->getProjectId().")";
  }

  public function __toString()
  {
      return $this->getDisplayName();
  }
}