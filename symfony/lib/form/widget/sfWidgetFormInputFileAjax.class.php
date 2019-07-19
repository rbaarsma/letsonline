<?php
/* 
 * @package    letsonline
 * @subpackage form
 * @author     Rein Baarsma <solidwebcode@googlemail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */

class sfWidgetFormInputFileAjax extends sfWidgetFormInputFileEditable
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Url');

    $script = <<<SCRIPT
<script src="/js/fileuploader.js" type="text/javascript"></script>
<script>
    function createUploader(){
        var uploader = new qq.FileUploader({
            element: document.getElementById('ajax_upload_avatar'),
            action: '%s',
            multiple: false,
            allowedExtensions: ['jpg','jpeg','png','gif'],
            onComplete: function (no, wrong_name, result) {
              document.getElementById('loading-avatar').style.display = "none";
              var img = document.getElementById('current_avatar');
              if (img.src.indexOf('missing'))
              {
                img.src = "/uploads/avatars/%userid%.jpg?"+new Date().getTime();
              }
              else
              {
                img.src += 1; // add random thing to src to make it reload
              }
            },
            onSubmit: function () {
              document.getElementById('loading-avatar').style.display = "block";
            },
            template: '<div class="qq-uploader">' +
                "<img src='%s' id='current_avatar' />" +
                "<div id='loading-avatar' style='display: none;'></div>" +
                '<div class="qq-upload-drop-area"><span>Drop file here to upload</span></div>' +
                "<div class='qq-upload-button'><input type='button' value='Upload new Photo' /></div>" +
                '<ul class="qq-upload-list"></ul>' +
             '</div>'
        });
    };
    jQuery(document).ready(createUploader);
</script>
SCRIPT;

    // first time user uploads an avatar only
    if (strpos($script, "%userid%") !== false)
      $script = str_replace("%userid%", sfContext::getInstance()->getUser()->getId(), $script);

    return sprintf("<div id='ajax_upload_avatar'><noscript>%s</noscript></div>%s",
      parent::render($name, $value, $attributes, $errors),
      sprintf($script, url_for('user_upload_avatar'), $this->getOption('file_src'))
    );
  }
}

?>
