<div id="sideBar">
    <div id="mainMenu">
        <ul>
        {section name="items" loop=$site_sections}
        {/section}
        </ul>
    </div>
    <div class="subMenu">
        <ul>
            <li><a href="{$site_url}/submit.php?mode=letter">Write a Letter</a></li>
            <li><a href="{$site_url}/archives.php">Archived Issues</a></li>
            <li><a href="{$site_url}/submit.php?mode=article">Submit a Story</a></li>
        </ul>
        <ul>
            <li><a href="{$site_url}/page.php?id=1">About <em>{$site_name}</em></a></li>
            <li><a href="{$site_url}/page.php?id=2">Advertising Rates</a></li>	  
            <li><a href="{$site_url}/feedback.php">Contact Us</a></li>
        </ul>
    </div>
</div>