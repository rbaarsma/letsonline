<?php if ($offers->count() > 0): ?>
<div>
  <table class="list" width="100%">
    <thead>
      <tr>
        <th colspan="5">
          <?php echo Utils::getInstance()->icon_to("edit.png", __("Edit your Offers & Requests"), "personal_offers", array(), array("style"=>"float:right; margin-right: 5px;")); ?>
          <?php if ($offers->getFirst()->getUserId() == $sf_user->getId()): ?>
            <?php echo __("Your Offers & Requests"); ?>
          <?php else: ?>
            <?php echo __("%user%'s Offers & Requests", array("%user%"=>$offers->getFirst()->getUser())); ?>
          <?php endif; ?>
        </th>
      </tr>
      <tr>
        <td><?php echo __("Category") ?></td>
        <td><?php echo __("Type") ?></td>
        <td><?php echo __("Description") ?></td>
        <td><?php echo __("Experience") ?></td>
        <td>&nbsp;</td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($offers as $offer): ?>
      <tr <?php echo time()-strtotime($offer->getUpdatedAt()) < 5 ? "class='new'" : ""?> onclick="location.href='<?php echo url_for('personal_offers_edit', array('id'=>$offer->getId())); ?>';">
        <td><?php echo __($offer->getCategory()) ?></td>
        <td><?php echo __($offer->getType()) ?></td>
        <td><?php echo $offer->getDescription() ?></td>
        <td><?php echo __($offer->getExperience()) ?></td>
        <td width="23">
        <?php if ($offer->getUserId() == $sf_user->getObject()->getId()): ?>
          <?php echo Utils::getInstance()->icon_to("edit.png", __("edit %type%", array("%type%"=>$offer->getType())),  'personal_offers_edit', array('id'=>$offer->getId())); ?>
        <?php else: ?>
          <?php echo Utils::getInstance()->icon_to("bekijk.png", __("view details"),  'personal_offers_show', array('id'=>$offer->getId())); ?>
        <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>