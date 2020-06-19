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
class CustomerStatus extends Page
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
     * @return array
     * @throws Exception
     */
    protected function getViewData()
    {
        // to do: fetch data for this view from the database
        error_reporting(E_ALL);
        //associative Array with (id => pizzaStatus)
        $orderIds = array();
        $pizzaStatuses = array();

        if (isset($_SESSION["orderID"])) {

            $sqlQuery = "SELECT id FROM ordering WHERE id = {$_SESSION["orderID"]}";
            $recordset = $this->_database->query($sqlQuery);
            if (!$recordset)
                throw new Exception("Fehler in Abfrage: " . $this->_database->error);

            //read all order ids available in database
            while ($record = $recordset->fetch_assoc()) {
                $orderId = htmlspecialchars($record["id"]);
                array_push($orderIds, $orderId);
            }
            $recordset->free();

            //read every status from each orderid
            foreach ($orderIds as $orderId) {
                $sqlQuery = "SELECT article.name,ordered_articles.status FROM ordered_articles JOIN article 
                        ON ordered_articles.f_article_id = article.id WHERE ordered_articles.f_order_id = $orderId";
                $recordset = $this->_database->query($sqlQuery);
                if (!$recordset)
                    throw new Exception("Fehler in Abfrage: " . $this->_database->error);
                $pizza = array();
                while ($record = $recordset->fetch_assoc()) {
                    $name = htmlspecialchars($record["name"]);
                    $status = htmlspecialchars($record["status"]);
                    array_push($pizza, array( $orderId,$name, $status));
                }
                $pizzaStatuses["pizzas"] = $pizza;
            }
            $recordset->free();
        }

        return $pizzaStatuses;
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
        header("Content-Type: application/json; charset=UTF-8");
        $item = $this->getViewData();
        $serializedData = json_encode($item);
        echo $serializedData;
        // $this->generatePageHeader('Kundenstatus');
        // to do: call generateView() for all members
        // to do: output view of this page
        // $this->generatePageFooter();
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here.
     * If the page contains blocks, delegate processing of the
     * respective subsets of data to them.
     *
     * @return void
     */
    protected function processReceivedData() 
    {
        parent::processReceivedData();
        header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 01 Jul 2000 06:00:00 GMT"); // Datum in der Vergangenheit
        header("Cache-Control: post-check=0, pre-check=0", false); // fuer IE
        header("Pragma: no-cache");
        session_cache_limiter('nocache'); // VOR session_start()!
        session_cache_expire(0);
        session_start();
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
            $page = new CustomerStatus();
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
CustomerStatus::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >