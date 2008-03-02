<?php
// Loads everything needed to run PacerCMS
include('cm-includes/cm-header.php');

$module = "index";

// SECURITY - User must be authenticated to view page //
cm_auth_module($module);
$issue = cm_get_settings('next_issue');

// Database Query
$query = "SELECT *, DATE_FORMAT(submitted_sent, '%b. %e, %Y at %l:%i %p') AS sent ";
$query .= " FROM cm_submitted WHERE issue_id = '$issue' ";
$query .= " ORDER BY submitted_sent DESC;";

// Run Query
$result = cm_run_query($query);
$submitted_articles = $result->GetArray();

get_cm_header();

// Notify for need of database upgrade
if ($needs_db_upgrade == true)
{
    print '<p class="alertMessage">Your database needs to be <a href="upgrade.php">upgraded</a>.';
} 


?>

<h2><?php echo gettext("Welcome"); ?> <?php echo $_SESSION['user_data']['user_first_name'] . ' ' . $_SESSION['user_data']['user_last_name']; ?></h2>
<div class="sidebar" style="border:solid 1px #ccc;background: #fff;padding:10px;">
<h3><?php echo gettext("Quick Links") ?></h3>
<ul>
<?php if (cm_auth_restrict('article-browse') == "true") { ?>
<li><a href="article-browse.php"><?php echo gettext("Manage Articles"); ?></a></li>
<?php } ?>
<?php if (cm_auth_restrict('article-edit') == "true") { ?>
<li><a href="article-edit.php?action=new"><?php echo gettext("Add an Article"); ?></a></li>
<?php } ?>
<?php if (cm_auth_restrict('poll-browse') == "true") { ?>
<li><a href="poll-browse.php"><?php echo gettext("Manage Poll Questions"); ?></a></li>
<?php } ?>
<?php if (cm_auth_restrict('poll-edit') == "true") { ?>
<li><a href="poll-edit.php?action=new"><?php echo gettext("Add a Poll Question"); ?></a></li>
<?php } ?>
</ul>
</div>
<h3><?php echo gettext("Announcements"); ?></h3>
<?php echo autop(cm_get_settings('site_announcement')); ?>

<div style="clear:both;"></div>

<?php if (cm_auth_restrict('submitted-browse') == "true") { ?>

<fieldset class="<?php echo "$module-form"; ?>">
<legend><a href="submitted-browse.php"><?php echo gettext("Submitted Articles"); ?></a><?php echo gettext(" for"); ?> <?php echo $_SESSION['issue_data']['next_issue_date'] . gettext(" (Volume ") . $_SESSION['issue_data']['next_issue_volume'] . gettext(", No. ") . $_SESSION['issue_data']['next_issue_number'] . ")"; ?></legend>

<?php if (!empty($submitted_articles)) { ?>

    <table class="<?php echo $module; ?>-table">
    <tr>
    <th><?php echo gettext("Sent"); ?></th>
    <th><?php echo gettext("From"); ?></th>
    <th><?php echo gettext("Subject"); ?></th>
    <th><?php echo gettext("Tools"); ?></th>
    </tr>
    <?php
    foreach($submitted_articles as $article)
    {
        $id = $article['id'];
        $sent = $article['submitted_sent'];
        $author = $article['submitted_author'];
        $title = $article['submitted_title'];
        $email = $article['submitted_author_email'];
        $keyword = $article['submitted_keyword'];
    ?>
        <tr>
        <td><?php echo $sent; ?></td>
        <td><a href="mailto:<?php echo $email; ?>"><?php echo $author; ?></a></td>
        <td><p><a href="submitted-edit.php?id=<?php echo $id; ?>#preview"><strong><?php echo $title; ?></strong></a> - <em><?php echo $keyword;?></em></p>
        </td>
            <td nowrap class="actionMenu">
            <ul class="center">
            <li class="command-preview"><a href="submitted-edit.php?id=<?php echo $id; ?>#preview"><?php echo gettext("Preview"); ?></a></li>
            <li class="command-post"><a href="article-edit.php?action=new&amp;submitted_id=<?php echo $id; ?>"><?php echo gettext("Post"); ?></a></li>
             <?php if (cm_auth_restrict('submitted-delete') == "true") { ?>
             <li class="command-delete"><a href="submitted-edit.php?id=<?php echo $id; ?>#delete"><?php echo gettext("Delete"); ?></a></li>
            <?php } ?>
            </ul>
            </td>
            </tr>
<?php

        }
?>
    </table>
    
<?php } else { ?>
    <p><?php echo gettext("There are no submitted articles for this issue."); ?></p>
<?php } ?>

</fieldset>

<?php } ?>

<?php get_cm_footer(); ?>
