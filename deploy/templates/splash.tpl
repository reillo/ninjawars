    <!-- Version {$version} -->

    <!-- Top horizontal bar -->
    <header id='index-header' class='clearfix'>
		<div id="logo-appended">
		  <a href="/">
	        <img id='ninjawars-title-image' src='images/halfShuriken.png' title='Home' alt='Ninja Wars' width='108' height='108'>
		  </a>
		</div>
		<div id='logo-placeholder'>
      &nbsp; <!-- Spacer div for the main shuriken linkback logo -->
		</div>
		<nav role='navigation' id='top-bar' class='navigation'>
		  <a id='solo-page-login-link' href='login.php' class='btn btn-vital'>Log in</a> <a id='solo-page-signup-link' href='signup.php' class='btn btn-vital'>Signup</a>
		</nav>
		<nav id='top-logo'>
      <h1>The Ninja Game at </h1>
      <h1 class='title-box'><a href='main.php' target='main'><img src='images/nw_bamboo_logo_soft.png' alt='NinjaWars' width='200' height='100'></a>.net</h1>
		</nav>
      
    <nav role='navigation' id='subcategory-bar' class='navigation'>
      <ul id='ninjas-subcategory'>
        <li><a href="list.php" target="main">Ninjas</a></li>
        <li><a href="clan.php" target="main">Clans</a></li>
      </ul>
      <ul id='map-subcategory'>
      	<li><a href='map.php' id='menu-map-head' target='main' title='Travel to different locations on the map'>Map&rarr;</a></li>
        <li><a href="shop.php" target="main">Shop</a></li>
        <li><a href="work.php" target="main">Field</a></li>
        <li>
          <a href="doshin_office.php" target="main">Doshin <img class='doshin-image' src="images/doshin.png" alt=""></a>
        </li>
      </ul>
    </nav> <!-- End of subcategory bar -->
      
	  </header><!-- End of header -->
      

      <div id='core' class='clearfix'>
      <!-- MAIN COLUMN STARTS HERE -->
		{include file="core.tpl"}    
      
      <aside id='sidebar-column'  class='navigation'>
		<div id='contact-us' class='thick'>
		  <a href='staff.php' target='main' class='font-shangrila'>Contact Staff</a>
		</div>

		<div id='feedback-link'>
		  <a class='font-shangrila extLink' rel='nofollow' href="http://ninjawars.proboards.com/index.cgi?action=display&amp;board=suggcomp&amp;thread=1174" target="_blank">Give Feedback</a>
		</div>

  {if isset($show_news) and $show_news}
      <div id='news-housing'>
        
{include file="mini-news.section.tpl"}

    </div><!-- End of news-housing -->
  {/if}
        
      <div id='chat-housing'>
        
{include file="mini-chat.section.tpl"}

	  </div><!-- End of chat-housing -->


      </aside><!-- End of aside -->
      
      </div><!-- End of core -->

      <!-- <div id='push'></div> -->
      <footer id='index-footer'  class='navigation'>
      
            <!-- Stuff like catchphrases, links, and the author information -->
      {include file='linkbar_section.tpl'}

        
      </footer>
      
      
<!-- Validated as of Oct, 2009 -->

<!-- Version: {$version} -->

{literal}
<script>
if (top.location != location) { // Framebreak on the splash page to prevent any issues.
  top.location.href = document.location.href ;
}
</script>
{/literal}