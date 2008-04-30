</div>
<div id="footer">
<hr />
  <ul>
    <li><strong><?php echo gettext("Live View:"); ?></strong></li>
    <?php cm_section_list('menu'); ?>
  </ul>
<ul>
    <li><a href="http://pacercms.sourceforge.net/?ver=<?php echo urlencode(CM_VERSION); ?>">PacerCMS <?php echo CM_VERSION; ?></a></li>
    <li><a href="http://code.google.com/p/pacercms/issues/list"><?php echo gettext("Report a Bug"); ?></a></li>
    <?php if (cm_auth_restrict('server-info') == "true") { ?>
    <li><a href="server-info.php"><?php echo gettext("Server Information"); ?></a></li>
    <?php }; ?>
</ul>
</div>
</body>
</html>