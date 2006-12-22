{include file="header.tpl"}

<div id="content">
<h2>{$site_name} Archives</h2>
<div class="colWrap">
<div class="bigCol">

<h3>Volume {$issue_volume}, Issue {$issue_number}</h3>

<table>
<tr>
<th>Headline</th>
<th>Author</th>
<th>Section</th>
<th>Words</th>
</tr>
{section name="items" loop=$article_list}
<tr>
<td><a href="{$site_url}/article.php?id={$article_list[items].article_id}">{$article_list[items].article_title}</a></td>
<td>{$article_list[items].article_author}</td>
<td>{$article_list[items].section_name}</td>
<td>{$article_list[items].article_word_count}</td>
</tr>
{/section}
</table>


<p>Select an issue to the right to begin browsing through the most-recent volume of the newspaper. If you are looking for an article from a previous year, use the "Volumes" list below it to see any article published. If you need any help, just <a href"mailto:{$site_email}">drop us an email</a>.</p>

</div>
<div class="smallCol">

<h3>Volume {$issue_volume}</h3>
<table>
<tr>
<th>Issue</th>
<th>Date</th>
</tr>
{section name="items" loop=$issue_list}
<tr>
<td>No. {$issue_list[items].issue_number}</td>
<td><a href="{$site_url}/archives.php?issue={$issue_list[items].issue_date}">{$issue_list[items].issue_date|date_format:"%B %e, %Y"}</a></td>
</tr>
{/section}
</table>

<h3>Volumes in our Internet Archives</h3>
<ul>
{section name="items" loop=$volume_list}
<li><a href="{$site_url}/archives.php?volume={$volume_list[items].volume}">Volume {$volume_list[items].volume}</a> ({$volume_list[items].issue_count})</li>
{/section}
</ul>
</div>
</div>
</div>

{include file="footer.tpl"}