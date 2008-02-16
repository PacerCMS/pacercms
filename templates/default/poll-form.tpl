{if $poll_question ne ''}
<form action="poll.php" method="post" class="sitePoll">
<h4>&mdash; {t}Web Poll{/t} &mdash;</h4>
<p class="question"><strong>{$poll_question|escape:'html'}</strong></p>
<ul>

{if $poll_r1 ne ''}
<li>
    <input name="vote-cast" type="radio" value="1" id="option-1" />
    <label for="option-1"> {$poll_r1|escape:'html'} </label>
</li>
{/if}

{if $poll_r2 ne ''}
<li>
    <input name="vote-cast" type="radio" value="2" id="option-2" />
    <label for="option-2"> {$poll_r2|escape:'html'} </label>
</li>
{/if}

{if $poll_r3 ne ''}
<li>
    <input name="vote-cast" type="radio" value="3" id="option-3" />
    <label for="option-3"> {$poll_r3|escape:'html'} </label>
</li>
{/if}

{if $poll_r4 ne ''}
<li>
    <input name="vote-cast" type="radio" value="4" id="option-4" />
    <label for="option-4"> {$poll_r4|escape:'html'} </label>
</li>
{/if}

{if $poll_r5 ne ''}
<li>
    <input name="vote-cast" type="radio" value="5" id="option-5" />
    <label for="option-5"> {$poll_r5|escape:'html'} </label>
</li>
{/if}

</ul>
<p class="submit">
<input type="submit" id="submit" value="{t}Cast Vote{/t}" class="button" />
</p>
</form>
{/if}