{include file="header.tpl"}

<div id="content">
<h2 class="sectionNameplate">{$site_name} {t}Archives{/t}</h2>
<div class="colWrap">
<div class="bigCol">

{if $issue_number ne ''}
<h3>{t}Volume{/t} {$issue_volume}, {t}Issue{/t} {$issue_number}</h3>

<table>
<tr>
<th>{t}Headline{/t}</th>
<th>{t}Author{/t}</th>
<th>{t}Section{/t}</th>
<th>{t}Words{/t}</th>
</tr>
{section name="items" loop=$article_list}
<tr>
<td><a href="{$site_url}/article.php?id={$article_list[items].article_id}" title="{$article_list[items].article_title|escape:'html'}">{$article_list[items].article_title|escape:'html'}</a></td>
<td>{$article_list[items].article_author|escape:'html'}</td>
<td>{$article_list[items].section_name|escape:'html'}</td>
<td>{$article_list[items].article_word_count}</td>
</tr>
{sectionelse}
<tr>
<td colspan="4">{t}No articles were uploaded to this issue.{/t}</td>
</tr>
{/section}
</table>
{/if}

<p>Select an issue to the right to begin browsing through the most-recent volume of the newspaper. If you are looking for an article from a previous year, use the "Volumes" list below it to see any article published. If you need any help, just <a href="mailto:{$site_email}">send us an email</a>.</p>

</div>
<div class="smallCol">

{if $issue_volume ne ''}
<h3>Volume {$issue_volume}</h3>
{/if}

<table>
<tr>
<th>{t}Issue{/t}</th>
<th>{t}Date{/t}</th>
</tr>
{section name="items" loop=$issue_list}
<tr>
<td>{t}Issue{/t} {$issue_list[items].issue_number}</td>
<td><a href="{$site_url}/archives.php?issue={$issue_list[items].issue_date}" title="Issue {$issue_list[items].issue_number}, Volume {$issue_volume}">{$issue_list[items].issue_date|date_format:"%B %e, %Y"}</a></td>
</tr>
{sectionelse}
<tr>
<td colspan="2">{t}That volume does not exist in our archives. Select a valid entry from the list below.{/t}</td>
</tr>
{/section}
</table>

<h3>{t}Volumes in our Internet Archives{/t}</h3>
<ul>
{section name="items" loop=$volume_list}
<li><a href="{$site_url}/archives.php?volume={$volume_list[items].volume}" title="Volume {$volume_list[items].volume}">Volume {$volume_list[items].volume}</a> ({$volume_list[items].issue_count})</li>
{/section}
</ul>
</div>
</div>
</div>

{include file="footer.tpl"}