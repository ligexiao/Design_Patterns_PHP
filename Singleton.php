<?php
/**
 * Created by PhpStorm.
 * User: yabohe
 * Date: 16/7/30
 * Time: 下午12:13
 */


/**
 * Example Description
 * Visitors to the CD Website can purchase more than one CD at a time. You should provide a shopping cart for them to
 * store their purchases in.
 * Since you work with live inventory, it is important to update the inventory listing as soon as the CDs are purchased.
 * To do this, you need to connect to the MYSQL database and update the quantity for that CD.
 * With your object-oriented approach, you could potentially create multiple connections to the database that are not needed.
 * Instead, the inventory connection is based on the Singleton Design Pattern.
*/

class InventoryConnection
{
    protected static $_instance = NULL;

    protected $_handle = NULL;

    public static function getInstance(){
        if (!self::$_instance instanceof self){
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    protected function __contruct(){
        $this->_handle = mysql_connect('localhost','user','pass');
        mysql_select_db('CD', $this->_handle);
    }

    public function updateQuantity($title, $band, $number){
        $query = "update CDS set amount=amount+" .intval($number);
        $query .= " where band='" .mysql_real_escape_string($band)."'";
        $query .= " and title='" .mysql_real_escape_string($title)."'";

        mysql_query($query, $this->_handle);
    }
}

class CD
{
    protected $_title = '';

    protected $_band = '';

    public function __construct($title, $band){
        $this->_title = $title;
        $this->_band = $band;
    }

    public function buy(){
        $inventory = InventoryConnection::getInstance();
        $inventory->updateQuantity($this->_title, $this->_band, -1);
    }
}

// The sample code to use these objects is probably pretty familiar.
$boughtCDs = array();
$boughtCDs[] = array('band'=>'Never Again', 'Waste of a Rib');
$boughtCDs[] = array('band'=>'Therapee', 'Long Road');

foreach ($boughtCDs as $boughtCD) {
    $cd = new CD($boughtCD['title'], $boughtCD['band']);
    $cd->buy();
}
