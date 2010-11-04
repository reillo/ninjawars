<div id='signup-process'>
  Your responses:<br>
  Name - {$send_name|escape}<br>
  Password - {if $send_pass}***yourpassword***{else}NO PASSWORD{/if}<br>
  Class - {$class_display|escape}<br>
  Email - {$send_email|escape}<br>
  Site Referred By - {$referred_by|escape}<br><br>
{if isset($completedPhase)}
	{if $completedPhase gte 1}
  Phase 1 Complete: Name passes requirements.<hr>
	{/if}
	{if $completedPhase gte 2}
  Phase 2 Complete: Password passes requirements.<hr>
	{/if}
	{if $completedPhase gte 3}
  Phase 3 Complete: Username and Email are unique.<br><hr>
	{/if}
	{if $completedPhase gte 4}
  Phase 4 Complete: Class was specified.<br><hr>
	{/if}

	{if $success}
		{if $confirmed}
  <p>Account with the login name "{$send_name|escape}" is now confirmed! Please login on the login bar of the ninjawars.net page.</p>
		{else}
  Phase 5: When you receive an email from SysMsg, it will describe how to activate your account.<br><br>
  Confirmation email has been sent to <b>{$send_email|escape}</b>.<br>
  Be sure to also check for the email in any "Junk Mail" or "Spam" folders.
  Delivery typically takes less than 15 minutes.
		{/if}
	{/if}

	{if $error}
  <p>{$error|escape}</p>
	{else}
  <p>Only one account per person is allowed.</p>
	{/if}
  If you need help use the forums at
  <a href="{$templatelite.const.WEB_ROOT}forum/">{$templatelite.const.WEB_ROOT}forum/</a>
  or email: <a href="mailto:{$templatelite.const.SUPPORT_EMAIL}">{$templatelite.const.SUPPORT_EMAIL}</a>
</div>
{/if}
