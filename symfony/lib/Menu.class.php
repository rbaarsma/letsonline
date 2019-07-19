<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of menu
 *
 * @author rein
 */
class Menu {
  protected $links = array();
  protected $sub = array();
  protected $key = -1;
  protected $highlight = -1;

  public function __construct($array)
  {
    foreach ($array as $key=>$val)
    {
      if ($val instanceof Menu)
        $this->addItem($key, $val);
      else
        $this->addItem($val);
    }
  }

  public function addItem($link, $submenu=false)
  {
    $this->key++;
    $this->links[$this->key] = $link;
    if ($submenu)
      $this->addSubMenu($submenu);
  }

  public function addSubMenu(Menu $submenu)
  {
    $this->sub[$this->key] = $submenu;
  }

  public function getSubMenus()
  {
    return $this->sub;
  }

  public function render($html_id=null, $curr=null, $submenus=true, $display=true)
  {
    $display_style = $display ? "" : "style='display: none'";
    ?>
    <ul class="menu" <?php echo $html_id ? "id='$html_id'" : ''?> <?php echo $display_style ?>>
      <?php foreach ($this->links as $key=>$link): ?>
      <li<?php echo !is_null($curr) && $curr == $key ? " class='active'" : ""?><?php echo $this->highlight == $key ? " class='highlight'" : ""?>>
        <?php echo $link ?>
        <?php if ($submenus && isset($this->sub[$key])): ?>
          <?php echo $this->sub[$key]->render(null, null, true, $curr==$key); ?>
        <?php endif; ?>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php
  }

  public function highlight($link)
  {
    foreach ($this->links as $key=>$val)
    {
      $matches = array();
      if (preg_match("/href=\"(.*?)\"/i", $val, $matches))
        if ($matches[1] == $link)
          $this->highlight = $key;
    }

    foreach ($this->sub as $menu)
      $menu->highlight($link);
  }
}
?>
