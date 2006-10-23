<?php
$show_server_info = cm_get_access('server-info', $_SESSION['cm_user_id']);
?>
</div>
<div id="footer">
<hr />
  <ul>
    <li><strong>Live View:</strong></li>
    <?php cm_section_list('menu'); ?>
  </ul>
<ul>
  <li><a href="http://pacercms.sourceforge.net/">PacerCMS</a></li>
  <li><a href="http://code.google.com/p/pacercms/issues/list">Report a Bug</a></li>
  <?php if ($show_server_info == "true") {; ?><li><a href="server-info.php">Server Information</a></li><?php }; ?>
</ul>
</div>
</body></html>