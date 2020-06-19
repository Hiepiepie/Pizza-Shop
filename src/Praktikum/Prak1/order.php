<?php
header("Content-type: text/html");
$title = "Bestellung";
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
  $margherita = "4.00 &euro";
  $salami = "4.50 &euro";
  $hawaii = "5.50 &euro";
  $imgAdres = "img/pizza.png";
  echo <<<EOT
  <h1>Bestellung </h1>
  <section>
    <article>
    <h2>Speisekarte</h2>
    <img src=$imgAdres width="250" height="250" alt="" title="Margherita Pizza" />
    <p>Margherita<br />
    {$margherita};
    </p>
    </article>
    <article>
    <img src=$imgAdres width="250" height="250" alt="" title="Salami Pizza" />
    <p>Salami<br />
    {$salami};
    </p>
    </article>
    <article>
    <img src=$imgAdres width="250" height="250" alt="" title="Hawaii Pizza" />
    <p>Hawaii<br />
    {$hawaii};
    </p>
    </article>
  </section>
  EOT;
  ?>

  <section>
    <form action="https://echo.fbi.h-da.de/" accept-charset="UTF-8" method="post">
      <h2>Warenkorb</h2>
      <label for="bestellung" hidden>Bestelleung</label>
      <select name="Auswahl" size="6" tabindex="1" id="bestellung" multiple required>
        <option value="1" selected>
          Margherita</option>
        <option value="2">
          Hawaii</option>
        <option value="3">
          Salami</option>
      </select>
      <p>Gesamtpreis:  <span id="totalPrice">14,50</span> €</p>
      <label for="adresse">Adresse</label>
      <input type="text" name="Adresse" id="adresse" size="30" maxlength="40" value="" placeholder="Ihre Adresse" required />
      <p></p>
      <input type="reset" value="Alle loeschen" />
      <input type="reset" value="Auswahl loeschen" />
      <input type="submit" value="Bestellen" />
    </form>
  </section>


</body>

</html>