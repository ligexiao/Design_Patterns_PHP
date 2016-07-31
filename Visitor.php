<?php
/**
 * Created by PhpStorm.
 * User: yabohe
 * Date: 16/7/31
 * Time: 上午10:29
 */

/**
 * Example Description
 * For auditing, each new CD purchase on your e-commerce website must be logged. The information is then archived.
 * In the case that there are ever any inconsistent inventory counts, the log file can be examined to see if a CD
 * was actually purchased.
*/
class CD
{
    public $title = '';

    public $band = '';

    public $price = '';

    public function __construct($title, $band, $price)
    {
        $this->title = $title;
        $this->band = $band;
        $this->price = $price;
    }

    public function buy()
    {
        // stub
    }


    public function acceptVisitor($visitor)
    {
        $visitor->visitCD($this);
    }
}

class CDVisitorLogPurchase
{
    public function visitCD($cd)
    {
        $logline = "{$cd->title} by {$cd->band} was purchased for {$cd->price}";
        $logline .= "at " . sdate('r') . "\n";

        file_put_contents('/logs/purchases.log', $logline, FILE_APPEND);
    }
}

// To purchase this CD and log the sale, the following code is used:
$externalTitle = 'Waste of a Rib';
$externalBand = 'Never Again';
$externalPrice = 9.99;

$cd = new CD($externalTitle, $externalBand, $externalPrice);
$cd->buy();
$cd->acceptVisitor(new CDVisitorLogPurchase());

/**
 * Example Description to continue
 * A new study was released that said that Visitors who view the home page are actually looking for discount CDs instead
 * of the standard-priced ones. Because of this, it was decided that the font page should have a live updated list of
 * CDs that were purchased that were discounted. A discounted CD is considered to be a CD that is under $10.
*/

class CDVisitorPopulateDiscountList
{
    public function visitCD($cd)
    {
        if ($cd->price < 10) {
            $this->_populateDiscountList($cd);
        }
    }

    protected function _populateDiscountList($cd)
    {
        // stub connects to sqlite and logs
    }
}

// The purchasing code has this view Visitor added
$cd = new CD($externalTitle, $externalBand, $externalPrice);
$cd->buy();
$cd->acceptVisitor(new CDVisitorLogPurchase());
$cd->acceptVisitor(new CDVisitorPopulateDiscountList());