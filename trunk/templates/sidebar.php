<div id="sideBar">
  <div id="mainMenu">
    <ul>
      <?php section_list('list'); ?>
    </ul>
  </div>
  <div class="subMenu">
    <ul>
      <li><a href="<?php echo site_info('url'); ?>/submit.php?mode=letter">Write a Letter</a></li>
      <li><a href="<?php echo site_info('url'); ?>/archives.php">Archived Issues</a></li>
      <li><a href="<?php echo site_info('url'); ?>/submit.php?mode=story">Submit a Story</a></li>
    </ul>
    <ul>
	  <li><a href="<?php echo site_info('url'); ?>/page.php?id=1">About <em><?php echo site_info('name'); ?></em></a></li>
      <li><a href="<?php echo site_info('url'); ?>/page.php?id=2">Advertising Rates</a></li>	  
      <li><a href="<?php echo site_info('url'); ?>/feedback.php">Contact Us</a></li>
    </ul>
  </div>
</div>
