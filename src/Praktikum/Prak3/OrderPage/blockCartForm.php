<?php    // UTF-8 marker äöüÄÖÜß€
/**
 * Class BlockTemplate for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 * 
 * PHP Version 7
 *
 * @file     BlockTemplate.php
 * @package  Page Templates
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 * @version  2.0
 */

/**
 * This is a template for classes, which represent blocks (e.g. a form)
 * within a web page. Instances of these classes are used as members 
 * of top level classes.
 * The order of methods might correspond to the order of thinking 
 * during implementation.
 
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
 */

class blockCartForm      // to do: change name of class
{
    // --- ATTRIBUTES ---

    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;

    // to do: declare reference variables for members 
    // representing substructures/blocks

    // --- OPERATIONS ---

    /**
     * Gets the reference to the DB from the calling page template.
     * Stores the connection in member $_database.
     *
     * @param $database $database is the reference to the DB to be used
     *
     */
    public function __construct($database)
    {
        $this->_database = $database;
        // to do: instantiate members representing substructures/blocks
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
    }

    /**
     * Generates an HTML block embraced by a div-tag with the submitted id.
     * If the block contains other blocks, delegate the generation of their
     * parts of the view to them.
     *
     * @param string $id $id is the unique (!!) id to be used as id in the div-tag
     *
     * @param $pizzas
     * @return void
     */
    public function generateView($id = "", $pizzas)
    {
        $this->getViewData();
        if ($id) {
            $id = "id=\"$id\"";
        }
        echo "<div $id>\n";
        // to do: call generateView() for all members
        echo <<<EOT
        <section>
            <form action="../CustomerPage/customer.php" accept-charset="UTF-8" method="post">
            <h2>Warenkorb</h2>
            <label for="order" hidden>Bestelleung</label>
            <select name="selectedItems[]" size="6" tabindex="1" id="order" multiple required>
        EOT;
        foreach ($pizzas as $id => $pizzaData) {
            $name = htmlspecialchars($pizzaData[0]);
            $price = htmlspecialchars($pizzaData[1]);
            $imgAdres = htmlspecialchars($pizzaData[2]);
            echo <<<EOT
            <option value="$id">
            $name</option>
            EOT;
        }
        echo <<<EOT
        </select>
        <p>Gesamtpreis:  <span id="totalPrice">14,50</span> €</p>
        <label for="Address">Adresse</label>
        <input type="text" name="address" id="Address" size="30" maxlength="40" value="" placeholder="Ihre Adresse" required />
        <br>
        <input type="reset" value="Alle loeschen" />
        <input type="reset" value="Auswahl loeschen" />
        <input type="submit" value="Bestellen" />
        </form>
        </section>
        EOT;

        echo "</div>\n";
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this block is supposed to do something with submitted
     * data do it here.
     * If the block contains other blocks, delegate processing of the
     * respective subsets of data to them.
     *
     * @return void
     */
    public function processReceivedData()
    {
        // to do: call processData() for all members
    }
}
// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >