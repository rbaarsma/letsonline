<?php


class UserTable extends Doctrine_Table
{
    
  public static function getInstance()
  {
    return Doctrine_Core::getTable('User');
  }

  public function getOthers(User $user=null)
  {
    if ($user === null)
      $user = sfContext::getInstance()->getUser()->getObject();
    return $this->getActiveQuery($user)->addWhere('id != ?', $user->getId())->execute();
  }

  public function getActiveNotMe(User $user)
  {
      return $this->getActiveNotMeQuery($user)->execute();
  }

  public function getActiveNotMeQuery(User $user)
  {
      return $this->getActiveQuery($user)->andWhere('u.id != ?', $user->getId());
  }

  public function getActive(User $user)
  {
    return $this->getActiveQuery($user)->execute();
  }

  public function getFromProject(User $user)
  {
    return $this->getFromProjectQuery($user)->execute();
  }

  public function getActiveQuery(User $user)
  {
    return $this->getFromProjectQuery($user)->addWhere('deleted = ?', false);
  }

  public function getFromProjectQuery(User $user)
  {
    return $this->createQuery('u')->where('project_id = ?', $user->getProjectId())->orderBy('display_name ASC');
  }

  // admin function
  public function getTransactionTotals(User $user)
  {
    return $this->createQuery('u')
          ->select('u.id, display_name')
          ->addSelect('(SELECT SUM(amount) FROM Coupon c1 WHERE receiver_id=u.id) AS received')
          ->addSelect('(SELECT SUM(amount) FROM Coupon c2 WHERE sender_id=u.id) AS payed')
          ->where("deleted = ?", false)
          ->addWhere("project_id = ?", $user->getProjectId())
          ->execute();
  }
}
