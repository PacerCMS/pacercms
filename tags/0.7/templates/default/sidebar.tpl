<div id="sideBar">
    <div id="mainMenu">
        <ul>
        {section name="items" loop=$site_sections}            <li><a href="{$site_sections[items].section_url}" title="{$site_sections[items].section_name|escape:'html'}">{$site_sections[items].section_name|escape:'html'}</a></li>
        {/section}
        </ul>
    </div>
    <div class="subMenu">
        <ul>
            <li><a href="{$site_url}/submit.php?mode=letter">{t}Write a Letter{/t}</a></li>
            <li><a href="{$site_url}/archives.php">{t}Archived Issues{/t}</a></li>
            <li><a href="{$site_url}/submit.php?mode=article">{t}Submit a Story{/t}</a></li>
        </ul>
        <ul>
            <li><a href="{$site_url}/page.php?id=1">{t}About{/t} <em>{$site_name}</em></a></li>
            <li><a href="{$site_url}/page.php?id=2">{t}Advertising Rates{/t}</a></li>	  
            <li><a href="{$site_url}/feedback.php">{t}Contact Us{/t}</a></li>
        </ul>
    </div>
</div>
