{include file="header.tpl"}
<div id="content">
<h2 class="sectionNameplate">Write for <em>{$site_name}</em></h2>
{if $smarty.request.msg eq "submitted"}<p class="systemMessage">Your submission has been sent to our editors.</p>{/if}
{if $smarty.request.msg eq "missing"}<p class="systemMessage">You must complete the required fields.</p>{/if}
{if $smarty.request.msg eq "failed"}<p class="systemMessage">Submission failed for some reason. Please try again.</p>{/if}
{if $smarty.request.msg eq "disabled"}<p class="systemMessage">Content submissions are disabled at this time.</p>{/if}

    <form action="{$SCRIPT_NAME}" method="post" name="sendit">
	<div class="colWrap">
    <div class="smallerCol">
      <h3>Step 1: Submission Type</h3>
      <p>
        <label for="keyword">Choose your submission type</label>
        <br />
        <select name="keyword" id="keyword" onChange="MM_jumpMenu('parent',this,0)" style="width: 250px;">
          <option value="{$site_url}/submit.php?mode=letter" {if $smarty.request.mode eq "letter"}selected{/if} >Letter to the Editor</option>
          <option value="{$site_url}/submit.php?mode=article" {if $smarty.request.mode eq "article"}selected{/if} >Article or Press Release</option>
          <option value="{$site_url}/submit.php?mode=bboard" {if $smarty.request.mode eq "bboard"}selected{/if} >Bulletin Board Item</option>
        </select>
      </p>
      {if $smarty.request.mode eq "letter"}
      <p class="finePrint"><strong>Submission Guidelines:</strong> Letters and columns will be edited for length and brevity. Letters must be between 100 - 200 words and include your name, hometown, classification and major. Columns may be between 300-700 words in length. We reccomend using a word processing application for basic spell check, as well as to save your original copy.</p>
      {/if}
      {if $smarty.request.mode eq "article"}
      <p class="finePrint"><strong>Submission Guidelines:</strong> Volunteer staff writers are responsible for the majority of the stories printed in <em>{$site_name}</em>. All submissions will be edited for content and length. <em>{$site_name}</em> makes ever attempt to conform to Associated Press Style, but with notable exceptions. Visit our <a href="{$site_url}/stylebook/">Online Stylebook</a> for further guidance. Submission does not guarantee publication.</p>
      {/if}
      {if $smarty.request.mode eq "bboard"}
      <p class="finePrint"><strong>Submission Guidelines:</strong> Announce your club meetings or events in <em>{$site_name}</em>'s Campus Bulletin Board. Send the time, date and location of your event along with contact information for the person in charge of the event. Submissions must be sent at least a week in advance of the event date. We will continue to run the notice until the event has passed.</p>
      {/if}
      <h3>Step 2: Contact Information</h3>
      <p>
        <label for="name">Your Name</label> <span style="font-weight:bold;color:red;">*</span>
        <br />
        <input type="text" id="name" name="name" style="width: 250px;" />
      </p>
      <p>
        <label for="email">E-mail</label> <span style="font-weight:bold;color:red;">*</span>
        <br />
        <input type="text" id="email" name="email" style="width: 250px;" />
      </p>
      <p>
        <label for="class">Classification</label> <span style="font-weight:bold;color:red;">*</span>
        <br />
        <select id="class" name="class" style="width: 250px;">
          <option value="(Not selected)">Please select ...</option>
          <option value="(Not selected)">---</option>
          <option value="Freshman">Freshman</option>
          <option value="Sophomore">Sophomore</option>
          <option value="Junior">Junior</option>
          <option value="Senior">Senior</option>
          <option value="(Not selected)">---</option>
          <option value="Faculty or Staff">Faculty / Staff</option>
          <option value="Community Member">Community Member</option>
          <option value="Alumnus / Alumnae">Alumnus / Alumnae</option>
        </select>
      </p>
      <p>
        <label for="major">Major / Department</label>
        <br />
        <input type="text" id="major" name="major" style="width: 250px;" />
      </p>
      <p>
        <label for="telephone">Telephone</label>
        <br />
        <input type="text" id="telephone" name="telephone" style="width: 250px;" />
      </p>
      <p>
        <label for="hometown">Hometown</label>
        <br />
        <input type="text" id="hometown" name="hometown" style="width: 250px;" />
      </p>
      </div>
      <div class="biggerCol">
      <h3>Step 3: Your Submission</h3>
      <p>
        <label for="title">Headline</label> <span style="font-weight:bold;color:red;">*</span>
        <br />
        <input type="text" id="title" name="title" style="width: 300px;" />
      </p>
      <p>
        <label for="text">Copy and paste from your word processor</label> <span style="font-weight:bold;color:red;">*</span>
        <br />
        <textarea name="text" id="text" rows="20" style="width: 300px;"></textarea>
      </p>
      {if $smarty.request.mode eq "letter"}
      <p style="border-top: solid 1px #ccc; border-bottom: solid 1px #ccc; padding: 5%;">
        <label>Word Count:</label>
        <br />
        <input type="text" name="wordCount" id="wordCount" readonly="true" style="width: 100px;" />
        <input type="button" name="doCount" id="doCount" value="Count Words" onClick="countit()" />
      </p>
      {/if}
      <p><span style="font-weight:bold;color:red;">*</span> - <em>Denotes required information</em>.</p>
      <p>
        <input type="submit" id="submit" value="Send Your Submission" />
        <input type="reset" id="reset" value="Reset" />
      </p>

  </div>
</div>
    </form>
</div>
{include file="footer.tpl"}