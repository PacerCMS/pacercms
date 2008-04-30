<div class="summaries">

    <hr />    

    <div class="leftCol">    

        <h4><a href="{$section_url_2}" title="{$section_name_2|escape:'html'}">{$section_name_2|escape:'html'}</a></h4>
        <ul>
        {section name="items" loop=$section_summary_2}
            <li><a href="{article_link article=$section_summary_2[items]}" title="{$section_summary_2[items].article_title|escape:'html'}">{$section_summary_2[items].article_title|escape:'html'}</a></li>
        {/section}
        </ul>        

        <h4><a href="{$section_url_4}" title="{$section_name_4|escape:'html'}">{$section_name_4|escape:'html'}</a></h4>
        <ul>
        {section name="items" loop=$section_summary_4}
            <li><a href="{article_link article=$section_summary_4[items]}" title="{$section_summary_4[items].article_title|escape:'html'}">{$section_summary_4[items].article_title|escape:'html'}</a></li>
        {/section}
        </ul>      

    </div>
    <div class="rightCol">    

        <h4><a href="{$section_url_3}" title="{$section_name_3|escape:'html'}">{$section_name_3|escape:'html'}</a></h4>
        <ul>
        {section name="items" loop=$section_summary_3}
            <li><a href="{article_link article=$section_summary_3[items]}" title="{$section_summary_3[items].article_title|escape:'html'}">{$section_summary_3[items].article_title|escape:'html'}</a></li>
        {/section}
        </ul>
       
        <h4><a href="{$section_url_5}" title="{$section_name_5|escape:'html'}">{$section_name_5|escape:'html'}</a></h4>
        <ul>
        {section name="items" loop=$section_summary_5}
            <li><a href="{article_link article=$section_summary_5[items]}" title="{$section_summary_5[items].article_title|escape:'html'}">{$section_summary_5[items].article_title|escape:'html'}</a></li>
        {/section}
        </ul>    
   
    </div>
</div>