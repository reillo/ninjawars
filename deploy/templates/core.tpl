	  <!-- Main column template section -->
      <section id='main-column'>

			<!-- THE MAIN CONTENT DISPLAY SECTION -->
            <iframe frameBorder='0' id="main" name="main" class="main-iframe" src="{$main_src|escape}">
            <!-- Note the the frameBorder attribute is apparently case sensitive in some versions of ie -->
              <a href='{$main_src|escape}' target='_blank'>Main Content</a> changes unavailable inside this browser window.
              {include file="main.tpl"}
            </iframe>
          
      </section> <!-- End of main-column -->
