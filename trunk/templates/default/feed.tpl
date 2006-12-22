{config_load file="pacercms.conf"}<?xml version="1.0" ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">

<channel>  <title>{$site_name} - {$section_name|escape:'html'}</title>  <description>{$site_description}</description>   <language>{$smarty.config.language}</language>   <link>{$site_url}</link>   <image>    <url>{$feed_image_url}</url>    <title>{$site_name}</title>    <link>{$site_url}</link>    <height>88</height>    <width>31</width>     <description>{$site_description}</description>  </image>

{section name="items" loop=$feed_items}  <item>    <title><![CDATA[ {$feed_items[items].article_title} ]]></title>    <category><![CDATA[ {$section_name} ]]></category>    <link>{$site_url}/article.php?id={$feed_items[items].id}</link>    <description><![CDATA[ {$feed_items[items].article_summary} ]]></description>    <pubDate>{$feed_items[items].publish_date}</pubDate>  </item>
{/section}
</channel></rss>