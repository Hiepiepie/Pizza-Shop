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
class Driver extends Page
{
    // to do: declare reference variables for members
    private $ifDataReceived = false;

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
        $orders = array();
        $sqlQuery = "SELECT ordered_articles.id, ordering.id as orderID, article.name, ordered_articles.status FROM ordering JOIN ordered_articles ON ordering.id = ordered_articles.f_order_id JOIN
            article ON ordered_articles.f_article_id = article.id ORDER BY ordered_articles.id";
        $recordset = $this->_database->query($sqlQuery);
        if (!$recordset)
            throw new Exception("Fehler in Abfrage: " . $this->_database->error);
        while ($record = $recordset->fetch_assoc()) {
            $id = $record["id"];
            $orderID = $record["orderID"];
            $name = $record["name"];
            $status = $record["status"];
            //array for
            $order = array( $orderID, $name, $status);

            $orders[$id] = $order;
        }
        $recordset->free();

        $orderDetails = array();
        $sql = "SELECT ordered_articles.f_order_id, SUM(article.price) as total, ordering.address FROM ordered_articles JOIN article 
                ON f_article_id = article.id JOIN ordering on ordering.id = ordered_articles.f_order_id GROUP by ordered_articles.f_order_id";
        $recordset = $this->_database->query($sql);
        if (!$recordset)
            throw new Exception("Fehler in Abfrage: " . $this->_database->error);

        while ($record = $recordset->fetch_assoc()) {
            $orderID = $record["f_order_id"];
            $total = $record["total"];
            $address = $record["address"];
            //array for
            $ordersDetail = array($total,$address);
            $orderDetails[$orderID] = $ordersDetail;
        }
        $recordset->free();

        return array($orders,$orderDetails);
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
        $this->generatePageHeader('Fahrer');
        // to do: call generateView() for all members
        // to do: output view of this page
        $dataArray = $this->getViewData();
        $orders = $dataArray[0];
        $orderDetails = $dataArray[1];

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
                      <a class="nav-link" href="../CustomerPage/customer.php">Kunde</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Fahrer</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="../BakerPage/baker.php">Baeker</a>
                    </li></ul></section> 
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                     </button>            
                </section>            
                </nav>     
        <section class="container ">
        <section class="container-fluid py-5">
        <section class="container driverSection" style="margin-top: 80px">
        <h1 class="text-responsive font-weight-bold text-muted">Lieferung Status</h1>
        <form id="formIdDriver" action="driver.php" accept-charset="UTF-8" method="post">
        EOT;
        foreach ($orderDetails as $orderId => $orderDetail) {
            $formattedTotal = htmlspecialchars(number_format($orderDetail[0], 2));
            $stats = array();
            $names = array();
            foreach ($orders as $id => $order) {
                $orderID = htmlspecialchars($order[0]);
                $name = htmlspecialchars($order[1]);
                $status = htmlspecialchars($order[2]);

                if ($orderID == $orderId) {
                    array_push($stats, $status);
                    array_push($names, $name);
                }
            }
            $inDelivery = 0;
            $finish = 0;
            $delivered = 0;
            foreach ($stats as $status) {
                switch ($status){
                    case 2:{
                        $finish++;
                        break;
                    }
                    case 3:{
                        $inDelivery++;
                        break;
                    }
                    case 4:{
                        $delivered++;
                        break;
                    }
                    default:break;
                }
            }

            switch (count($stats)){
                case $finish:{
                    echo <<< EOT
                    
                    <h3 class="text-responsive bestelltnummer">Bestellungnummer {$orderId}</h3>
                    <p class="text-responsive">Adresse: {$orderDetail[1]} <br>Total : {$formattedTotal} &euro;<br> Pizzas : 
                    EOT;
                    foreach ($names as $pizzas => $pizza)
                        echo "{$pizza}, ";
                    echo <<< EOT
                    <br>
                    <label class="text-responsive" for=$orderId.1><br>Finish</label>
                    <input type="radio" name='statuses[{$orderId}]' id=$orderId.1 value="2" onclick="document.forms['formIdDriver'].submit()" checked/><br>
                    <label class="text-responsive" for=$orderId.2>In Delivery</label>
                    <input type="radio" name='statuses[{$orderId}]' id=$orderId.2 value="3" onclick="document.forms['formIdDriver'].submit()"/><br>
                    <label class="text-responsive"  for=$orderId.3>Delivered</label>
                    <input type="radio" name='statuses[{$orderId}]' id=$orderId.3 value="4" onclick="document.forms['formIdDriver'].submit()"/><br> 
                    </p>
                    
                    EOT;
                    break;
                }
                case $inDelivery:{
                    echo <<< EOT
                    <section>
                    <h3 class="text-responsive bestelltnummer"> Bestellungnummer {$orderId}</h3>
                    <p class="text-responsive">Adresse: {$orderDetail[1]} <br>Total : {$formattedTotal} &euro;<br> Pizzas : 
                    EOT;
                    foreach ($names as $pizzas => $pizza)
                        echo "{$pizza}, ";
                    echo <<< EOT
                    <br>
                    <label class="text-responsive" for=$orderId.1><br>Finish</label>
                    <input type="radio" name='statuses[{$orderId}]' id=$orderId.1 value="2" onclick="document.forms['formIdDriver'].submit()"/><br>
                    <label class="text-responsive" for=$orderId.2>In Delivery</label>
                    <input type="radio" name='statuses[{$orderId}]' id=$orderId.2 value="3" onclick="document.forms['formIdDriver'].submit()" checked/><br>
                    <label class="text-responsive" for=$orderId.3>Delivered</label>
                    <input type="radio" name='statuses[{$orderId}]' id=$orderId.3 value="4" onclick="document.forms['formIdDriver'].submit()"/><br>  
                    </section> 
                    EOT;
                    break;
                }
                case $delivered:{
//                    echo "<p style='color: cornflowerblue'> DELIVERED !</p>";
                    break;
                }
                default:
//                    echo "<p style='color: red'>NOT READY !</p>";
                    break;
            }
        }
        echo <<< EOT
        <br>
        </form>
        </section>      
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
        if (isset($_POST['statuses'])){
            $statuses = $_POST['statuses'];
            $this->_database->query("Begin Transaction;");
            $this->_database->query("Lock Table ordered_articles Write;");
            foreach($statuses as $id => $status){
                $sqlQuery ="UPDATE ordered_articles SET status = $status WHERE f_order_id = $id";
                $recordset = $this->_database->query($sqlQuery);
                if (!$recordset) {
                    throw new Exception("Query failed!" . $this->_database->error);
                }
            }
            $this->ifDataReceived = true;
            $this->_database->query("Unlock Tables;");
            $this->_database->query("Commit;");
            header("Location: " . $_SERVER['REQUEST_URI']);
            return;
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
            $page = new Driver();
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
Driver::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >