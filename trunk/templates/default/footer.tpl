</div>

<div id="footer">
    <hr />
    <ul>
    {section name="items" loop=$site_sections}        <li><a href="{$site_sections[items].section_url}" title="{$site_sections[items].section_name|escape:'html'}">{$site_sections[items].section_name|escape:'html'}</a></li>
    {/section}
        <li><a href="{$site_url}/page.php?id=2">{t}Advertising{/t}</a></li>
        <li><a href="{$site_url}/feedback.php">{t}Contact Us{/t}</a></li>
    </ul>

{*Note: You can obviously do whatever you like with the credit line. If you take it out, at least let us know you are using the software. It makes our day to see it up and running. *}

    <p>Copyright &copy; {$smarty.now|date_format:"%Y"}, <em>{$site_name} {t}Online Edition{/t}</em>. {t}Powered by{/t} <a href="http://pacercms.sourceforge.net">PacerCMS</a></p>

</div>

{include file="advertisements.tpl"}

</body>
</html>
