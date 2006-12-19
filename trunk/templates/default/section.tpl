{include file="header.tpl"}

<div id="content">
<h2 class="sectionNameplate">{$section_name|escape 'html'}</h2>
  <div class="topStory">
    <div class="bigCol">

	<h2><a href="{$site_url}/article.php?id={$top_article_id}" title="{$top_article_title|escape 'html'}">{$top_article_title|escape 'html'}</a></h2>
	<p class="byline" style="width:400px !important"><a href="{$site_url}/search.php?s={$top_article_author|escape:'url'}&amp;s_by=author"><strong>{$top_article_author}</strong></a>{if $top_article_author_title}, <em>{$top_article_author_title}</em>{/if}</p>
	<p class="summary">{$top_article_summary|escape 'html'}</p>
	<p class="moreLink"><a href="{$site_url}/article.php?id={$top_article_id}" title="{$top_article_title|escape 'html'}"><strong>Read More</strong></a></p>
	
    </div>
    <div class="smallCol">
      <div class="sectionSidebar">
	  <p><strong><a href="mailto:{$section_editor_email}">{$section_editor|escape 'html'}</a></strong><br />
	  <em>{$section_editor_title|escape 'html'}</em></p>
  	  <div class="divider"><hr /></div>
	  {$section_sidebar}
	  <div class="divider"><hr /></div>
	  <p><a href="{$site_url}/feed.php?id={$section_id}" class="rssFeed">{$section_name|escape 'html'}</a></p>
	  </div>
    </div>
    <div class="colWrap">
      <div class="fullCol">
        {if $other_articles ne ''}<hr />{/if}
	    {section name="items" loop=$other_articles}	    <div class="otherStory">
		<h3><a href="{$site_url}/article.php?id={$other_articles[items].id}" title="$title">{$other_articles[items].article_title}</a></h3>
		<p class="summary">{$other_articles[items].article_summary|escape 'html'}</p>
		<p class="moreLink"><a href="{$site_url}/article.php?id={$other_articles[items].id}" title="{$other_articles[items].article_title|escape 'html'}">Read More</a></p>
	    </div>
        {/section}
      </div>
    </div>
    
  </div>
  {include file="section-summaries.tpl"}
</div>

{include file="footer.tpl"}