<?php
require_once("../web/config.php");

if (isset($_COOKIE["id_projet"])) {
  $id_pro = $_COOKIE["id_projet"];
  $infos_pro = $db->infosProjet($id_pro);
  $row_pro = $infos_pro->fetch_assoc();

  function fibo($n) {
    if ($n <= 0) {
      return 0;
    } elseif ($n == 1) {
      return 1;
    } else {
      return (fibo($n-1) + fibo($n-2));
    }
  }

  $s->head("Backlog");
  $s->header($db);
  $s->nav($db);
?>
          <script>
            $(document).ready(function() {
              $('#tableUS').DataTable( {
                "order": [[ 4, "asc" ]],
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
              <h2><?php echo $row_pro["PRO_nom"];?> - Backlog</h2>
              <hr>
<?php
  if (isset($_GET["modif"])) {
    if (!strcmp($_GET["modif"], "erreurID")) {
?>
              <div class="alert alert-danger alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Erreur!</strong> Cette User Story n'existe pas.
              </div>
<?php
    }
    if (!strcmp($_GET["modif"], "erreur")) {
?>
              <div class="alert alert-danger alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Erreur!</strong> La modification de l'User story a échoué.
              </div>
<?php
    }
  }
  if (isset($_GET["modif"])) {
    if (!strcmp($_GET["modif"], "OK")) {
?>
              <div class="alert alert-success alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>OK!</strong> User story modifié.
              </div>
<?php
    }
  }
  if (isset($_GET["suppr"])) {
    if (!strcmp($_GET["suppr"], "erreurID")) {
?>
              <div class="alert alert-danger alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Erreur!</strong> Cette User Story n'existe pas.
              </div>
<?php
    }
    if (!strcmp($_GET["suppr"], "erreur")) {
?>
              <div class="alert alert-danger alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Erreur!</strong> La supression de l'User story a échoué.
              </div>
<?php
    }
  }
  if (isset($_GET["suppr"])) {
    if (!strcmp($_GET["suppr"], "OK")) {
?>
              <div class="alert alert-success alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>OK!</strong> User story supprimé.
              </div>
<?php
    }
  }
  if (isset($_GET["ajout"])) {
    if (!strcmp($_GET["ajout"], "erreur")) {
?>
              <div class="alert alert-danger alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Erreur!</strong> L'ajout de l'User Story a échoué.
              </div>
<?php
    }
  }
  if (isset($_GET["ajout"])) {
    if (!strcmp($_GET["ajout"], "OK")) {
?>
              <div class="alert alert-success alert-dismissible">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>OK!</strong> User story ajouté.
              </div>
<?php
    }
  }
?>
              <table id="tableUS" class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Nom</th>
                    <th>Chiffrage</th>
                    <th>Priorité</th>
		    <th>Sprint</th>
                    <th>Date de création</th>
<?php
  if (isset($_SESSION["session"])) {
    if ($db->estMembreProjet($row_pro["PRO_id"], $_SESSION["id_co"])) {
?>
                    <th>Actions</th>
<?php
    }
  }
?>
                  </tr>
                </thead>
                <tbody>
<?php
  $result = $db->listeUserStories($id_pro);

  while ($row = $result->fetch_assoc()) {
?>
                  <tr>
                    <td><?php echo $row["US_nom"]; ?></td>
                    <td><?php echo $row["US_chiffrageAbstrait"]; ?></td>
                    <td><?php echo $row["US_priorite"]; ?></td>
                    <td><?php echo $db->numeroSprint($row["SPR_id"]); ?></td>
                    <td><?php echo $row["US_dateCreation"]; ?></td>
<?php
    if (isset($_SESSION["session"])) {
      if ($db->estMembreProjet($id_pro, $_SESSION["id_co"])) {
?>
                    <td>
                      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modificationModal<?php echo $row["US_id"]; ?>">Modifier</button>
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimerModal<?php echo $row["US_id"]; ?>">Supprimer</button>
                    </td>
                    <!-- Modal Modification -->
                    <div id="modificationModal<?php echo $row["US_id"]; ?>" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <form style="display: inline;" action="../web/modificationUserStory.php" method="post">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Modification d'une User Story</h4>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <label for="nomInput">Nom</label>
                                <input type="text" class="form-control" id="nomInput" placeholder="En tant que... je souhaite..." name="nom_us" value="<?php echo $row["US_nom"]; ?>" required>
                              </div>
                              <div class="form-group">
                               <label for="chiffrageSelect">Chiffrage</label>
                               <select class="form-control" id="chiffrageSelect" name="chiffrage_us">
<?php
        for ($i=2; $i < 11 ; $i++) {
          if (fibo($i) == $row["US_chiffrageAbstrait"]) {
?>
                                  <option selected="selected"><?php echo fibo($i); ?></option>
<?php
          } else {
?>
                                  <option><?php echo fibo($i); ?></option>
<?php
          }
        }
?>
                              </select>
                             </div>
<?php
        if ($db->estProductOwner($_SESSION["id_co"], $id_pro)) {
?>
                             <div class="form-group">
                              <label for="prioriteSelect">Priorité</label>
                              <select class="form-control" id="prioriteSelect" name="priorite_us">
<?php
          for ($i=1; $i < 11 ; $i++) {
            if ($i == $row["US_priorite"]) {
?>
                                <option selected="selected"><?php echo $i; ?></option>
<?php
            } else {
?>
                                <option><?php echo $i; ?></option>
<?php
            }
          }
?>
                             </select>
                            </div>
<?php
        }
?>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                              <input type="hidden" name="id_us" value="<?php echo $row["US_id"]; ?>"/>
                              <input class="btn btn-primary" type="submit" value="Valider"/>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Modal Suppression -->
                    <div id="supprimerModal<?php echo $row["US_id"]; ?>" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Confirmation de suppression d'une User Story</h4>
                          </div>
                          <div class="modal-body">
                            <p>Attention action irréversible</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                            <form style="display: inline;" action="../web/suppressionUserStory.php" method="post">
                              <input type="hidden" name="id_us" value="<?php echo $row["US_id"]; ?>"/>
                              <input class="btn btn-danger" type="submit" value="Supprimer"/>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
<?php
      }
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
  if (isset($_SESSION["session"])) {
    if ($db->estMembreProjet($id_pro, $_SESSION["id_co"])) {
?>
            <div class="col-sm-2 sidenav">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ajoutModal">Ajouter</button>
            </div>
            <!-- Modal Ajout -->
            <div id="ajoutModal" class="modal fade" role="dialog" style="text-align: left">
              <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <form style="display: inline;" action="../web/ajoutUserStory.php" method="post">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Ajout d'une User Story</h4>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label for="nomInput">Nom</label>
                        <input type="text" class="form-control" id="nomInput" placeholder="En tant que... je souhaite..." name="nom_us" required>
                      </div>
                      <div class="form-group">
                       <label for="chiffrageSelect">Chiffrage</label>
                       <select class="form-control" id="chiffrageSelect" name="chiffrage_us">
<?php
      for ($i=2; $i < 11 ; $i++) {
?>
                          <option><?php echo fibo($i); ?></option>
<?php
      }
?>
                      </select>
                     </div>
<?php
      if ($db->estProductOwner($_SESSION["id_co"], $id_pro)) {
?>
                     <div class="form-group">
                      <label for="prioriteSelect">Priorité</label>
                      <select class="form-control" id="prioriteSelect" name="priorite_us">
<?php
        for ($i=1; $i < 11 ; $i++) {
?>
                        <option><?php echo $i; ?></option>
<?php
        }
?>
                     </select>
                    </div>
<?php
      }
?>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                      <input class="btn btn-primary" type="submit" value="Valider"/>
                    </form>
                  </div>
                </div>
              </div>
            </div>
<?php
    }
  }
?>
          </aside>
<?php
  $s->footer();
}  else {
  header("Location: ../web/index.php");
  exit();
}
?>
