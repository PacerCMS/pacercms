{include file='header.tpl'}

<div id="content">
<div class="colWrap">
    <form action="{$SCRIPT_NAME}" method="get">
        <div class="biggerCol">
            <h3>Search <em>{$site_name}</em> Archives</h3>
            <p>
                <label for="s">Search for:</label>
                <input type="text" name="s" value="{$search_string|escape:'html'}"/>
            </p>
            <p>
                <input type="submit" name="search" value="Search" />
                <input type="reset" name="reset" value="Reset" />
            </p>
        </div>
        <div class="smallerCol">
            <p>
            <label for="index">Search within:</label>
                <select name="index" id="index">
                <option value="article">Article &amp; Headlines</option>
                <option value="author">Authors</option>
                <option value="keyword">Keywords</option>
            </select>
            </p>
            <p>
            <label for="sort_by">Sort by:</label>
            <select name="sort_by" id="sort_by">
                <option value="article_publish">Publish Date</option>
                <option value="article_title">Headline</option>
                <option value="article_subtitle">Sub-Headline</option>
                <option value="article_author">Author Name</option>
                <option value="article_word_count">Word Count</option>
            </select>
            <select name="sort_dir" id="sort_dir">
                <option value="DESC">Descending</option>
                <option value="ASC">Ascending</option>
            </select>
            </p>
        </div>
    </form>
</div>
<div class="colWrap">
    <div class="fullCol">
    
        <table>
            <tr>
                <th>Headline</th>
                <th>Publish Date</th>
                <th>Author</th>
                <th>Section</th>
                <th>Words</th>
            </tr>
            {section name="items" loop=$article_list}
            <tr>
                <td><a href="{$site_url}/article.php?id={$article_list[items].article_id}">{$article_list[items].article_title}</a></td>
                <td>{$article_list[items].article_publish|date_format:"%m/%e/%Y"}</td>
                <td>{$article_list[items].article_author|escape:'html'}</td>
                <td>{$article_list[items].section_name|escape:'html'}</td>
                <td>{$article_list[items].article_word_count}</td>
            </tr>
            {sectionelse}
            <tr>
                <td colspan="5">Your search did not return any articles.</td>
            </tr>
        {/section}
        </table>
    
    </div>
</div>
</div>

{include file='footer.tpl'}