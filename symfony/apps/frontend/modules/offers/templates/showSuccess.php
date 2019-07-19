<?php slot('actions'); ?>
<?php echo link_to(__("E-mail Member"), "user_email_slug", array("slug"=>$offer->getUser()->getSlug(),"offer"=>$offer->getId())); ?>
<?php end_slot(); ?>

<div>
  <table>
    <thead>
      <th colspan="2">
        <?php echo __("%type% Details", array("%type%"=>$offer->getType())); ?>
      </th>
    </thead>
    <tbody>
      <tr>
        <th><?php echo __("Member"); ?></th>
        <td>
          <?php echo $offer->getUser()->getRaw('profileLink'); ?>
        </td>
      </tr>
      <tr>
        <th><?php echo __("Category"); ?></th>
        <td><?php echo __($offer->getCategory()) ?></td>
      </tr>
      <tr>
        <th><?php echo __("Type"); ?></th>
        <td><?php echo __($offer->getType()) ?></td>
      </tr>
      <tr>
        <th><?php echo __("Experience"); ?></th>
        <td><?php echo __($offer->getExperience()) ?></td>
      </tr>
      <tr>
        <th><?php echo __("Description"); ?></th>
        <td><strong><?php echo $offer->getDescription(); ?></strong></td>
      </tr>
      <?php if ($offer->getUser()->getShowAddress()): ?>
      <tr>
        <th><?php echo __("Address"); ?></th>
        <td><?php echo $offer->getUser()->getAddressLine(); ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <th><?php echo __("Zip Code"); ?></th>
        <td><?php echo $offer->getUser()->getZipCode(); ?></td>
      </tr>
      <tr>
        <th><?php echo __("City"); ?></th>
        <td><?php echo $offer->getUser()->getCity(); ?></td>
      </tr>
    </tbody>
  </table>
</div>