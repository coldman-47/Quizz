<?php
$users = json_decode(file_get_contents('users.json'),true);
$questionnaires=json_decode(file_get_contents('questions.json'),true);
$type = $questionnaires['type'];
$qcm = $radio = $text = 0;
foreach($type as $val){
  if($val=='qcm'){
    $qcm++;
  }elseif($val=='radio'){
    $radio++;
  }else{
    $text++;
  }
}
$joueurs  = $users['joueurs']; $admin = $users['admin'];
$logins = $joueurs['login']; $scores = $joueurs['score'];
$labels = $points = '';
$j = $a = 0;
foreach($logins as $key => $val){
  $labels .= ',"'.$val.'"';
  $points .= ',"'.$scores[$key].'"';
  $j++;
}
foreach($admin['login'] as $val){
  $a++;
}
$labels = '['.substr($labels, 1).']';
$points = '['.substr($points, 1).']';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="vendor/Chart.min.css.css">
    <script src="vendor/Chart.min.js"></script>
</head>
<style>
.chart-container {
  position: relative;
  margin: auto;
  height: 80vh;
  width: 100%;
  padding: 1rem;
}
nav{
  display: flex;
  justify-content: space-around;
  padding: 1rem;
  font-weight: bold;
}
a{
  color: deepskyblue;
}
</style>
<body>
<nav>
  <a href="index.php">Histogramme des Joueurs par Score</a>
  <a href="index.php?ratio">Ratio Admin / Joueurs</a>
  <a href="index.php?questions">Répartition Questions / type</a>
</nav>
<div class="chart-container">
    <canvas id="chart"></canvas>
</div>

</body>
<script>
  <?php
  if(isset($_GET['ratio'])){
    ?>
let conteneur = document.getElementsByClassName('chart-container')[0];
conteneur.style.maxHeight = "25rem";

var canvas = document.getElementById("chart");
var ctx = canvas.getContext('2d');

// Global Options:
 Chart.defaults.global.defaultFontColor = 'black';
 Chart.defaults.global.defaultFontSize = 16;

var data = {
    labels: ["Admin ", "Joueurs"],
      datasets: [
        {
            fill: true,
            backgroundColor: [
                'deepskyblue',
                'grey'],
            data: [<?= $a.",".$j ?>],
// Notice the borderColor
            borderColor:	['white'],
            borderWidth: [2,2]
        }
    ]
};

var options = {
        title: {
                  display: true,
                  text: 'Ratio Admin / Joueurs',
                  position: 'top'
              }
};

var myBarChart = new Chart(ctx, {
    type: 'pie',
    data: data,
    options: options
});
<?php
}elseif(isset($_GET['questions'])){
  ?>
let conteneur = document.getElementsByClassName('chart-container')[0];
conteneur.style.maxHeight = "25rem";

var canvas = document.getElementById("chart");
var ctx = canvas.getContext('2d');

// Global Options:
 Chart.defaults.global.defaultFontColor = 'black';
 Chart.defaults.global.defaultFontSize = 16;

var data = {
    labels: ["QCM ", "Choix Simples", "Choix Textes"],
      datasets: [
        {
            fill: true,
            backgroundColor: [
                'deepskyblue',
                'grey'],
            data: [<?= $qcm.",".$radio.",".$text ?>],
// Notice the borderColor
            borderColor:	['white'],
            borderWidth: [2,2]
        }
    ]
};

var options = {
        title: {
                  display: true,
                  text: 'Répartition des réponses par type',
                  position: 'top'
              }
};

var myBarChart = new Chart(ctx, {
    type: 'doughnut',
    data: data,
    options: options
});
<?php
}
else{
?>
Chart.defaults.global.legend.display = false;
    var data = {
  labels: <?= $labels ?>,
  datasets: [{
    label: "Points",
    backgroundColor: "rgba(0,191,255,0.2)",
    borderColor: "deepskyblue",
    borderWidth: 1,
    hoverBackgroundColor: "rgba(100,100,100,0.6)",
    data: <?= $points ?>,
  }]
};

var options = {
  maintainAspectRatio: false,
  scales: {
    yAxes: [{
      stacked: true,
    }],
    xAxes: [{
      gridLines: {
        display: false
      }
    }]
  }
};

Chart.Bar('chart', {
  options: options,
  data: data
});
<?php } ?>
</script>
</html>