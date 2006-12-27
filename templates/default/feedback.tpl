{include file="header.tpl"}
<div id="content">
<h2 class="sectionNameplate">Newspaper &amp; Web Site Feedback</h2>
{if $status_message eq "submitted"}
<p class="systemMessage">Your submission has been sent to our editors.</p>
{/if}
{if $status_message eq "missing"}
<p class="systemMessage">You must complete the required fields.</p>
{/if}
{if $status_message eq "failed"}
<p class="systemMessage">Submission failed for some reason. Please try again.</p>
{/if}
{if $status_message eq "disabled"}
<p class="systemMessage">Content submissions are disabled at this time.</p>
{/if}

<form action="{$script_name}" method="post" name="sendit">
    <div class="colWrap">
        <div class="leftCol">
            <h3>Mailing Address</h3>
            <p><strong>{$site_name}<br />
            {$site_address}<br />
            {$site_city}, {$site_state} {$site_zipcode}</strong></p>
            <h3>Contacting Us</h3>
            <p><strong>Telephone: {$site_telephone}<br />Fax: {$site_fax}</strong></p>
            <p><strong>Primary E-mail: <a href="mailto:{$site_email}">{$site_email}</a></strong></p>
            <hr />
            <h3>Before you complete this form ...</h3>
            <p>We receive hundreds of e-mails every day. Unfortunately, this means we cannot guarantee individual response. Take a look at the links below to make sure your question is not answered elsewhere on the Web site.</p>
            <ul>
                <li>Send a <a href="{$site_url}/submit.php?mode=letter">''Letter to the Editor''</a>.</li>
                <li>Send a <a href="{$site_url}/submit.php?mode=article">story or press release</a>.</li>
                <li>Send a <a href="{$site_url}/submit.php?mode=bboard">''Bulletin Board'' item</a>.</li>
            </ul>
        </div>
        <div class="rightCol">
            <h3>Step 1: Contact Information</h3>
            <p>
                <label for="name">Your Name</label> <span style="font-weight:bold;color:red;">*</span><br />
                <input type="text" id="name" name="name" style="width: 250px;" value="{$feedback_name}" />
            </p>
            <p>
                <label for="email">E-mail</label> <span style="font-weight:bold;color:red;">*</span><br />
                <input type="text" id="email" name="email" style="width: 250px;" value="{$feedback_email}" />
            </p>
            <h3>Step 2: Sound Off</h3>
            <p>
                <label for="text">Your Feedback</label> <span style="font-weight:bold;color:red;">*</span><br />
                <textarea name="text" id="text" rows="20" style="width: 300px;">{$feedback_text}</textarea>
            </p>
                <p><span style="font-weight:bold;color:red;">*</span> - <em>Denotes required information</em>.</p>
            <p>
                <input type="submit" id="submit" value="Submit Feedback" /> <input type="reset" id="reset" value="Reset" />
            </p>
        </div>
    </div>
</form>

</div>
{include file="footer.tpl"}