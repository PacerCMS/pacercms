{include file="header.tpl"}
<div id="content">
    <h2 class="sectionNameplate">Share Article</h2>
    <p>Select a service from the list below to post this article to an Internet community.</p>
    <div style="padding:10px;border:solid 1px #ccc;">
        <h3>{$article_title|escape}</h3>
        <p>{$article_summary|escape}</p>
        <p><strong>Link:</strong> <a href="{$site_url}/article.php?id={$article_id}">{$site_url}/article.php?id={$article_id}</a></p>
    </div>

    <div id="share_article">
        <ul>
            <li><a href="http://del.icio.us/post?url={$site_url|escape:'url'}/article.php%3Fid={$article_id}&title={$article_title|escape:'url'}" target="_blank">del.icio.us</a>
            <li><a href="http://digg.com/submit?phase=2&url={$site_url|escape:'url'}/article.php%3Fid={$article_id}&title={$article_title|escape:'url'}" target="_blank">Digg</a>
            <li><a href="http://furl.net/storeIt.jsp?u={$site_url|escape:'url'}/article.php%3Fid={$article_id}&t={$article_title|escape:'url'}" target="_blank">Furl</a>
            <li><a href="http://www.netscape.com/submit/?U={$site_url|escape:'url'}/article.php%3Fid={$article_id}&T={$article_title|escape:'url'}" target="_blank">Netscape</a>
            <li><a href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u={$site_url|escape:'url'}/article.php%3Fid={$article_id}&t={$article_title|escape:'url'}" target="_blank">Yahoo! My Web</a>
            <li><a href="http://www.stumbleupon.com/submit?url={$site_url|escape:'url'}/article.php%3Fid={$article_id}&title={$article_title|escape:'url'}" target="_blank">StumbleUpon</a>
            <li><a href="http://www.google.com/bookmarks/mark?op=edit&bkmk={$site_url|escape:'url'}/article.php%3Fid={$article_id}&title={$article_title|escape:'url'}" target="_blank">Google Bookmarks</a>
            <li><a href="http://www.technorati.com/faves?add={$site_url|escape:'url'}/article.php%3Fid={$article_id}" target="_blank">Technorati</a>
            <li><a href="http://blinklist.com/index.php?Action=Blink/addblink.php&Url={$site_url|escape:'url'}/article.php%3Fid={$article_id}&Title={$article_title|escape:'url'}" target="_blank">BlinkList
            <li><a href="http://www.newsvine.com/_wine/save?u={$site_url|escape:'url'}/article.php%3Fid={$article_id}&h={$article_title|escape:'url'}" target="_blank">Newsvine</a>
            <li><a href="http://ma.gnolia.com/bookmarklet/add?url={$site_url|escape:'url'}/article.php%3Fid={$article_id}&title={$article_title|escape:'url'}" target="_blank">ma.gnolia</a>
            <li><a href="http://reddit.com/submit?url={$site_url|escape:'url'}/article.php%3Fid={$article_id}&title={$article_title|escape:'url'}" target="_blank">reddit</a>
            <li><a href="https://favorites.live.com/quickadd.aspx?marklet=1&mkt=en-us&url={$site_url|escape:'url'}/article.php%3Fid={$article_id}&title={$article_title|escape:'url'}&top=1" target="_blank">Windows Live
            <li><a href="http://tailrank.com/share/?link_href={$site_url|escape:'url'}/article.php%3Fid={$article_id}&title={$article_title|escape:'url'}" target="_blank">Tailrank</a>
        </ul>
    </div>
</div>
{include file="footer.tpl"}