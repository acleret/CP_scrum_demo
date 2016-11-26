<?php
require_once("../web/config.php");

$s->head("test bootstrap structure example");
$s->header($db);
$s->nav($db);
?>
          <article>
            <div class="col-sm-8 text-left">
              <h1>Welcome</h1>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
              </p>
              <hr>
              <h3>Test</h3>
              <p>Lorem ipsum...</p>
            </div>
          </article>
          <aside>
            <div class="col-sm-2 sidenav">
              <div class="well">
                <p>ADS</p>
              </div>
              <div class="well">
                <p>ADS</p>
              </div>
            </div>
          </aside>
<?php $s->footer();?>
