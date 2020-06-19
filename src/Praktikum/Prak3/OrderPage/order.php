<?php  // UTF-8 marker äöüÄÖÜß€
session_start();
/**
 * Class PageTemplate for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 7
 *
 * @file     PageTemplate.php
 * @package  Page Templates
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 * @version  2.0
 */

// to do: change name 'PageTemplate' throughout this file
require_once '../Page.php';
require_once './blockCartForm.php';

/**
 * This is a template for top level classes, which represent
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class.
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking
 * during implementation.

 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
class Order extends Page
{
    // to do: declare reference variables for members
    // representing substructures/blocks

    private $blockCartForm;

    /**
     * Instantiates members (to be defined above).
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
     *
     * @throws Exception
     */

    protected function __construct()
    {
        parent::__construct();
        $this->blockCartForm = new blockCartForm($this->_database);

    }

    /**
     * Cleans up what ever is needed.
     * Calls the destructor of the parent i.e. page class.
     * So the database connection is closed.
     *
     * @return void
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return array
     * @throws Exception
     */
    protected function getViewData()
    {
        // to do: fetch data for this view from the database
        error_reporting(E_ALL);
        //associative Array with (id => pizzaData) 
        $pizzas = array();
        $sql = "SELECT * FROM article";
        $recordset = $this->_database->query($sql);
        if (!$recordset)
            throw new Exception("Fehler in Abfrage: " . $this->database->error);
        while ($record = $recordset->fetch_assoc()) {
            $id = $record["id"];
            $name = $record["name"];
            $price = $record["price"];
            $picture = $record["picture"];
            //array for name, price, picture.
            $pizzaData = array($name, $price, $picture);
            $pizzas[$id] = $pizzaData;
        }
        $recordset->free();
        return $pizzas;
    }

    /**
     * First the necessary data is fetched and then the HTML is
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if avaialable- the content of
     * all views contained is generated.
     * Finally the footer is added.
     *
     * @return void
     * @throws Exception
     */

    protected function generateView()
    {
        error_reporting(E_ALL);
        //$pizzas = $this->getViewData();
        $this->generatePageHeader('Bestellung');
        // to do: call generateView() for all members
        // to do: output view of this page

        echo "<h1>Bestellung</h1>";
        $pizzas = $this->getViewData();
        $this->printMenu($pizzas);
        $this->blockCartForm->generateView(1,$pizzas);
        $this->generatePageFooter();
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here.
     * If the page contains blocks, delegate processing of the
     * respective subsets of data to them.
     *
     * @return void
     * @throws Exception
     */
    protected function processReceivedData()
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members

    }

    /**
     * Print the Menu for Pizzas
     *
     * @param $pizzas , $pizzas is an assoziative Array with pizza's id as key and
     *         pizza's datas Array as Value
     * @return void
     */

    private function printMenu($pizzas){
        error_reporting(E_ALL);
        echo "<section>\n";
        // to do: call generateView() for all members
        echo <<<EOT
            <h2>Speisekarte</h2>
        EOT;
        foreach ($pizzas as $id => $pizzaData) {
            $name = htmlspecialchars($pizzaData[0]);
            $price = htmlspecialchars($pizzaData[1]);
            $imgAdres = htmlspecialchars($pizzaData[2]);
            echo <<<EOT
        <form> 
        <article>
        <h4>{$name}<br> {$price} &euro; </h4>
        <img src=$imgAdres width="250" height="250" alt="pizzaIMG" title="{$name}" />
        </article>
        </form>
        EOT;
        }
        echo "</section>\n";
    }

    /**
     * This main-function has the only purpose to create an instance
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     *
     * @return void
     */
    public static function main()
    {
        try {
            $page = new Order();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page. 
// That is input is processed and output is created.
Order::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >
