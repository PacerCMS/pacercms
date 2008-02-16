<div class="inlineCol">

    <div class="articleToolbar">
        <ul>
            <li><strong>Tools:</strong></li>
            <li><a href="javascript:window.print()" class="printArticle">{t}Print{/t}</a></li>
            <li><a href="send.php?id={$article_id}" class="emailArticle">{t}E-mail{/t}</a></li>
            <li><a href="share.php?id={$article_id}" class="discussArticle">{t}Share This Article{/t}</a></li>
        </ul>
    </div>

    {if $article_images ne '' OR $article_swfs ne '' }
    <div class="mediaImage">    

        {section name="items" loop=$article_images}
        <p class="image"><img src="{$article_images[items].media_src}" alt="{$article_images[items].media_title}" /></p>
        {if $article_images[items].media_credit ne ''}<p class="imageCredit">{$article_images[items].media_credit}</p>{/if}
        {if $article_images[items].media_caption ne ''}<p class="imageCaption">{$article_images[items].media_caption}</p>{/if}
        {/section}
                {section name="items" loop=$article_swfs}
        <object type="application/x-shockwave-flash" data="{$article_swfs[items].media_src}" width="300" height="250">
        <param name="movie" value="{$article_swfs[items].media_src}" />
        </object>
        {if $article_swfs[items].media_credit ne ''}<p class="imageCredit">{$article_swfs[items].media_credit}</p>{/if}
        {if $article_swfs[items].media_caption ne ''}<p class="imageCaption">{$article_swfs[items].media_caption}</p>{/if}
        {/section}
        
    </div>
    {/if}
    
    {if $article_related ne '' }
    <div class="mediaRelated">
        <h4>Related</h4>
        <ul>
            {section name="items" loop=$article_related}
            <li><a href="{$article_related[items].media_src}">{$article_related[items].media_title}</a></li>
            {/section}
        </ul>
    </div>
    {/if}
    
	{if $section_headlines ne ''}	  <div class="sectionHeadlines">	    <h4><a href="{$section_url}" title="{$section_name|escape:'html'}">{t}More{/t} {$section_name|escape:'html'} {t}Headlines{/t}</a></h4>
        <ul>
        {section name="items" loop=$section_headlines}
            <li><a href="{$site_url}/article.php?id={$section_headlines[items].id}">{$section_headlines[items].article_title|escape:'html'}</a></li>
        {/section}
        </ul>
    </div>
    {/if}
    
</div>