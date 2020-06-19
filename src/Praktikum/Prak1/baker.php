<?php
header("Content-type: text/html");
$title = "Bäcker";
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
  <h1> Bäcker Seite </h1>
  <form action="https://echo.fbi.h-da.de/" accept-charset="UTF-8" method="post">
    <section>
      <p><b>Bestellung 24</b></p>
      <p>Margherita<br>
      <label for="r1.1">Bestellt</label>
      <input type="radio" name="pizza1" id="r1.1" value="0" checked /><br>
      <label for="r1.2">im Ofen</label>
      <input type="radio" name="pizza1" id="r1.2" value="1" /><br>
      <label for="r1.3">Fertig</label>
      <input type="radio" name="pizza1" id="r1.3" value="2" />
      </p>

      <p>Hawaii<br>
      <label for="r2.1">Bestellt</label>
      <input type="radio" name="pizza2" id="r2.1" value="0" checked /><br>
      <label for="r2.2">im Ofen</label>
      <input type="radio" name="pizza2" id="r2.2" value="1" /><br>
      <label for="r2.3">Fertig</label>
      <input type="radio" name="pizza2" id="r2.3" value="2" />
      </p>
    </section>

    <section>
      <p><b>Bestellung 25</b></p>
      <p>Salami<br>
      <label for="r3.1">Bestellt</label>
      <input type="radio" name="pizza3" id="r3.1" value="0" checked /><br>
      <label for="r3.2">im Ofen</label>
      <input type="radio" name="pizza3" id="r3.2" value="1" /><br>
      <label for="r3.3">Fertig</label>
      <input type="radio" name="pizza3" id="r3.3" value="2" />
      </p>

    <p>Hawaii<br>
      <label for="r4.1">Bestellt</label>
      <input type="radio" name="pizza4" id="r4.1" value="0" checked /><br>
      <label for="r4.2">im Ofen</label>
      <input type="radio" name="pizza4" id="r4.2" value="1" /><br>
      <label for="r4.3">Fertig</label>
      <input type="radio" name="pizza4" id="r4.3" value="2" />
      </p>
    </section>


<input type="submit"  value="Submit" />
</form>
EOT;
?>
</body>
</html>
