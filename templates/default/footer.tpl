</div>

<div id="footer">
    <hr />
    <ul>
    {section name="items" loop=$site_sections}
    {/section}
        <li><a href="{$site_url}/page.php?id=2">Advertising</a></li>
        <li><a href="{$site_url}/feedback.php">Contact Us</a></li>
    </ul>

{*Note: You can obviously do whatever you like with the credit line. If you take it out, at least let me know you are using the software. It makes my day to see it up and running. *}

    <p>Copyright &copy; {$smarty.now|date_format:"%Y"}, <em>{$site_name} Online Edition</em>. Powered by <a href="http://pacercms.sourceforge.net">PacerCMS</a></p>

</div>

<div id="adWideSkyscraper">
    <p>&mdash; Advertisement &mdash;</p>
    <p><img src="{$site_url}/templates/{$site_templates_folder}/images/default_adWideSkyscraper.png" width="160" height="600" alt="Advertisement" /></p>
</div>

</body>
</html>