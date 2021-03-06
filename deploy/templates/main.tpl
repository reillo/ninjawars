<style type='text/css'>
{literal}
#faqs {
  margin: .5em auto 1em;
  padding: .2em;
  width: 90%;
}
#faqs p{
  border: 1px solid #7BA9AD;
  border-bottom-left-radius: 10px 10px;
  border-bottom-right-radius: 10px 10px;
  border-top-left-radius: 10px 10px;
  border-top-right-radius: 10px 10px;
  padding: 1em;
}
#faqs .notice{
  font-style:italic;
}
#faqs .brownHeading{
  font-variant:small-caps;
}
#progression {
  text-align:center;
  margin: 3em 0 0.5em 0;
  font-size:1.7em;
  font-family:"Trebuchet MS",Arial,Helvetica,sans-serif;
  /*font-family: Impact, sans-serif;*/
  /*font-variant: small-caps;*/
}
#progression a {
  font-family:"Trebuchet MS",Arial,Helvetica,sans-serif;
}
#later-progression a{
    color:whitesmoke;
}
#progression .down-arrow {
  height:35px;margin-top:0.5em;
}
#progression p {
  margin: 0;
}
#progression p:first-child {
  font-size:larger;
}
#join-link{
    color:#66CCFF;
    font-size:1.5em;
    text-shadow:#c00 2px 2px 2px;
}
#join-link:hover{
    color:brown;
    font-size:1.6em;
}
.accent-sandwiched{
  display:inline-block;margin: .4em auto .3em;text-align:center;font-size:1.1em;font-style:italic;
  padding-left:2em;padding-right:2em;
  border-top:1px solid rgba(0, 129, 165, 0.4); border-bottom:1px solid rgba(0, 129, 165, 0.4);
}
.accent-sandwiched a{
  display:inline-block;width:100%;height:100%
}
/* Don't display the h1 when housed within the iframe */
#main-page-headings{
  display:none;
}
.solo-page #main-page-headings{
  display:inherit;
}
{/literal}
</style>

<div id='main-page-headings'>

  <h1>The Ninja Game at Ninjawars.net</h1>
  <h2>Live by the Shuriken!</h2>
</div>





<div id='progression'>
{if !$user_id}
	<p><a target='main' href='{$smarty.const.WEB_ROOT}signup.php' id='join-link'>Become a Ninja!&shy;</a></p>
	<img class='down-arrow' src='images/Down_Arrow_Icon.png' alt='then'>
{/if}

<div id='later-progression' style='margin-top:0;margin-bottom:0'>
	<p>Explore the <a target='main' href='{$smarty.const.WEB_ROOT}map.php'>map</a> and <a target='main' href='{$smarty.const.WEB_ROOT}enemies.php'>attack monsters</a>, gather loot</p>
	<img class='down-arrow' src='images/Down_Arrow_Icon.png' alt='then'>
	<p>Kill other <a target='main' href='{$smarty.const.WEB_ROOT}list.php'>Ninja</a>, get stronger at the <a target='main' href='{$smarty.const.WEB_ROOT}dojo.php'>Dojo</a></p>
	<img class='down-arrow' src='images/Down_Arrow_Icon.png' alt='then'>
	<p>Join a <a target='main' href='{$smarty.const.WEB_ROOT}clan.php'>Clan</a>, wage war on other ninja clans</p>
	<img class='down-arrow' src='images/Down_Arrow_Icon.png' alt='then'>
	<p>Live by the Sword, and <a target='main' href='{$smarty.const.WEB_ROOT}shrine.php'>avoid death</a> if you can!</p>
	</div>
</div>




<div class='centered'>
  <div id='show-faqs' class='accent-sandwiched'>
    <a target='main' href="tutorial.php?show_faqs=1" id='show-faqs-link'>Show More Info</a>
  </div>
</div>


