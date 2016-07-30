<?php
/**
 * Created by PhpStorm.
 * User: yabohe
 * Date: 16/7/30
 * Time: 下午5:45
 */

/**
 * Example Description
 * The e-commerce website has expended. Visitors to the website can now order many new items, including cases of cereal
 * that endorsed by the band.
 * Now, you can create classes that can hold the price for themselves as well as add tax. You used to be able to apply
 * a flat tax -- but new with food sales -- each class has to hold the tax for itself.
 * Additionally, some items are pretty large and these product require a premium to cover handling.
 * This is added to the final purchase price.
*/

abstract class SaleItemTemplate
{
    public $price = 0;

    public final function setPriceAdjustments()
    {
        $this->price += $this->taxAddition();
        $this->price += $this->oversizedAddition();
    }

    protected function oversizedAddition()
    {
        return 0;
    }

    abstract protected function taxAddition();
}

/**
 * Example Description to continue
 * The next step is to modify our CD object form its normal state as well as create a new cereal object
 */

class CD extends SaleItemTemplate
{
    public $title = '';

    public $band = '';

    public function __construct($title, $band, $price)
    {
        $this->title = $title;
        $this->band = $band;
        $this->price = $price; // inherited from parent
    }

    protected function taxAddition()
    {
        return round($this->price * 0.5, 2);
    }
}

class CerealEndorsedByBand extends SaleItemTemplate
{
    public $band = '';

    public $price = 0;

    public function __construct($band, $price)
    {
        $this->band = $band;
        $this->price = $price;
    }

    protected function taxAddition()
    {
        return 0;
    }

    protected function oversizedAddition()
    {
       return round($this->price * 0.20, 2);
    }
}

// The following code demonstrates how these classes are used
$externalTitle = "Waste of a Rib";
$externalBand = "Never Again";
$externalCDPrice = 12.99;
$externalCerealPrice = 90;

$cd = new CD($externalTitle, $externalBand, $externalCDPrice);
$cd->setPriceAdjustments();

print 'The total cost for CD item is: $'.$cd->price . '<br />';

$cereal = new CerealEndorsedByBand($externalBand, $externalCerealPrice);
$cereal->setPriceAdjustments();

print 'The total cost for Cereal case is: $'.$cereal->price . '<br />';