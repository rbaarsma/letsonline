<?php

class myDoctrinePager extends sfDoctrinePager
{
  protected $templates = array();

  public function __construct(Doctrine_Query $query, $max_pages=null)
  {
    if (is_null($max_pages))
      $max_pages = sfConfig::get('app_paging_limit');

    if (!$max_pages)
      throw new Exception("Please set application config paging_limit to a value above 0");

    parent::__construct($model="", $max_pages);
    $this->setQuery($query);
    $this->setLinksTemplate("global/paging_links");
    $this->setCountTemplate("global/paging_count");
    $this->setFilterTemplate("global/paging_filter");
  }
  
  public function setLinksTemplate($template)
  {
    $this->setTemplate("links",$template);
  }

  public function setFilterTemplate($template)
  {
    $this->setTemplate("filter",$template);
  }

  public function setCountTemplate($template)
  {
    $this->setTemplate("count",$template);
  }

  public function setTemplate($key, $template)
  {
    $this->templates[$key] = $template;
  }

  public function render()
  {
    $this->renderLinks();
    $this->renderCount();
  }

  public function renderJS($url)
  {
    ?>
    <script type="text/javascript">
      var filter = new Filter('data', '<?php echo $url; ?>');
    </script>
    <?php
  }

  public function renderLinks()
  {
    echo $this->renderPartial($this->templates['links']);
  }

  public function renderCount()
  {
    echo $this->renderPartial($this->templates['count']);
  }

  public function renderFilter(sfForm $form, $ajax_url)
  {
    $this->renderJS($ajax_url);
    echo $this->renderPartial($this->templates['filter'], array("form"=>$form));
  }

  protected function renderPartial($template, array $vars=array())
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
    return get_partial($template, array_merge(array("pager"=>$this), $vars));
  }
}