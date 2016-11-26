<?php
require_once("../web/config.php");

if (isset($_SESSION["session"])) {
  $_SESSION["expire"] = time() + (30 * 60); // 30 mn plus tard
  $s->suppressionCookies();
  $s->head("Modification de mon mot de passe");
  $s->header($db);
  $s->nav($db);
?>
	<article>
		<div class="col-sm-8 text-left">
			<h2>Modification de mon mot de passe</h2>
			<hr>

			<div name="changemdp" id="changemdp">
					<form class="form-horizontal" accept-charset="utf-8" method="post" action="">
						<div class="form-group">
							<div class="col-md-8">
								<label>Mot de passe actuel <input type="password" name="amdp" placeholder="" required /></label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-8">
								<label>Nouveau mot de passe <input type="password" name="nmdp" placeholder="" required /></label>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-8">
								<label>Confirmation du mot de passe <input type="password" name="vmdp" placeholder="" required /></label>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-offset-0 col-md-8"><input class="btn btn-primary" type="submit" name="submit" value="Mettre à jour le mot de passe"/></div>
						</div>
					</form>
					
	<?php
			$result=false;
			if(isset($_POST['submit'])){
				$amdp=$_POST['amdp'];
				$nmdp=$_POST['nmdp'];
				$vmdp=$_POST['vmdp'];

				if (($amdp!='')&&($nmdp!='')&&($vmdp!='')){
						if ($amdp==$_SESSION['mdp_co']){
								if($nmdp==$vmdp){
									if ($db->modifDeveloppeurMDP($_SESSION['id_co'], $nmdp)){
										echo 'Modification du mot de passe effectuée avec succès !';
										$_SESSION['mdp_co']=$nmdp;
									} else{
										echo 'La modification de votre mot de passe a échoué. 
										Veuillez contacter l\'équipe de maintenance.'; 
									}
								} else {
										echo 'Attention, erreur de correspondance entre le nouveau mot de passe entré et la vérification. <br>
										Veuillez recommencer la démarche.';
								}
						} else {
								echo 'Attention, le mot de passe actuel n\'est pas valide.';
						}
				} else {
						echo 'Veuillez remplir tous les champs !';
				}
			} else {
					echo '';
			}           
	?>
		</div>

<?php
  $s->footer();
} else {
  header("Location: index.php");
  exit();
}
?>