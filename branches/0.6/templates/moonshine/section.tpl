{include file="header.tpl"}

<div id="content">
<h2 class="sectionNameplate">{$section_name|escape:'html'}</h2>
  <div class="topStory">
    <div class="bigCol">
    {section name="items" loop=$section_articles start=0 max=1}
	<h2><a href="{$site_url}/article.php?id={$section_articles[items].id}" title="{$section_articles[items].article_title|escape:'html'}">{$section_articles[items].article_title|escape:'html'}</a></h2>
	<p class="byline" style="width:400px !important"><strong><a href="{$site_url}/search.php?s={$section_articles[items].article_author|escape:'url'}&amp;s_by=author">{$section_articles[items].article_author|escape:'html'}</a></strong>{if $top_article_author_title}, <em>{$section_articles[items].article_author_title|escape:'html'}</em>{/if}</p>
	<p class="summary">{$section_articles[items].article_summary}</p>
	<p class="moreLink"><strong><a href="{$site_url}/article.php?id={$section_articles[items].id}" title="{$section_articles[items].article_title|escape:'html'}">Read More</a></strong></p>
	{/section}
    </div>
    <div class="smallCol">
      <div class="sectionSidebar">
	  <p><strong><a href="mailto:{$section_editor_email}">{$section_editor|escape:'html'}</a></strong><br />
	  <em>{$section_editor_title|escape:'html'}</em></p>
  	  <div class="divider"><hr /></div>
	  {$section_sidebar}
	  <div class="divider"><hr /></div>
	  <p><a href="{$site_url}/feed.php?id={$section_id}" class="rssFeed">{$section_name|escape:'html'}</a></p>
	  </div>
    </div>
    <div class="colWrap">
      <div class="fullCol">
        {if count($section_articles) gt 1}<hr />{/if}
	    {section name="items" loop=$section_articles start=1}	    <div class="otherStory">
    	<h3><a href="{$site_url}/article.php?id={$section_articles[items].id}" title="{$section_articles[items].article_title|escape:'html'}">{$section_articles[items].article_title|escape:'html'}</a></h3>
    	<p class="summary">{$section_articles[items].article_summary}</p>
    	<p class="moreLink"><strong><a href="{$site_url}/article.php?id={$section_articles[items].id}" title="{$section_articles[items].article_title|escape:'html'}">Read More</a></strong></p>
	    </div>
        {/section}
      </div>
    </div>
    
  </div>
  {include file="section-summaries.tpl"}
</div>

{include file="footer.tpl"}