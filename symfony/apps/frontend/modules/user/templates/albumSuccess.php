<style type="text/css">
  .avatar { margin: 0; }
</style>
<table id="photo_album">
  <thead>
    <tr>
      <th><?php echo __("Photo Album"); ?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <?php foreach ($users as $user): ?>
        <div style="float: left; width: 70px; margin-bottom: 1em; margin-right: 1em; font-size: 10px;">
          <a href='#' onclick="showDetails('<?php echo $user->getRaw('profileUrl'); ?>', this, '<?php echo __("Profile of %user%", array("%user%"=>$user->getDisplayName())) ?>')" style="text-decoration: none;">
            <?php echo $user->getRaw('avatarImage'); ?>
          </a><br/>
        </div>
        <?php endforeach; ?>
      </td>
    </tr>
  </tbody>
</table>
