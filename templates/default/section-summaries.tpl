<div class="summaries">

    <hr />    

    <div class="leftCol">    

        <h4><a href="{$section_url_2}">{$section_name_2|escape:'html'}</a></h4>
        <ul>
        {section name="items" loop=$section_summary_2}
            <li><a href="{$site_url}/article.php?id={$section_summary_2[items].id}">{$section_summary_2[items].article_title|escape:'html'}</a></li>
        {/section}
        </ul>        

        <h4><a href="{$section_url_4}">{$section_name_4|escape:'html'}</a></h4>
        <ul>
        {section name="items" loop=$section_summary_4}
            <li><a href="{$site_url}/article.php?id={$section_summary_4[items].id}">{$section_summary_4[items].article_title|escape:'html'}</a></li>
        {/section}
        </ul>      

    </div>
    <div class="rightCol">    

        <h4><a href="{$section_url_3}">{$section_name_3|escape:'html'}</a></h4>
        <ul>
        {section name="items" loop=$section_summary_3}
            <li><a href="{$site_url}/article.php?id={$section_summary_3[items].id}">{$section_summary_3[items].article_title|escape:'html'}</a></li>
        {/section}
        </ul>
       
        <h4><a href="{$section_url_5}">{$section_name_5|escape:'html'}</a></h4>
        <ul>
        {section name="items" loop=$section_summary_5}
            <li><a href="{$site_url}/article.php?id={$section_summary_5[items].id}">{$section_summary_5[items].article_title|escape:'html'}</a></li>
        {/section}
        </ul>    
   
    </div>
</div>