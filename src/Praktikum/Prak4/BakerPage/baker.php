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
class Baker extends Page
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
       // error_reporting(E_ALL);
        $orderedArticles = array();
        $sqlQuery = "SELECT article.name, ordered_articles.* FROM ordered_articles JOIN article WHERE ordered_articles.f_article_id = article.id AND ordered_articles.status < 3";
        $recordset = $this->_database->query($sqlQuery);
        if (!$recordset)
            throw new Exception("Fehler in Abfrage: " . $this->_database->error);
        while ($record = $recordset->fetch_assoc()) {
            $id = $record["id"];
            $orderID = $record["f_order_id"];
            $status = $record["status"];
            $name = $record["name"];
            $orderedArticle = array($name,$orderID,$status,$id);
            $orderedArticles[$id] = $orderedArticle;
        }
        $recordset->free();
        return $orderedArticles;
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
        $orderedArticles = $this->getViewData();
        $this->generatePageHeader('Bäcker');

        // to do: call generateView() for all members
        // to do: output view of this page
        echo <<< EOT
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
                      <a class="nav-link" href="../DriverPage/driver.php">Fahrer</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Baeker</a>
                    </li></ul></section> 
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                     </button>            
                </section>            
                </nav>  
                <section class="container driverSection" style="margin-top: 80px">
            <h1 class="text-responsive text-muted font-weight-bold">Pizzas Status</h1>
            <form id="formID" action="baker.php" accept-charset="UTF-8" method="post">
            EOT;
        $articleEachOrder = array();
        foreach ($orderedArticles as $id => $orderedArticle){
            $name = htmlspecialchars($orderedArticle[0]);
            $orderID = htmlspecialchars($orderedArticle[1]);
            $status = htmlspecialchars($orderedArticle[2]);
            $id = htmlspecialchars($orderedArticle[3]);
            $article = array($id, $name, $status);
            if(array_key_exists($orderID,$articleEachOrder)){
                $articleArray = $articleEachOrder[$orderID];
                array_push($articleArray,$article);
                $articleEachOrder[$orderID] = $articleArray;
            }
            else{
                $articleArray = array();
                array_push($articleArray,$article);
                $articleEachOrder[$orderID] = $articleArray;
            }
        }
        ksort($articleEachOrder);
        $this->printArticleStatus($articleEachOrder);
        echo <<< EOT
            <br>
            </form>
            EOT;

        $this->generatePageFooter();
    }

    /**
     * Print article Status for each pizza
     *
     * @param array $articleEachOrder
     */
    public function printArticleStatus($articleEachOrder): void
    {
        foreach($articleEachOrder as $orderId => $articleArray){
            echo "<section><h2 class=\"text-responsive bestelltnummer\">Bestellnummer {$orderId}</h2>";
            foreach ($articleArray as $article){
                $id = $article[0];
                $name = $article[1];
                $status = $article[2];
                echo "<p class=\"text-responsive\"><b>{$name} Pizza </b> mit id : {$id} <br>";
                switch ($status){
                    case 0:{
                        echo <<< EOT
            <label class="text-responsive" for=$id.1>Bestellt</label>
            <input type="radio" name='statuses[{$id}]' id=$id.1 value="0" onclick="document.forms['formID'].submit()" checked/><br>
            <label class="text-responsive" for=$id.2>Im Ofen</label>
            <input type="radio" name='statuses[{$id}]' id=$id.2 value="1" onclick="document.forms['formID'].submit()" /><br>
            <label class="text-responsive" for=$id.3>Fertig</label>
            <input type="radio" name='statuses[{$id}]' id=$id.3 value="2" onclick="document.forms['formID'].submit()"/><br>  
            </p>
            EOT;
                        break;
                    }
                    case 1:{
                        echo <<< EOT
            <label class="text-responsive" for=$id.1>Bestellt</label>
            <input type="radio" name='statuses[{$id}]' id=$id.1 value="0" onclick="document.forms['formID'].submit()"/><br>
            <label class="text-responsive" for=$id.2>Im Ofen</label>
            <input type="radio" name='statuses[{$id}]' id=$id.2 value="1" onclick="document.forms['formID'].submit()" checked/><br>
            <label class="text-responsive" for=$id.3>Fertig</label>
            <input type="radio" name='statuses[{$id}]' id=$id.3 value="2" onclick="document.forms['formID'].submit()" /><br>  
            </p>
            EOT;
                        break;
                    }
                    case 2:{
                        echo <<< EOT
            <label class="text-responsive" for=$id.1>Bestellt</label>
            <input type="radio" name='statuses[{$id}]' id=$id.1 value="0" onclick="document.forms['formID'].submit()"/><br>
            <label class="text-responsive" for=$id.2>Im Ofen</label>
            <input type="radio" name='statuses[{$id}]' id=$id.2 value="1" onclick="document.forms['formID'].submit()"/><br>
            <label class="text-responsive" for=$id.3>Fertig</label>
            <input type="radio" name='statuses[{$id}]' id=$id.3 value="2" onclick="document.forms['formID'].submit()" checked /><br>  
            </p>
            EOT;
                        break;
                    }
                }
            }
            echo"</section>";
        }
        echo "</section>";
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
        //error_reporting(E_ALL);
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
        if (isset($_POST['statuses'])){
            $statuses =  $_POST['statuses'];
            $this->_database->query("Begin Transaction;");
            $this->_database->query("Lock Table ordered_articles Write;");
            foreach($statuses as $id => $status){
                $sqlQuery ="UPDATE ordered_articles SET status = $status WHERE id = $id";
                $recordset = $this->_database->query($sqlQuery);
                if (!$recordset) {
                    throw new Exception("Query failed!" . $this->_database->error);
                }
            }

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
            $page = new Baker();
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
Baker::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >