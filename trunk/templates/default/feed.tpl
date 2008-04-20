{config_load file="pacercms.conf"}<?xml version="1.0" ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">

<channel>
  <title>{$site_name}{if $section_name ne ''} - {$section_name|escape:'html'}{/if}</title>
  <description>{$site_description|escape:'html'}</description> 
  <language>{$smarty.config.language}</language> 
  <link>{$site_url}</link> 
  {if $feed_image_url ne ''}
  <image>
    <url>{$feed_image_url}</url>
    <title>{$site_name}</title>
    <link>{$site_url}</link>
    <height>88</height>
    <width>31</width> 
    <description>{$site_description}</description>
  </image>
  {/if}

{section name="items" loop=$feed_items}
  <item>
    <title><![CDATA[ {$feed_items[items].article_title} ]]></title>
    <category><![CDATA[ {$feed_items[items].section_name} ]]></category>
    <link>{article_link article=$feed_items[items]}</link>
    <description><![CDATA[ {$feed_items[items].article_summary} ]]></description>
    <pubDate>{$feed_items[items].article_publish}</pubDate>
  </item>
{/section}

</channel>
</rss>