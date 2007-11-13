{include file="header.tpl"}

<div id="content">
    <h2 class="sectionNameplate">E-mail Article</h2>    
    {if $smarty.request.msg eq "sent"}<p class="systemMessage">Article was e-mailed.</p>{/if}
    {if $smarty.request.msg eq "fail"}<p class="systemMessage">Mailing failed. Check your recipient's e-mail address.</p>{/if}
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
                <legend>Required Information</legend>
                <h4>Recipient Information</h4>
                <p>
                    <label for="recipient-email">E-mail Address</label><br />
                    <input type="text" name="recipient-email" id="recipient-email" />
                </p>
                <h4>Your Information</h4>
                <p>
                    <label for="sender">Name</label><br />
                    <input type="text" name="sender" id="sender" />
                </p>
                <p>
                    <label for="sender">E-mail Address</label><br />
                    <input type="text" name="sender-email" id="sender-email" />
                </p>
                <p>
                    <input id="submit" type="submit" value="Send" />
                    <input id="reset" type="reset" value="Reset" />
                </p>
            </fieldset>
        </form>
        </div>
    </div>
</div>

{include file="footer.tpl"}