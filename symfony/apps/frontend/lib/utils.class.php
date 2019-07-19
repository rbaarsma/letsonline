<?php

class Utils
{
  static $instance;

  const ABC = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";


  final private function __construct() {}

  /**
   *
   * @return Utils $instance
   */
  static public function getInstance()
  {
    if (self::$instance instanceof self)
      return self::$instance;
    else
      return self::$instance = new self();
  }

  public function icon_to($icon, $title, $route, array $options=array(), array $attributes=array())
  {
    if (!is_callable('link_to'))
      sfContext::getInstance()->getConfiguration()->loadHelpers('Url');
    
    $icon_file = "/images/icons/$icon";
    $icon = sprintf("<img src='%s' alt='%s' title='%s' />", $icon_file, $title, $title);
    return link_to($icon, $route, $options, $attributes);
  }

  public function formatDate($date, $with_time=false)
  {
    // return strftime("%c", strtotime($date));

    if (substr($date, 0, 10) == "0000-00-00")
      return "";
    
    $ts = strtotime($date);

    $user = sfContext::getInstance()->getUser();
    switch ($user->getCulture())
    {
      case "nl":
        return date("d-m-Y".($with_time ? " H:i" : ""), $ts);
      default:
        return date("Y-m-d".($with_time ? " H:i" : ""), $ts);
    }
  }

  public function getJsDateFormat()
  {
    $user = sfContext::getInstance()->getUser();
    switch ($user->getCulture())
    {
      case 'nl':
        return 'dd-mm-yy';
      default:
        return 'yy-mm-dd';
    }
  }

  public function generatePassword($length=8)
  {
    $password = "";
    for ($i=0; $i<$length; $i++)
    {
      $pos = (int)rand(0, strlen(self::ABC));
      $password .= substr(self::ABC, $pos, $pos+1);
    }
    return $password;
  }

  public function showInfo($title, $route, array $options=array(), $image=true)
  {
    if (!is_callable('link_to'))
      sfContext::getInstance()->getConfiguration()->loadHelpers('Url');

    $link = url_for($route, $options);
    //return $this->icon_to("bekijk.png", $title, $route, $options, array("onclick"=>"showDetails('$route'); return false;"));

    if ($image === true)
      return sprintf("<img src='%s' alt='%s' title='%s' style='cursor: pointer;' onclick=\"showDetails('%s', this, '%s'); event.cancelBubble=true;\" />",
            "/images/icons/bekijk.png", $title, $title, $link, $title);
    else
      return sprintf("<a href='#' onclick=\"showDetails('%s', this, '%s'); event.cancelBubble=true; \">%s</a>",
            $link, $title, $title);
  }
}

?>
