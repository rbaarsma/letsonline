<?php


class OfferTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Offer');
    }

    public function getOffersQuery(User $user) // can be both User and myUser
    {
        return $this->createQuery('a')
            ->addFrom('a.User u')
            ->addFrom('a.Category c')
            ->where('u.deleted = ?', false)
            ->addWhere('project_id = ?', $user->getProjectId())
            ->orderby('updated_at DESC, category_id');
    }

    public function getUserOffersQuery(User $user)
    {
      return $this->getOffersQuery($user)->addWhere('user_id = ?', $user->getId());
    }

    public function getUserOffers(User $user)
    {
      return $this->getUserOffersQuery($user)->execute();
    }
}