{include file="header.tpl"}
<div id="content">
<h2 class="sectionNameplate">{t}Feedback{/t}</h2>
{if $status_message eq "submitted"}
<p class="systemMessage">{t}Your submission has been sent to our editors.{/t}</p>
{/if}
{if $status_message eq "missing"}
<p class="systemMessage">{t}You must complete the required fields.{/t}</p>
{/if}
{if $status_message eq "failed"}
<p class="systemMessage">{t}Submission failed for some reason. Please try again.{/t}</p>
{/if}
{if $status_message eq "disabled"}
<p class="systemMessage">{t}Content submissions are disabled at this time.{/t}</p>
{/if}

<form action="{$script_name}" method="post" name="sendit">
    <div class="colWrap">
        <div class="leftCol">
            <h3>{t}Mailing Address{/t}</h3>
            <p>
                <strong><em>{$site_name}</em></strong><br />
                {$site_address}<br />
                {$site_city}, {$site_state} {$site_zipcode}
            </p>
            
            <h3>{t}Contacting Us{/t}</h3>
            <p>
                <strong>{t}Telephone:{/t}</strong> {$site_telephone}<br />
                <strong>{t}Fax:{/t}</strong> {$site_fax}
            </p>
            
            <p>
                <strong>{t}Primary E-mail:{/t}</strong> <a href="mailto:{$site_email}">{$site_email}</a>
            </p>
            
            <hr />
            <h3>{t}Before you complete this form{/t}</h3>
            <p>We receive hundreds of e-mails every day. Unfortunately, this means we cannot guarantee individual response. Take a look at the links below to make sure your question is not answered elsewhere on the Web site.</p>
            <ul>
                <li><a href="{$site_url}/submit.php?mode=letter">{t}Letter to the Editor{/t}</a></li>
                <li><a href="{$site_url}/submit.php?mode=article">{t}Story or Press Release{/t}</a></li>
                <li><a href="{$site_url}/submit.php?mode=bboard">{t}Bulletin Board Item{/t}</a></li>
            </ul>
        </div>
        <div class="rightCol">
            <h3>{t}Step 1: Contact Information{/t}</h3>
            <p>
                <label for="name">{t}Your Name{/t}</label> <span style="font-weight:bold;color:red;">*</span><br />
                <input type="text" id="name" name="name" style="width: 250px;" value="{$feedback_name}" />
            </p>
            <p>
                <label for="email">{t}E-mail{/t}</label> <span style="font-weight:bold;color:red;">*</span><br />
                <input type="text" id="email" name="email" style="width: 250px;" value="{$feedback_email}" />
            </p>
            <h3>Step 2: Sound Off</h3>
            <p>
                <label for="text">{t}Your Feedback{/t}</label> <span style="font-weight:bold;color:red;">*</span><br />
                <textarea name="text" id="text" rows="20" style="width: 300px;">{$feedback_text}</textarea>
            </p>
                <p><span style="font-weight:bold;color:red;">*</span> - <em>{t}Denotes required information{/t}</em>.</p>
            <p>
                <input type="submit" id="submit" value="{t}Submit Feedback{/t}" /> <input type="reset" id="reset" value="{t}Reset{/t}" />
            </p>
        </div>
    </div>
</form>

</div>
{include file="footer.tpl"}