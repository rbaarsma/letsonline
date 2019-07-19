<?php

class myTestFunctional extends sfTestFunctional
{
  public function __construct(sfBrowser $browser)
  {
    parent::__construct($browser);
  }

  public function login()
  {
    $this->get('/user/login')->
      click('Login', array('user' => array(
        'login'     => 'rbaarsma',
        'password'  => 'test'
      )), array('_with_csrf' => true, "position"=>2))->
      with('response')->isRedirected()->
      followRedirect();
    
    return $this;
  }
}