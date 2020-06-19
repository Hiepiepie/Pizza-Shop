<?php	// UTF-8 marker äöüÄÖÜß€

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
class Customer extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks

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
        // to do: instantiate members representing substructures/blocks
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
     * @return void
     */
    protected function getViewData()
    {
        // to do: fetch data for this view from the database
    return ;

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
        $this->getViewData();
        $this->generatePageHeader('Kunde');
        // to do: call generateView() for all members
        // to do: output view of this page

        echo <<<EOT
        <nav class="navbar bg-dark navbar-dark fixed-top navbar-expand-md " >
         <section class="container-fluid">  
                 <section class="container " ><a class="navbar-brand" href="../../../index.php">Clumsy Pizzas</a></section>
                 
                  <section id="collapsibleNavbar" class="navbar-collapse collapse w-100">
                  <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                      <a class="nav-link" href="../OrderPage/order.php">Bestellung</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="customer.php">Kunde</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="../DriverPage/driver.php">Fahrer</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="../BakerPage/baker.php">Baeker</a>
                    </li></ul></section> 
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                     </button>            
                </section>            
                </nav>  
        <section class="container driverSection" style="margin-top: 80px">
        EOT;
        $this->printStatus();
        echo <<<EOT
            <form action="../OrderPage/order.php">
            <input type="submit" value="Neue Bestellung" />
            </form>
            
        EOT;
        echo "</section>";
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
        error_reporting(E_ALL);
        if (isset($_POST["address"]) && isset($_POST["selectedItems"])) {
                $this->_database->query("Begin Transaction;");
                $this->_database->query("Lock Table ordering Write;");
                $this->_database->query("Lock Table ordered_articles Write;");
            $address = $this->_database->real_escape_string($_POST["address"]);
            $sqlQuery = "INSERT INTO ordering (id,address,timestamp) VALUES (DEFAULT,'$address',DEFAULT)";
            $recordset = $this->_database->query($sqlQuery);
            if (!$recordset) {
                throw new Exception("Query failed1" . $this->_database->error);
            }
            $order_id = $this->_database->real_escape_string($this->_database->insert_id);

            session_start();
            $_SESSION["orderID"] = $order_id;

            $selectedItems = $_POST["selectedItems"];
            foreach ($selectedItems as $item) {
                $sqlQuery = "INSERT INTO ordered_articles (id,f_article_id,f_order_id,status) 
                VALUES (DEFAULT,'$item','$order_id',DEFAULT)";
                $recordset = $this->_database->query($sqlQuery);
                if (!$recordset) {
                    throw new Exception("Query failed" . $this->_database->error);
                }
            }
            $this->_database->query("Unlock Tables;");
            $this->_database->query("Commit;");
            //data have saved in database
            header("Location: " . $_SERVER['REQUEST_URI']);
            return;
        }
    }

    /**
     * Print the Status of Pizzas
     *
     *
     * @return void
     */
    private function printStatus(){
        // to do: call generateView() for all members

            echo <<< EOT
            <h1 class="text-muted text-responsive font-weight-bold">Bestellung Status</h1>
            <section id ="status">
            </section>
            EOT;

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
            $page = new Customer();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page. 
// That is input is processed and output is created.
Customer::main();


// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >