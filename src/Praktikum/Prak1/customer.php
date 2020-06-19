<?php
header("Content-type: text/html");
$title = "Kunde";
?>


<!DOCTYPE html>
<html lang="de">

<?php
echo <<<EOT
<head>
  <meta charset="UTF-8" />
  <!-- für später: CSS include -->
  <!-- <link rel="stylesheet" href="XXX.css"/> -->
  <!-- für später: JavaScript include -->
  <!-- <script src="XXX.js"></script> -->
  <title>$title</title>
</head>
EOT;
?>

<body>
  <?php
    echo <<<EOT
    <h1> Kunde Seite </h1>
    <p> Hier Können Sie den Status Ihre Bestellung checken </p>
    <section>
    <p>Margheritta<br>
      <label for="r1.1">Bestellt</label>
      <input type="radio" name="pizza1" id="r1.1" value="0" checked /><br>
      <label for="r1.2">im Ofen</label>
      <input type="radio" name="pizza1" id="r1.2" value="1" /><br>
      <label for="r1.3">Fertig</label>
      <input type="radio" name="pizza1" id="r1.3" value="2" /><br>
      <label for="r1.4">Unterwegs</label>
      <input type="radio" name="pizza1" id="r1.4" value="3" /><br>
      <label for="r1.5">Geliefert</label>
      <input type="radio" name="pizza1" id="r1.5" value="4" />
    </p>
    </section>
    <section>
    <p>Salami<br>
      <label for="r2.1">Bestellt</label>
      <input type="radio" name="pizza2" id="r2.1" value="0" checked /><br>
      <label for="r2.2">im Ofen</label>
      <input type="radio" name="pizza2" id="r2.2" value="1" /><br>
      <label for="r2.3">Fertig</label>
      <input type="radio" name="pizza2" id="r2.3" value="2" /><br>
      <label for="r2.4">Unterwegs</label>
      <input type="radio" name="pizza2" id="r2.4" value="3" /><br>
      <label for="r2.5">Geliefert</label>
      <input type="radio" name="pizza2" id="r2.5" value="4" />
    </p>
    </section>
    EOT;
    ?>
</body>
</html>
