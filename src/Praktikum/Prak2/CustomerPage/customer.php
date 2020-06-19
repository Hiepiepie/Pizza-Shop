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
    private $ifDataReceived = false; //-1 if no data received, 0 if data received

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
        $sqlQuery = "SELECT id FROM ordering";
        $recordset = $this->_database->query($sqlQuery);
        if (!$recordset)
            throw new Exception("Fehler in Abfrage: " . $this->database->error);

        //read all order ids available in database
        while ($record = $recordset->fetch_assoc()){
            $orderId = $record["id"];
            array_push($orderIds,$orderId);
        }
        $recordset->free();

        //read every status from each orderid
        foreach($orderIds as $orderId){
            $sqlQuery = "SELECT article.name,ordered_articles.status FROM ordered_articles JOIN article 
                        ON ordered_articles.f_article_id = article.id WHERE ordered_articles.f_order_id = $orderId";
            $recordset = $this->_database->query($sqlQuery);
            if (!$recordset)
                throw new Exception("Fehler in Abfrage: " . $this->database->error);
            $pizzaStatus = array();
            while ($record = $recordset->fetch_assoc()) {
                $name = $record["name"];
                $status = $record["status"];
                array_push($pizzaStatus, array($name, $status));
            }
            $pizzaStatuses[$orderId] = $pizzaStatus;
        }
        $recordset->free();
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
        $this->getViewData();
        $this->generatePageHeader('Kunde');
        // to do: call generateView() for all members
        // to do: output view of this page
        if($this->ifDataReceived == true){
            echo "<h2 style='color: green'>Pizzas bestellt !<h2>";
        }
        echo <<<EOT
        <h1>Pizzas status</h1>
        EOT;
        $pizzaStatuses = $this->getViewData();
        $this->printStatus($pizzaStatuses);
        echo <<<EOT
            <form action="../OrderPage/order.php">
            <input type="submit" value="Neue Bestellung" />
            </form>
        EOT;
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
            $address = $_POST["address"];
            $sqlQuery = "INSERT INTO ordering (id,address,timestamp) VALUES (DEFAULT,'$address',DEFAULT)";
            $recordset = $this->_database->query($sqlQuery);
            if (!$recordset) {
                throw new Exception("Query failed1" . $this->_database->error);
            }
            $order_id = $this->_database->insert_id;
            $selectedItems = $_POST["selectedItems"];
            foreach ($selectedItems as $item) {
                $sqlQuery = "INSERT INTO ordered_articles (id,f_article_id,f_order_id,status) VALUES (DEFAULT,'$item','$order_id',DEFAULT)";
                $recordset = $this->_database->query($sqlQuery);
                if (!$recordset) {
                    throw new Exception("Query failed" . $this->_database->error);
                }
            }
            //data have saved in database
            $this->ifDataReceived = true;
            return;
        }
    }

    /**
     * Print the Status of Pizzas
     *
     * @param $pizzaStatuses
     * @return void
     */
    private function printStatus($pizzaStatuses){
        // to do: call generateView() for all members
        foreach ($pizzaStatuses as $orderId => $pizzaStatus) {
            echo "<section>\n
                 <h3>Bestellnummer  {$orderId} : </h3>";
            foreach($pizzaStatus as $status){
                $name = htmlspecialchars($status[0]);
                $status = htmlspecialchars($status[1]);
                echo "<p>{$name} Pizza : ";
                switch ($status) {
                    case 0:
                    {
                        echo "Bestellt</p>";
                        break;
                    }
                    case 1:
                    {
                        echo "Im Ofen</p>";
                        break;
                    }
                    case 2:
                    {
                        echo "Fertig</p>";
                        break;
                    }
                    case 3:
                    {
                        echo "In Lieferung</p>";
                        break;
                    }
                    case 4:
                    {
                        echo "Geliefert</p>";
                        break;
                    }
                }
            }
            echo "</section>";
        }
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