<div id='faqs'>
<div id='scrollable-viewport'>
  <p>
    <span class="brownHeading">How do I level up ?</span><br>
    By <a href='list_all_players.php'>killing other Ninja</a>.
    Once you have enough kill points, you may increase your level by visiting the <a href="dojo.php">Dojo</a> in the village, which will make you do more damage and make your abilities stronger.
  </p>

  <p>
    <span class="brownHeading">How do I attack another ninja?</span><br>
    You can attack another ninja by selecting <a href="enemies.php">fight</a> from the main page menu then putting a ninja's name into the search, or viewing the <a href="list_all_players.php">list of ninjas</a> on the "player list", then click their name and attack them from their profile page.
  </p>

  <p>
    <span class="brownHeading">I need turns, where can I get them?</span><br>
    You can <a href='inventory.php'>use amanita mushrooms</a> once you buy some from the <a href='shop.php'>shop</a> with gold, or wait for the half-hour and you will receive a few turns automatically (more if you have the <a href='skills.php'>"speed" skill</a>).
  </p>

  <p>
    <span class="brownHeading">I need gold, where can I get it?</span><br>
    You can get a percentage of gold from <a href='enemies.php'>NPCs</a>, <a href='enemies.php'>killing other ninja</a>, or <a href="work.php">Working</a> in fields on the <a href='map.php'>Map</a>, which will let you trade your time/turns for gold. Also, the <a href="doshin_office.php">Doshin Office</a> keeps a list of ninjas with bounties on their heads. Killing those ninja will get you the bounty as a reward.
  </p>
  
  <p>
    <span class="brownHeading">How do I attack an NPC?</span><br>
    Choose the <a href="enemies.php">Fight</a> link from the main page, then click an NPC's link.  Most NPCs only give items and gold, not kill points, with the exception of the Samurai, who is very difficult to kill.
  </p>

  <p>
    <span class="brownHeading">How do I use items?</span><br>
    There are two ways.  Either go to your own <a href="inventory.php">Inventory</a>, where you can use stealth scrolls and speed scrolls on you-rself, or go to another ninja's page from the ninja list and then click on the item to use it on them.
  </p>

  <p>
    <span class="brownHeading">How do I use my skills?</span><br>
    Different Ninjas have different <a href="skills.php">skills</a> based on your ninja color (red, white, blue, black, and gray).  Either click the <a href='skills.php'>Skills</a> link from the menu for any skills that you can use on yourself, or find an enemy ninja's profile page and click a skill to use it on them.
  </p>

  <p>
    <span class="brownHeading">How can I communicate with other players?</span><br>
    You can message players from their profile, send a message to all your clan members from the <a href='clan.php'>Clan</a> link if you are part of a clan, or post public chats to all players on the <a href='village.php'>full chat</a> board.  To check for messages sent directly to you, click the <a href='messages.php'>Messages</a> link on the main page.
  </p>

  <p>
    <span class="brownHeading">How do I join a clan?</span><br>
    Find a clan you want to join or a clanleader you want to follow and then click on the clan section, then Join a clan, and find the name of the clan or clanleader.  When you click on the clan leader's name to join their clan, a message will be sent to them to make sure that they want to let you in.
  </p>

  <p class='notice'>
    For various other info, see the <a target='_blank' class='extLink' href="http://ninjawars.pbworks.com">Wiki</a>
  </p>
 </div>
</div><!-- End of faqs div -->

<script type='text/javascript'>
var show_faqs = false; // Set faqs hidden by default.
{if isset($show_faqs) && $show_faqs}  // Set the template passed var
show_faqs = true;
{/if}
{literal}
var showfaqsLink = $('#show-faqs');
var faqsArea = $('#faqs');
if(!show_faqs){
  faqsArea.hide(); // To avoid flashing of content hide early on.
} else {
  showfaqsLink.hide(); // Otherwise, hide the show-hide link.
}
$(function () {
  showfaqsLink.click(function(event){
    faqsArea.slideToggle('slow');
    $(event.target).toggle();
    return false;
  });

  // Fade the link colors in gradually, one at a time.
  $('#later-progression a')
      .each(function(secs, element){
      setTimeout(function (){
          $(element).css({'color':'steelBlue'});
      }, 1000*(secs+1)*5);
  });
  // Finally, fade in the join link last.
  $('#join-link').each(function(index, element){
      setTimeout(function (){
          $(element).css({'color':'steelBlue', 'font-size':'1.5em'});
      }, 1000*26);
  });
});
{/literal}
</script>