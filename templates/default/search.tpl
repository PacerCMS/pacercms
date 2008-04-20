{include file='header.tpl'}

<div id="content">
<div class="colWrap">
    <form action="{$SCRIPT_NAME}" method="get">
        <div class="biggerCol">
            <h3>Search <em>{$site_name}</em> {t}Archives{/t}</h3>
            <p>
                <label for="s">{t}Search for:{/t}</label>
                <input type="text" name="s" value="{$search_string|escape:'html'}"/>
            </p>
            <p>
                <input type="submit" name="search" value="{t}Search{/t}" />
                <input type="reset" name="reset" value="{t}Reset{/t}" />
            </p>
        </div>
        <div class="smallerCol">
            <p>
            <label for="index">{t}Search within:{/t}</label>
            <select name="index" id="index">
                {html_options values=$s_index_values output=$s_index_names selected=$s_index_select}
            </select>
            </p>
            <p>
            <label for="sort_by">{t}Sort by:{/t}</label>
            <select name="sort_by" id="sort_by">
                {html_options values=$s_sort_by_values output=$s_sort_by_names selected=$s_sort_by_select}
            </select>
            <select name="sort_dir" id="sort_dir">
                {html_options values=$s_sort_dir_values output=$s_sort_dir_names selected=$s_sort_dir_select}
            </select>
            </p>
        </div>
    </form>
</div>
<div class="colWrap">
    <div class="fullCol">
    
        <table>
            <tr>
                <th>{t}Headline{/t}</th>
                <th>{t}Publish Date{/t}</th>
                <th>{t}Author{/t}</th>
                <th>{t}Section{/t}</th>
                <th>{t}Words{/t}</th>
            </tr>
            {section name="items" loop=$article_list}
            <tr>
                <td><a href="{article_link article=$article_list[items]}">{$article_list[items].article_title}</a></td>
                <td>{$article_list[items].article_publish|date_format:"%m/%e/%Y"}</td>
                <td>{$article_list[items].article_author|escape:'html'}</td>
                <td>{$article_list[items].section_name|escape:'html'}</td>
                <td>{$article_list[items].article_word_count}</td>
            </tr>
            {sectionelse}
            <tr>
                <td colspan="5">{t}Your search did not return any articles.{/t}</td>
            </tr>
        {/section}
        </table>
    
    </div>
</div>
</div>

{include file='footer.tpl'}