<?php

class Info
{
  public static function get($path_info)
  {
    switch ($path_info)
    {
      //case "/transactions/list":
        //return __("In this overview you will be able to find all transactions that you have ever made.<br/><br/>Find specific transactions by using the <b>Search</b> on the far left.");
      //case "/":
      //case "/transactions":
        //return __("On this screen is an overview of all recent transactions. If you have any reminders to accept they will be at the top under 'reminders to accept'. You can accept these by clicking on details (the maginfying glass) and using the <b>Confirm</b> button.");
      case "/members/album":
        return __("Click on any photo to see that user's profile.<br/><br/>You can upload your own photo <a href='/members/album'>here</a>");
      case "/email":
        return __("The e-mail sent by the system will automatically contain a footer notifying the receiver that the e-mail has been sent through the LETSOnline system.");
      default:
        return false; //$path_info;
    }
  }
}
?>
