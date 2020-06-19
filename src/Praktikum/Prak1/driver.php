<?php
header("Content-type: text/html");
$title = "Fahrer";
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
  <h1> Fahrer Seite </h1>
  <form action="https://echo.fbi.h-da.de/" accept-charset="UTF-8" method="post">
  <section>
    <p><b>Bestellung 21</b></p>
    <p>Darmstadter Str. 123</p>
    <label for= "status1" hidden>status</label> 
    <select name="Status1" size="1" tabindex="1" id="status1">
      <option value="3" selected>
        abgehölt</option>
      <option value="4">
        geliefert</option>
    </select>
  </section>
  <section>
    <p><b>Bestellung 21</b></p>
    <p>Darmstadter Str. 123</p>
    <label for= "status2" hidden>status</label> 
    <select name="Status2" size="1" tabindex="1" id="status2">
      <option value="3" selected>
        abgehölt</option>
      <option value="4">
        geliefert</option>
    </select>
  </section>
  <section>
    <p><b>Bestellung 22</b></p>
    <p>Mainzer Str. 4</p>
    <label for= "status3" hidden>status</label> 
    <select name="Status3" size="1" tabindex="1" id="status3">
      <option value="3" selected>
        abgehölt</option>
      <option value="4">
        geliefert</option>
    </select>
  </section>  
  <section>
    <input type="submit"  value="Submit" />
  </section>
  </form>
  EOT;
  ?>
</body>

</html>