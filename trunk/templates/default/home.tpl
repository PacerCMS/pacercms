{include file="header.tpl"}
<div id="content">
  <div class="topStory">
    <div class="biggerCol">
    {if $top_article_publish gt $current_issue_date}
    <h5 class="breakingNews">Breaking News</h5>
    {else}
    <h5>&mdash; TOP STORY &mdash;</h5>
    {/if}

	<h2><a href="{$site_url}/article.php?id={$top_article_id}" title="{$top_article_title|escape 'html'}">{$top_article_title|escape 'html'}</a></h2>
	<p class="byline"><a href="{$site_url}/search.php?s={$top_article_author|escape:'url'}&amp;s_by=author"><strong>{$top_article_author}</strong></a>{if $top_article_author_title}, <em>{$top_article_author_title}</em>{/if}</p>
	<p class="summary">{$top_article_summary|escape 'html'}</p>
	<p class="moreLink"><a href="{$site_url}/article.php?id={$top_article_id}" title="{$top_article_title|escape 'html'}"><strong>Read More</strong></a></p>

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

	    {section name="items" loop=$other_articles}	    <div class="otherStory">
		<h3><a href="{$site_url}/article.php?id={$other_articles[items].id}" title="$title">{$other_articles[items].article_title}</a></h3>
		<p class="summary">{$other_articles[items].article_summary|escape 'html'}</p>
		<p class="moreLink"><a href="{$site_url}/article.php?id={$other_articles[items].id}" title="{$other_articles[items].article_title|escape 'html'}">Read More</a></p>
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
          <p><a href="feed.php?id=$section" class="rssFeed">Top
              Stories</a> <small>(What's this?)</small></p>
        </div>
      </div>
    </div>
  </div>
  {include file="section-summaries.tpl}
</div>
{include file="footer.tpl"}
