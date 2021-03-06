{include file="header.tpl"}
<div id="content">
  <div class="topStory">
    <div class="biggerCol">
    {section name="items" loop=$cover_articles start=0 max=1}
    {if $cover_articles[items].article_publish gt $current_issue_date}
    <h5 class="breakingNews">{t}Breaking News{/t}</h5>
    {else}
    <h5>&mdash; {t}Top Story{/t} &mdash;</h5>
    {/if}

	<h2><a href="{article_link article=$cover_articles[items]}" title="{$cover_articles[items].article_title|escape:'html'}">{$cover_articles[items].article_title|escape:'html'}</a></h2>
	<p class="byline"><a href="{$site_url}/search.php?s={$cover_articles[items].article_author|escape:'url'}&amp;s_by=author"><strong>{$cover_articles[items].article_author|escape:'html'}</strong></a>{if $cover_articles[items].article_author_title ne ''}, <em>{$cover_articles[items].article_author_title|escape:'html'}</em>{/if}</p>
	<p class="summary">{$cover_articles[items].article_summary}</p>
	<p class="moreLink"><a href="{article_link article=$cover_articles[items]}" title="{$cover_articles[items].article_title|escape:'html'}"><strong>{t}Read More{/t}</strong></a></p>
    {/section}
    </div>
    <div class="smallerCol">
      <div class="mediaImage">

    {if $top_article_images ne '' OR $top_article_swfs ne '' }
    
        {section name="items" loop=$top_article_images}
        <p class="image"><img src="{$top_article_images[items].media_src}" alt="{$top_article_images[items].media_title}" /></p>
        {if $top_article_images[items].media_credit ne ''}
        <p class="imageCredit">{$top_article_images[items].media_credit}</p>
        {/if}
        {if $top_article_images[items].media_caption ne ''}
        <p class="imageCaption">{$top_article_images[items].media_caption}</p>
        {/if}
        {/section}
        
        {section name="items" loop=$top_article_swfs}
        <object type="application/x-shockwave-flash" data="{$top_article_swfs[items].media_src}" width="300" height="250">
        <param name="movie" value="{$top_article_swfs[items].media_src}" />
        </object>
        {if $top_article_swfs[items].media_credit ne ''}<p class="imageCredit">{$top_article_swfs[items].media_credit}</p>{/if}
        {if $top_article_swfs[items].media_caption ne ''}<p class="imageCaption">{$top_article_swfs[items].media_caption}</p>{/if}
        {/section}
        
    {/if}

      </div>
    </div>
	
    <hr />

    <div class="colWrap">
      <div class="bigCol">

	    {section name="items" loop=$cover_articles start=1}
	    <div class="otherStory">
		<h3><a href="{article_link article=$cover_articles[items]}" title="{$cover_articles[items].article_title|escape:'html'}">{$cover_articles[items].article_title|escape:'html'}</a></h3>
		<p class="summary">{$cover_articles[items].article_summary|escape:'html'}</p>
		<p class="moreLink"><a href="{article_link article=$cover_articles[items]}" title="{$cover_articles[items].article_title|escape:'html'}">{t}Read More{/t}</a></p>
	    </div>
        {/section}
	  
      </div>
      <div class="smallCol">
        <div class="homeSidebar">
        
        {include file="poll-form.tpl"}
        
          <div class="divider">
            <hr />
          </div>
		  {$section_sidebar}
          <div class="divider">
            <hr />
          </div>
          <p><a href="feed.php" class="rssFeed">{t}Top Stories{/t}</a></p>
        </div>
      </div>
    </div>
  </div>
  {include file="section-summaries.tpl}
</div>
{include file="footer.tpl"}
