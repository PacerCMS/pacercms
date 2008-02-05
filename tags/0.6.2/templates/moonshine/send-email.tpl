{if $sender_name eq ''}
Message From: [[Your Name]]
{else}
Message From: {$sender_name}
{/if}

------------------------------------------------

{$article_title|trim}

{$article_summary|trim}

------------------------------------------------
Link: {$site_url}/article.php?id={$article_id}


---

NOTE: You have received this e-mail because a visitor used a utility found on our Web site to forward you this article summary and link. At no time was your e-mail address stored on our server. Please visit our site to learn more about our privacy policies.