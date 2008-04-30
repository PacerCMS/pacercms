{include file="header.tpl"}

<div id="content">
    <h2 class="sectionNameplate">{t}E-mail Article{/t}</h2>    
    {if $smarty.request.msg eq "sent"}<p class="systemMessage">{t}Article was e-mailed.{/t}</p>{/if}
    {if $smarty.request.msg eq "fail"}<p class="systemMessage">{t}Mailing failed. Check your recipient's e-mail address.{/t}</p>{/if}
    <div class="colWrap">
        <div class="bigCol">
            <h3>{$site_name} - {$article_title}</h3> 
            <div style="padding:10px;font-family:courier,fixed-width;border:solid 1px #ccc;">
            {$preview_email}
            </div>
        </div>
        <div class="smallCol">
        <form action="send.php?id={$article_id}" method="post">
            <fieldset>
                <legend>{t}Required Information{/t}</legend>
                <h4>{t}Recipient Information{/t}</h4>
                <p>
                    <label for="recipient-email">{t}E-mail Address{/t}</label><br />
                    <input type="text" name="recipient-email" id="recipient-email" />
                </p>
                <h4>{t}Your Information{/t}</h4>
                <p>
                    <label for="sender">{t}Name{/t}</label><br />
                    <input type="text" name="sender" id="sender" />
                </p>
                <p>
                    <label for="sender">{t}E-mail Address{/t}</label><br />
                    <input type="text" name="sender-email" id="sender-email" />
                </p>
                <p>
                    <input id="submit" type="submit" value="{t}Send{/t}" />
                    <input id="reset" type="reset" value="{t}Reset{/t}" />
                </p>
            </fieldset>
        </form>
        </div>
    </div>
</div>

{include file="footer.tpl"}