<?php


class CouponTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Coupon');
    }

    public function getProjectQuery(User $user)
    {
      return $this->createQuery('c')->where('c.project_id = ?', $user->getProjectId())->orderBy('c.updated_at DESC');
    }
    
    public function getReceivedReminders(User $user)
    {
    	return $this->getProjectQuery($user)
        ->innerJoin('c.Sender u')
    	  ->andWhere('c.sender_id = ?', $user->getId())
    	  ->andWhere('c.confirmed = ?', false)
        ->execute();
    }
    
    public function getPaymentsQuery(User $user)
    {
      $user_id = $user->getId();
    	return $this->getProjectQuery($user)
        ->innerJoin('c.Sender s')
        ->innerJoin('c.Receiver r')
        ->andWhere('c.sender_id = ? OR c.receiver_id = ?', array($user_id, $user_id))
    	  ->andWhere('c.confirmed = ?', true);
    }

    public function getPayments(User $user)
    {
      return $this->getPaymentsQuery($user)->execute();
    }
    
    public function getSentReminders(User $user)
    {
    	return $this->getProjectQuery($user)
        ->innerJoin('c.Receiver u')
    	  ->andWhere('receiver_id = ?', $user->getId())
    	  ->andWhere('confirmed = ?', false)
        ->execute();
    }

    public function getTotal(User $user, $field)
    {
    	$res = $this->getProjectQuery($user)
              ->select('SUM(amount) AS total')
              ->addWhere("`$field` = ?", $user->getId())
              ->addWhere("confirmed = ?", true)
              ->execute()
              ->getFirst();
      return $res->getTotal();
    }

    public function getTotalReceived(User $user)
    {
      return $this->getTotal($user, "receiver_id");
    }

    public function getTotalPayed(User $user)
    {
      return $this->getTotal($user, "sender_id");
    }
}