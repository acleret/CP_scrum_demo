<?php
require_once("config.php");

$s->head("Conduite de projet");
$s->header($db);
$s->nav($db);
?>
	<article>
		<div class="col-sm-8 text-left">
			<h2>CdP : l'outil web qui facilitera la conduite de vos projets !</h2>
<?php if (isset($_SESSION["session"])) { ?>
			<p><strong>Vous êtes déjà très nombreux à avoir inscrit vos projets !</strong></p>
<?php } else { ?>
			<p><strong>Inscrivez-vous... Inscrivez votre projet... Vous en comprendrez vite les intérêts !</strong></p>
<?php } ?>
		</div>
	</article>

<?php
  $s->footer();
  exit();
?>