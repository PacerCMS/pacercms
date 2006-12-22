<div class="inlineCol">
    <div class="articleToolbar">
    <ul>
    <li><strong>Tools:</strong></li>
    <li><a href="javascript:window.print()" class="printArticle">Print</a></li>
    <li><a href="send.php?id={$article_id}" class="emailArticle">E-mail</a></li>
    {literal}<li><a href="javascript:w=window;d=document;var u;s='';ds=d.selection;if(ds&&ds!=u){if(ds.createRange()!=u){s=ds.createRange().text;}}else if(d.getSelection!=u){s=d.getSelection()+'';}else if(w.getSelection!=u){s=w.getSelection()+'';} if(s.length<2){h=String(w.location.href);if(h.length==0||h.substring(0,6)=='about:'){s=prompt('Technorati Realtime Search for:',s);}else{s=w.location.href;}}if(s!=null)w.location='http://technorati.com/search/'+escape(s)+'?sub=toolsearch';void(1);" class="discussArticle">Search for Blogs</a></li>{/literal}
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
            <li><a href="{$article_related[items].media_src}">{$article_related[items].media_title}</li>
            {/section}
        </ul>
    </div>
    {/if}
    
    {if $section_headlines ne ''}
    <div class="sectionHeadlines">
        <h4><a href="{$article_section_url}" title="{$article_section_name|escape:'html'}">More {$article_section_name|escape:'html'} Headlines</a></h4>
        <ul>
        {section name="items" loop=$section_headlines}
            <li><a href="{$site_url}/article.php?id={$section_headlines[items].id}">{$section_headlines[items].article_title|escape:'html'}</a></li>
        {/section}
        </ul>
    </div>
    {/if}
    
</div>