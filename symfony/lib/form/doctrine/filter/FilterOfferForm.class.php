<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FilterOfferForm extends FilterForm
{
  public function setup()
  {
    $categories = Doctrine_Core::getTable('Category')->getTranslatedAndOrdered();
    // trick to prepend element without changing keys
    $categories = array(0=>"All Categories") + $categories;

    $types = array(
      0         => "Offers and Requests",
      'request' => 'Requests Only',
      'offer'   => 'Offers Only'
    );

    $this->setWidgets(array(
      'type'        => new sfWidgetFormChoice(array('choices'=>$types)),
      'category_id' => new sfWidgetFormChoice(array('choices'=>$categories)),
      'description' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'type'        => new sfValidatorChoice(array('choices'=>array_keys($types))),
      'category_id' => new sfValidatorChoice(array('choices'=>array_keys($categories))),
      'description' => new sfValidatorString(array('max_length' => 50, 'required' => true)),
    ));

    parent::setup();
  }

  public function configure()
  {
    $this->getWidgetSchema()->setHelps(array(
       'type'         => 'Filter by type or category:',
       'description'  => 'Search user or description:'
    ));

    parent::configure();
  }

  public function render($attributes=array())
  {
    $this->renderFilterJs();
    $output = "<tr><td>";
    $output .=  $this->getWidgetSchema()->getHelp('type');
    $output .= "</td><td>";
    $output .=  $this['type'];
    $output .=  $this['category_id'];
    $output .=  "</td></tr><tr><td>";
    $output .=  $this->getWidgetSchema()->getHelp('description');
    $output .= "</td><td>";
    $output .= $this['description'];
    $output .= "</td></tr>";
    return $output;
  }

}