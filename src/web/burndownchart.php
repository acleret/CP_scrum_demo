<?php
require_once("../web/config.php");

if (isset($_COOKIE["id_projet"])) {
  $id_pro = $_COOKIE["id_projet"];
  $infos_pro = $db->infosProjet($id_pro);
  $row_pro = $infos_pro->fetch_assoc();
  $s->head("Burndown Chart");
  $s->header($db);
  $s->nav($db);

  $tab = [];
  if($db->listeUserStoriesAvecCommit($id_pro)->num_rows > 0) {
    if (($db->listeUserStoryOutOfSprints($id_pro)->num_rows == 0) && ($db->listeUserStories($id_pro)->num_rows > 0)) {
        $tab = $db->listeChiffrageReel($id_pro);
    }
  } else {
    if (($db->listeUserStoryOutOfSprints($id_pro)->num_rows == 0) && ($db->listeUserStories($id_pro)->num_rows > 0)) {
      $db->modifChiffragePlanifie($id_pro);
    }
  }
?>
          <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
          <script type="text/javascript" src="../web/js/jquery.canvasjs.min.js"></script>
          <script type="text/javascript">
            $(function () {
              //Better to construct options first and then pass it as a parameter
              var options = {
                title: {
                  text: "Burndown Chart"
                },
                legend: {
                  horizontalAlign: "right", // left, center ,right
                  verticalAlign: "top",  // top, center, bottom
                },
                animationEnabled: true,
                axisX: {
                  minimum: 0,
                  interval: 1,
                  title : "Sprints",
                },
                axisY:{
                  title : "Effort",
                },
                data: [
                  {
                    type: "line",
                    showInLegend: true,
                    name: "series1",
                    legendText: "planifié",
                    dataPoints: [
                      <?php
                        $resultat = $db->listeChiffragePlanifie($id_pro);
                        $somme = $db->sommeChiffragePlanifie($id_pro);
                        if(empty($somme)) $somme = 0;
                        echo "{ x: 0, y : ".$somme." },\n";
                        while ($row = $resultat->fetch_assoc()) {
                            echo "{ x: ".$db->numeroSprint($row["SPR_id"]).", y : ".($somme-=$row['BDC_chargePlanifie'])." },\n";
                        }
                      ?>
                    ]
                  },
                  {
                    type: "line",
                    showInLegend: true,
                    name: "series2",
                    legendText: "réel",
                    dataPoints: [
                      <?php
                        $somme = $db->sommeChiffrageBacklog($id_pro);
                        if(empty($somme)) $somme = 0;
                        echo "{ x: 0, y: ".$somme." },\n";
                        foreach ($tab as $key => $value) {
                          echo "{ x: ".$db->numeroSprint($key).", y: ".($somme-=$value)." },\n";
                        }
                      ?>
                    ]
                  }
                ]
              };
              $("#chartContainer").CanvasJSChart(options);
            });
          </script>
          <article>
            <div class="col-sm-8 text-left">
              <h2><?php echo $row_pro["PRO_nom"]; ?> - Burndown Chart</h2>
              <hr>
              <div id="chartContainer" style="height: 500px; width: 100%;"></div>
              <br />
            </div>
          </article>
<?php
  $s->footer();
} else {
  header("Location: ../web/index.php");
  exit();
}
?>
