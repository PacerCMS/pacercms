{if $sender_name eq ''}
{t}Message From:{/t} [[Your Name]]
{else}
{t}Message From:{/t} {$sender_name}
{/if}

------------------------------------------------

{$article_title|trim}

{$article_summary|trim}

------------------------------------------------
{t}Link:{/t} {$site_url}/article.php?id={$article_id}


---

{t}NOTE:{/t} You have received this e-mail because a visitor used a utility found on our Web site to forward you this article summary and link. At no time was your e-mail address stored on our server. Please visit our site to learn more about our privacy policies.