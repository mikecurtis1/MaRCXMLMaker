<?php 

// create instance
require_once dirname(__FILE__) . '/../src/MaRCXMLMaker.php';
$mm = new MarcXMLMaker();

// start with creating a MaRC leader tag
$mm->buildLeader(6, 'a');
$mm->buildLeader(7, 'm');
// build a controlfield
$mm->buildControlfield('001', '10001234');
// build the subfields that will contain data, then build the parent datafield
$mm->buildSubfield('a', '9780547750330');
$mm->buildDatafield('020', '', '');
$mm->buildSubfield('a', 'Schlosser, Eric');
$mm->buildDatafield('100', '', '');
$mm->buildSubfield('a', 'Fast Food Nation');
$mm->buildDatafield('245', '', '');
// add the record to the set
$mm->addRec();

// build and add another record
$mm->buildLeader(null, '^^^^^nam#a^^^^^^^z^^4500');
$mm->buildControlfield('001', '10001235');
$mm->buildSubfield('a', 'Kerouac, Jack');
$mm->buildDatafield('100', '', '');
$mm->buildSubfield('a', 'On the Road');
$mm->buildSubfield('h', 'by Jack Kerouac');
$mm->buildDatafield('245', '', '');
$mm->addRec();

// create collection string
$collection =  '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . $mm->getCollection();

// display XML
header('Content-Type: text/xml; charset=utf-8');
echo $collection;

?>