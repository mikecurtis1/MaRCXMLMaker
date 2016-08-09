<?php 

// create instance
require_once dirname(__FILE__) . '/../library/MarcXMLMaker.php';
$mm = new MarcXMLMaker();

// start with creating a MaRC leader tag
$mm->buildLeader('book');
// go ahead, add a controlfield!
$mm->buildControlfield('001', '10001234');
// build the subfields that will contain data, then build the parent datafield
$subfields = '';
$subfields .= $mm->buildSubfield('a', 'Fast Food Nation');
$mm->buildDatafield('245', '', '', $subfields);
// add the record to the set
$mm->addRec();

// add another record
$subfields = '';
$mm->buildLeader('book');
$mm->buildControlfield('001', '10001235');
$subfields .= $mm->buildSubfield('a', 'On the Road');
$subfields .= $mm->buildSubfield('h', 'by Jack Kerouac');
$mm->buildDatafield('245', '', '', $subfields);
$mm->addRec();

// create collection string
$collection =  '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . $mm->getCollection();

// write file
#$mm->makeFile('books.xml', $collection, 'w');

// display XML
header('Content-Type: text/xml; charset=utf-8');
echo $collection;

// MaRC documentation here: http://www.loc.gov/marc/bibliographic/, or use the crib sheet below

/*
local system id number = 001
author = 100__a, 245__c, 710__a
title = 245__a
publisher = 260__b
pubdate = 260__c

edition = 250__a
series title = 490_a
series volume or sequential designation = 490_v

general note = 500__a
description = 520__a
topic term (undefined controlled vocabulary) = 650_4a
genre term (undefined controlled vocabulary) = 655_4a
target audience = 521__a, MPAA rating, etc.

856403 = http link text
85640u = http link URL

010__a = LCCN
020__a = ISBN
022__a = ISSN

035__a = a system id number
876__a = an id number, see: http://www.loc.gov/marc/holdings/hd876878.html

041__a = language code, see: http://www.loc.gov/marc/languages/language_name.html

LC call number
050_4a|QH367.1 , classification number
050_4b|.G37 2009eb , item number

0820_a = Dewey classification number

MEDIA/FORMAT

538__a = file types, DVD, CD, etc.
655_4a|Electronic books.

???
book

ebook
	leader = ^^^^^^am^^^^^^^^^^^^^^^^
	245__h [electronic resource]
	300__a|1 online resource
	655_4a|Electronic books.
	856403 = http link text
	85640u = http link URL

pdf
mp3
jpeg
DVD
CD
VHS

*/

?>
