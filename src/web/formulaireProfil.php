<?php
require_once("config.php");

if (isset($_SESSION["session"])) {
  $s->suppressionCookies();
  $s->head("Mon profil - Édition");
  $s->header($db);
  $s->nav($db);

  $erreurDev = false;
  if (isset($_GET["erreur"])) {
    if (!strcmp($_GET["erreur"], "dev")) {
      $erreurDev = true;
      echo "Erreur : le developpeur n'a pas été créé";
    }
  }
  $erreurPseudo = false;
  if (isset($_GET["erreur"])) {
    if (!strcmp($_GET["erreur"], "pseudo")) {
      $erreurPseudo = true;
    }
  }
  $prenom = "";
  if (isset($_GET["prenom"])) {
    $prenom = $_GET["prenom"];
  } else {
    $prenom = $_SESSION["prenom_co"];	
	}
  $nom = "";
  if (isset($_GET["nom"])) {
    $nom = $_GET["nom"];
  } else {
    $nom = $_SESSION["nom_co"];	
	}
/*  
	$pseudo = "";
  if (isset($_GET["pseudo"])) {
    $pseudo = $_GET["pseudo"];
  }	else {
    $pseudo = $_SESSION["pseudo_co"];	
	}
*/
?>
	<script>
		function verifMail() {
			a = document.Co.mail.value;
			valide1 = false;
			for (var j = 1; j < (a.length); j++) {
				if (a.charAt(j) == '@') {
					if (j < (a.length - 4)) {
						for (var k = j; k < (a.length - 2); k ++) {
							if (a.charAt(k) == '.')
								valide1 = true;
						}
					}
				}
			}
			if(valide1 == false)
				header("Location: formulaireProfil.php?erreur=mail2");
			return valide1;
		}
		function VerifFormulaire() {
			return verifMail();
		}
	</script>
	
	<article>
		<div class="col-sm-8 text-left">
			<h2>Édition de mon profil</h2>
			<hr>
<?php
			if ($erreurDev) {
				echo "Erreur : le développeur n'a pas été trouvé.";
			}
?>
			<form class="form-horizontal" action="modificationDeveloppeur.php" method="post">
				<!--onsubmit="VerifFormulaire()"-->
				<div class="form-group">
					<div class="col-md-offset-0 col-md-8">
						<label>Prénom <input class="form-control" type="text" name="prenom" maxlength="255" placeholder="Prénom" value="<?php echo $prenom; ?>"/></label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-0 col-md-8">
						<label>Nom <input class="form-control" type="text" name="nom" maxlength="255" placeholder="Nom" value="<?php echo $nom; ?>"/></label>
					</div>
				</div>
				<!--<div <?php/* if($erreurPseudo) echo 'class="form-group has-error"'; else echo 'class="form-group"'; */?> 
					<div class="col-md-offset-0 col-md-8">
						<label>Pseudo <input class="form-control" type="text" name="pseudo" maxlength="20" placeholder="Pseudo" value="<?php/* echo $pseudo; */?>"/></label>
					</div>
<?php/*
  if ($erreurPseudo) echo "Erreur : le pseudo existe déjà";
*/?>
				</div>-->
				<div class="form-group">
					<div class="col-md-offset-0 col-md-8">
						<label>Chemin d'accès de votre image d'avatar <input class="form-control" type="text" name="url" maxlength="500" placeholder="URL image avatar"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-0 col-md-8">
						<input class="btn btn-primary" type="submit" value="Mettre à jour">
					</div>
				</div>
			</form>
		</div>
	</article>
<?php
  $s->footer();
} else {
  header("Location: index.php");
  exit();
}
?>