<?php
/**
 * Created by PhpStorm.
 * User: yabohe
 * Date: 16/7/30
 * Time: 下午3:37
 */

/**
 * Example Description
 * The website works heavily with AJAX. From time to time, it's necessary for the CD object to generate an XML version
 * of itself. This is returned to the JavaScript front end to be processed.
 */

class CD
{
    public $title = '';

    public $band = '';

    public function __construct($title, $band)
    {
        $this->title = $title;
        $this->band = $band;
    }

    public function getAsXML()
    {
        $doc = new DOMDocument();

        $root = $doc->createElement('CD');
        $root = $doc->appendChild($root);

        $title = $doc->createElement('TITLE', $this->title);
        $title = $root->appendChild($title);

        $band = $doc->createElement('TITLE', $this->band);
        $band = $root->appendChild($band);

        return $doc->saveXML();
    }
}

// To use this, the code is pretty straightforward
$externalTitle = 'Waste of Rib';
$externalBand = 'Never Again';

$cd = new CD($externalTitle, $externalBand);

print $cd->getAsXML();

/**
 * Example Description to continue
 * A new developer has joined the team. He has some great experience with AJAX and says that you could be doing this
 * a little bit better. Additional flexibility is needed for the website's AJAX functionality, including being able to
 * generate the CD as a JavaScript Object Notation(JSON) entity.
 *
 * Some web services may require a different format besides XML or JSON.
 * This is the perfect time to use the Strategy Design Pattern.
 */

class CDusesStrategy
{
    public $title = '';

    public $band = '';

    protected $_strategy; // an instance of this base class is being passed into the Strategy object.

    public function __construct($title, $band)
    {
        $this->title = $title;
        $this->band = $band;
    }

    public function setStrategyContext($strategyObject)
    {
        $this->_strategy = $strategyObject;
    }

    public function get()
    {
        return $this->_strategy->get($this); // replace the getAsXML() method, but more abstract depends on concrete strategy.
    }
}

class CDAsXMLStartegy
{
    public function get(CDusesStrategy $cd)
    {
        $doc = new DOMDocument();

        $root = $doc->createElement('CD');
        $root = $doc->appendChild($root);

        $title = $doc->createElement('TITLE', $cd->title);
        $title = $root->appendChild($title);

        $band = $doc->createElement('TITLE', $cd->band);
        $band = $root->appendChild($band);

        return $doc->saveXML();
    }
}

class CDAsJSONStartegy
{
    public function get(CDusesStrategy $cd)
    {
        $json = array();
        $json['CD']['title'] = $cd->title;
        $json['CD']['band'] = $cd->band;

        return json_encode($json);
    }
}

// Executing the code using the Strategy objects is not complicated.
$cd = new CDusesStrategy($externalTitle, $externalBand);

// xml output
$cd->setStrategyContext(new CDAsXMLStartegy());
print $cd->get();

// json output
$cd->setStrategyContext(new CDAsJSONStartegy());
print $cd->get();
