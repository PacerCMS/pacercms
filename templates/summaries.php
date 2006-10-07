<?php
$issue = current_issue('id');
?>
<div class="summaries">
	<hr />
    <div class="leftCol">
      <h4><a href="<?php echo htmlentities(section_info('url','2')); ?>"><?php echo htmlentities(section_info('name','2')); ?></a></h4>
      <ul>
        <?php section_headlines(2,$issue); ?>
      </ul>
      <h4><a href="<?php echo htmlentities(section_info('url','4')); ?>"><?php echo htmlentities(section_info('name','4')); ?></a></h4>
      <ul>
        <?php section_headlines(4,$issue); ?>
      </ul>
    </div>
    <div class="rightCol">
      <h4><a href="<?php echo htmlentities(section_info('url','3')); ?>"><?php echo htmlentities(section_info('name','3')); ?></a></h4>
      <ul>
        <?php section_headlines(3,$issue); ?>
      </ul>
      <h4><a href="<?php echo htmlentities(section_info('url','5')); ?>"><?php echo htmlentities(section_info('name','5')); ?></a></h4>
      <ul>
        <?php section_headlines(5,$issue); ?>
      </ul>
    </div>
  </div>