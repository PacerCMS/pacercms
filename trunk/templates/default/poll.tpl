{include file="header.tpl"}

<div id="content">
{if $poll_question ne ''}
    <h2 class="sectionNameplate">Poll Results</h2>
    {if $smarty.request.msg eq 'counted'}<p class="systemMessage">Your vote was counted.</p>{/if}
    {if $smarty.request.msg eq 'error'}<p class="systemMessage">Something was wrong with your ballot.</p>{/if}
    <div id="pollResults">
        
        <h3>{$poll_question}</h3>
        
        {if $poll_votes != 0}
        <table>
            <tr>
                <th>Poll Response</th>
                <th>Result</th>
            </tr>   
            {if $poll_r1 ne ''}
            <tr>
                <td>{$poll_r1|escape:'html'}</td>
                <td><strong>{$poll_v1}</strong> ({math equation="(x/y)*100" x=$poll_v1 y=$poll_votes format="%.2f"}%)</td>
            </tr>
            {/if}
                {if $poll_r2 ne ''}
            <tr>
                <td>{$poll_r2|escape:'html'}</td>
                <td><strong>{$poll_v2}</strong> ({math equation="(x/y)*100" x=$poll_v2 y=$poll_votes format="%.2f"}%)</td>
            </tr>
            {/if}
            {if $poll_r3 ne ''}
            <tr>
                <td>{$poll_r3|escape:'html'}</td>
                <td><strong>{$poll_v3}</strong> ({math equation="(x/y)*100" x=$poll_v3 y=$poll_votes format="%.2f"}%)</td>
            </tr>
            {/if}
            {if $poll_r4 ne ''}
            <tr>
                <td>{$poll_r4|escape:'html'}</td>
                <td><strong>{$poll_v4}</strong> ({math equation="(x/y)*100" x=$poll_v4 y=$poll_votes format="%.2f"}%)</td>
            </tr>
            {/if}
            {if $poll_r5 ne ''}
            <tr>
                <td>{$poll_r5|escape:'html'}</td>
                <td><strong>{$poll_v5}</strong> ({math equation="(x/y)*100" x=$poll_v5 y=$poll_votes format="%.2f"}%)</td>
            </tr>
            {/if}
            <tr>
                <td><strong>Total Votes:</strong></td>
                <td>{$poll_votes}</td>
            </tr>
        </table>
        {else}
        <h4>No one has participated in this Web poll yet.</h4>
        <p>Please go back to the home page to cast your ballot</p>
        {/if}
        {if $related_article_id ne ''}
        <p><strong>Related Article:</strong> <a href="{$site_url}/article.php?id={$related_article_id}">{$related_article_title|escape:'html'}</a></p>
        {/if}
        <p>Our Web poll is not scientific and reflects the opinions of only those Internet users who have chosen to participate. The results cannot be assumed to represent the opinions of Internet users in general, the public as a whole, nor the students or faculty of our university.</p>
    
    </div>
{else}
<h2>Web polls are currently unavailable.</h2>
{/if}
</div>

{include file="footer.tpl"}