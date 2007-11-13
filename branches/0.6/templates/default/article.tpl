{include file="header.tpl"}
<div id="content" class="fullStory">
  <div class="colWrap">
    <div class="fullCol">
{include file="article-sidebar.tpl"}
      <h2>{$article_title}</h2>
      {if $article_subtitle ne ''}<h3>{$article_subtitle}</h3>{/if}
      <p class="byline">
        <a href="{$site_url}/search.php?s={$article_author|escape:'url'}&amp;s_by=author"><strong>{$article_author}</strong></a>{if $article_author_title}, <em>{$article_author_title}</em>{/if}
      </p>
      
      {$article_text}
      
      </div>
  </div>
  {include file="section-summaries.tpl}
</div>
{include file="footer.tpl"}