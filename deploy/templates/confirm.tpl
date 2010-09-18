<h1>Account Confirmation</h1>

<div style="border: 1px solid #000000;font-weight: bold;">

{if $confirmed eq 1}
  That player username ({$username|escape}) is already confirmed in our system.
  <br><br>Please log in on the main page or contact <a href='staff.php'>the game administrators</a> if you have further issues.
{else if $confirmation_confirmed}
  Confirmation Successful
  <p>You may now log in from the main page.</p>
{else}
  <p>
    This account can not be verified or the account was deactivated.  
    Please contact {$templatelite.const.SUPPORT_EMAIL|escape} if you require more information.
  </p>
{/if}
  <div><a target='main' href="tutorial.php">Return to Main?</a></div>
</div>