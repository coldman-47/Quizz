<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="index.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
<div id="doc">
    <?php
    require('header.php')
    ?>
    <div class="conteneur">
        <?php require_once('inscriptions.php') ?>
    </div>
</div>
</body>
<script>
function readURL(input) {
  if (input.files && input.files[0]) {
    var imgUploaded = new FileReader();

    imgUploaded.onload = function(e) {
      document.getElementById('avatar').src = e.target.result;
    }

    imgUploaded.readAsDataURL(input.files[0]);
  }
}

</script>
</html>