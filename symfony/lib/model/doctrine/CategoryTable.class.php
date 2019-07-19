<?php


class CategoryTable extends Doctrine_Table
{ 
  public static function getInstance()
  {
    return Doctrine_Core::getTable('Category');
  }

  public function getAll()
  {
    return $this->createQuery('c')->execute(); // where->('project_id = ?', $user->getProjectId())
  }

  public function getTranslatedAndOrdered()
  {
    $i18n = sfContext::getInstance()->getI18N();

    $categories = array();
    foreach ($this->getAll() as $category)
      $categories[$category->getId()] = $i18n->__($category);

    asort($categories);
    return $categories;
  }
}