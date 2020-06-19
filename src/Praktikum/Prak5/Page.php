<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * Class Page for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 7
 *
 * @file     Page.php
 * @package  Page Templates
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 * @version  2.0
 */

/**
 * This abstract class is a common base class for all
 * HTML-pages to be created.
 * It manages access to the database and provides operations
 * for outputting header and footer of a page.
 * Specific pages have to inherit from that class.
 * Each inherited class can use these operations for accessing the db
 * and for creating the generic parts of a HTML-page.
 *
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
abstract class Page
{
    // --- ATTRIBUTES ---

    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;

    // --- OPERATIONS ---

    /**
     * Connects to DB and stores
     * the connection in member $_database.
     * Needs name of DB, user, password.
     *
     * @throws Exception
     */
    protected function __construct()
    {
        error_reporting (E_ALL);

        $this->_database = new MySQLi("mariadb", "public", "public", "pizzaservice_2020");


        //$this->_database = new MySQLi("localhost", "public", "public", "pizzaservice_2020");
        $this->_database->set_charset("utf8");
        if (mysqli_connect_errno())
            throw new Exception("Connect failed: " . mysqli_connect_error());

        // set charset to UTF8!!
        if (!$this->_database->set_charset("utf8"))
            throw new Exception($this->_database->error);
    }

    /**
     * Closes the DB connection and cleans up
     *
     * @return void
     */
    public function __destruct()
    {
        // to do: close database
    }

    /**
     * Generates the header section of the page.
     * i.e. starting from the content type up to the body-tag.
     * Takes care that all strings passed from outside
     * are converted to safe HTML by htmlspecialchars.
     *
     * @param string $headline $headline is the text to be used as title of the page
     *
     * @return void
     */
    protected function generatePageHeader($headline = "")
    {
        $headline = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");

        // to do: output common beginning of HTML code
        // including the individual headline

        echo <<<EOT
        <!DOCTYPE html>
        <html lang="de">
        <head>
          <meta charset="UTF-8" />
        <!--          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">-->
          <meta name="viewport" content="width=device-width, initial-scale=1">
        EOT;
        if($headline=="Bäcker" || $headline == "Fahrer"){
            echo "<meta http-equiv=\"refresh\" content=\"5\" />";
        }

        echo <<<EOT
          <!-- für später: CSS include -->
          <!-- <link rel="stylesheet" href="XXX.css"/> -->
           <!-- Latest compiled and minified CSS -->
             
              <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
              <link rel="stylesheet" type="text/css" href="../Style.css">
              <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
              
              <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
              <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
          <!-- für später: JavaScript include -->
          <script type="text/javascript" src="StatusUpdate.js" async></script>
          <script type="text/javascript" src="Cart.js" async></script>
          <title>$headline</title>
        </head>
        EOT;
        if($headline=="Bäcker"){
            echo "<body>";
        } if ($headline == "Fahrer"){
            echo "<body>";
        } if ($headline == "Kunde"){
            echo "<body onload=\"window.setInterval(requestData,1000)\">";
        } if ($headline == "Bestellung"){
            echo "<body onload=\"window.setInterval(checkSubmit,10)\">";
        }
    }

    /**
     * Outputs the end of the HTML-file i.e. /body etc.
     *
     * @return void
     */
    protected function generatePageFooter()
    {
        // to do: output common end of HTML code
        echo <<< EOT
        </body>
        </html>
        EOT;

    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If every page is supposed to do something with submitted
     * data do it here. E.g. checking the settings of PHP that
     * influence passing the parameters (e.g. magic_quotes).
     *
     * @return void
     */
    protected function processReceivedData()
    {

    }
} // end of class

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >