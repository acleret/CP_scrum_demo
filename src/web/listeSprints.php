<?php
require_once("../web/config.php");

if (isset($_COOKIE["id_projet"])) {
  $id_pro = $_COOKIE["id_projet"];
  $infos = $db->infosProjet($id_pro);
  $row = $infos->fetch_assoc();

  $liste_sprints = $db->listeSprints($id_pro);
  $row_sprints = $liste_sprints->fetch_assoc();

  if (isset($_POST["ajout_sprint"])) {
    $db->ajoutSprint($_POST["numero"], $_POST["dateDebut"], $_POST["duree"], $id_pro);
  }

  if (isset($_POST["suppr_sprint"])) {
    $db->supprimerSprint($_POST["suppr_sprint"]);
  }

  if (isset($_POST["modif_sprint"])) {
    $id_sprint_modif = $_POST["modif_sprint"];
    $infos_sprint_modif = $db->infosSprint($id_sprint_modif);
    $row_sprint_modif = $infos_sprint_modif->fetch_assoc();
    if($_POST["num_sprint"] == NULL) $modif_num = $row_sprint_modif["SPR_numero"]; else $modif_num = $_POST["num_sprint"];
    if($_POST["date_sprint"] == NULL) $modif_date = $row_sprint_modif["SPR_dateDebut"]; else $modif_date = $_POST["date_sprint"];
    if($_POST["duree_sprint"] == NULL) $modif_duree = $row_sprint_modif["SPR_duree"]; else $modif_duree = $_POST["duree_sprint"];
    $db->modifSprint($id_sprint_modif, $modif_num, $modif_date, $modif_duree);
  }

  $s->head($row['PRO_nom']);
  $s->header($db);
  $s->nav($db);
?>
          <script>
            $(document).ready(function() {
              $('#tableSprint').DataTable( {
                "order": [[ 0, "asc" ]],
                "oLanguage": {
                  "sLengthMenu": "Afficher _MENU_ entrées",
                  "sSearch": "<span class=\"glyphicon glyphicon-search\"></span> Recherche:",
                  "sEmptyTable": "Aucunes données",
                  "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                  "sInfoEmpty": "",
                  "oPaginate": {
                    "sPrevious": "Précédent",
                    "sNext": "Suivant"
                  }
                }
              } );
            } );
          </script>
          <article>
            <div class="col-sm-8 text-left">
              <h2><?php echo $row["PRO_nom"];?> - Sprints</h2>
              <hr>
              <dl class="dl-horizontal">
                <dt>Durée des sprints</dt>
                <dd>
<?php
  if ($row_sprints["SPR_duree"] == NULL)
    echo "-";
  else
    echo $row_sprints["SPR_duree"]." jours\n";
?>
                </dd>
              </dl>
              <hr>
              <table id="tableSprint" class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Numéro</th>
                    <th>Début</th>
                    <th>Kanban</th>
<?php
  if (isset($_SESSION["session"]) && $db->estMembreProjet($row["PRO_id"], $_SESSION["id_co"]))
      echo "              <th>Actions</th>\n" ;
?>
                  </tr>
                </thead>
                <tbody>
<?php
$liste_sprints = $db->listeSprints($id_pro);  // on réinitialiste liste_sprints
  while ($row_sprints = $liste_sprints->fetch_assoc()) {
?>
                  <tr>
                    <td>
                      <form style="display: inline;" action="../web/sprint.php" method="post">
<?php
    $id_spr = $row_sprints["SPR_id"];
    $infos_spr = $db->infosSprint($id_spr);
    $row_sprints = $infos_spr->fetch_assoc();
    $nom_spr = "Sprint#".$row_sprints["SPR_numero"];
?>
                        <input type="hidden" name="id_sprint" value="<?php echo $id_spr;?>"/>
                        <input type="hidden" name="nom_sprint" value="<?php echo $nom_spr;?>"/>
                        <input class="btn btn-link"  type="submit" value="<?php echo $nom_spr;?>"/>
                      </form>
                    </td>
                    <td><?php echo $db->ordonnerDate($row_sprints["SPR_dateDebut"]); ?></td>
                    <td>
                      <form style="display: inline;" action="kanban.php" method="post">
                        <input type="hidden" name="id_sprint" value="<?php echo $id_spr; ?>"/>
                        <input class="btn btn-link" type="submit" value="Kanban"/>
                      </form>
                    </td>
<?php
    if (isset($_SESSION["session"]) && $db->estMembreProjet($row["PRO_id"], $_SESSION["id_co"])) {
?>
                    <td>
                      <form style="display: inline;" action="modificationSprint.php" method="post">
                        <input type="hidden" name="id_sprint" value="<?php echo $id_spr; ?>"/>
                        <input type="hidden" name="nom_sprint" value="<?php echo $nom_spr; ?>"/>
                        <input type="hidden" name="num_sprint" value="<?php echo $row_sprints["SPR_numero"]; ?>"/>
                        <input type="hidden" name="duree_sprint" value="<?php echo $row_sprints["SPR_duree"]; ?>"/>
                        <input class="btn btn-default" type="submit" value="Modifier"/>
                      </form>
                      <form style="display: inline;" action="" method="post">
                        <input type="hidden" name="suppr_sprint" value="<?php echo $id_spr;?>"/>
                        <input class="btn btn-danger" type="submit" value="Supprimer"/>
                      </form>
                    </td>
<?php
    }
?>
                  </tr>
<?php
  }
?>
                </tbody>
              </table>
            </div>
          </article>
          <aside>
<?php
  if (isset($_SESSION["session"]) && $db->estMembreProjet($row["PRO_id"], $_SESSION["id_co"])) {
?>
            <div class="col-sm-2 sidenav">
              <form style="display: inline;" action="formulaireSprint.php" method="post">
                <input type="hidden" name="action_page" value="ajouter"/>
                <input class="btn btn-primary" type="submit" value="Ajouter Sprint"/>
              </form>
            </div>
<?php
  }
?>
          </aside>
<?php
  $s->footer();
} else {
  header("Location: ../web/index.php");
  exit();
}
?>
