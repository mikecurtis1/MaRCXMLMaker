# MaRCXMLMaker

This class was originally developed as part of a one-off data transformation pipeline.

In that context, bibliographic records for a DVD collection were exported from a Firebird SQL database into a delimited text format. A custom PHP script then:

1. Parsed the exported data
2. Mapped fields to MARC21 tags
3. Used `MarcXMLMaker` to generate MARCXML records programmatically

The goal was to produce standards-compliant MARCXML suitable for ingestion into downstream library or archival systems.

After the initial migration, the MARCXML generation logic was extracted into this reusable class to separate:

* data parsing (input-specific)
* field mapping (domain-specific)
* MARCXML serialization (standards-specific)

This repository represents that reusable serialization layer.

## Basic MaRC-XML record structure

The structure of MARC records is an implementation of national and international standards, e.g., Information Interchange Format (ANSI Z39.2) and Format for Information Exchange (ISO 2709).

A MaRC-XML `record` is composed of a single `leader` element along with one or more `controlfield` and `datafield` elements. Both field types have a three digit numeric `tag` attribute. Datafield elements additionally have two indicator attributes - `ind1` and `ind2` - and contain one or more `subfield` child elements which have single character `code` attributes.

Example

```XML
<record>
	<leader>^^^^^^am^^^^^^^^^^^^^^^^</leader>
	<controlfield tag="001">00000001</controlfield>
	<datafield tag="245" ind1="" ind2="">
		<subfield code="a">On the Road</subfield>
		<subfield code="h">by Jack Kerouac</subfield>
	</datafield>
</record>
```

Complete documentation of tag, indicator, and subfield codes is available at the Library of Congress [MARC 21 Format for Bibliographic Data](https://www.loc.gov/marc/bibliographic/) page.

## Usage

This example demonstrates how to use the `MarcXMLMaker` class to build a simple MARCXML record collection.

---

### Step 1: Include the class and create an instance

Include the `MarcXMLMaker` class and create a new instance:

```php
require_once dirname(__FILE__) . '/../src/MaRCXMLMaker.php';
$mm = new MarcXMLMaker();
```

### Step 2: Add records

A record is built using the `buildLeader()`, `buildControlfield()`, `buildSubfield()`, and  `buildDatafield()` methods which add metadata to the private `$rec` property.

#### Build a MaRC leader tag 

* The leader is a fixed width 24 character string which encodes details about the record item.
* The `buildLeader()` function accepts plain English keywords to set character positions 6 and 7 indicating media and granularity of the record item.
* Keywords allowed:
  * book
  * book_chapter
  * magazine_issue
  * magazine_article
  * ebook
  * digital_video (DVD, mp4, etc.)
  * musical_recording (CD, mp3, 33 1/3 vinyl, etc.)
  * music_score (notated music)
  * website
  * physical_image (photographs, postcards, drawings, etc.)
  * physical_artifact (fossil, clothing item, taxidermy mount, etc.)

```php
$mm->buildLeader('book');
```

#### Build a control field

Control field tag `001` designates a `control number` an internal system ID number. Note, the `001` tag type is not used for ISBN data which is designated by tag `020` as demonstrated below. 

```php
$mm->buildControlfield('001', '10001234');
```

#### Build a datafield 

Build temp subfield data

```php
$subfields = '';
$subfields .= $mm->buildSubfield('a', '9780547750330');
```
Build the datafield with the subfield data

```php
$mm->buildDatafield('020', '', '', $subfields);
```

#### Build additional datafields

```php
$subfields = '';
$subfields .= $mm->buildSubfield('a', 'Schlosser, Eric');
$mm->buildDatafield('100', '', '', $subfields);
```

```php
$subfields = '';
$subfields .= $mm->buildSubfield('a', 'Fast Food Nation');
$mm->buildDatafield('245', '', '', $subfields);
```

#### Add the record to the records set

The `addRec()` method adds records to the private `$recs` property and empties `$rec` in preparation for a new record build.

```php
$mm->addRec();
```

#### Add a second record

```php
$mm->buildLeader('book');
$mm->buildControlfield('001', '10001235');
$subfields = '';
$subfields .= $mm->buildSubfield('a', 'Kerouac, Jack');
$mm->buildDatafield('100', '', '', $subfields);
$subfields = '';
$subfields .= $mm->buildSubfield('a', 'On the Road');
$subfields .= $mm->buildSubfield('h', 'by Jack Kerouac');
$mm->buildDatafield('245', '', '', $subfields);
$mm->addRec();
```
### Step 3. Output the MaRC-XML metadata

#### Create an XML string

```php
$collection =  '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . $mm->getCollection();
```

#### Output XML to a web browser 

```php
header('Content-Type: text/xml; charset=utf-8');
echo $collection;
```

### MaRC Notes

<pre>
MaRC documentation here: http://www.loc.gov/marc/bibliographic/, or use the crib sheet below

https://www.loc.gov/marc/bibliographic/bdapndxc.html

https://www.loc.gov/marc/ldr06guide.html
	
Two guides to the leader field
http://www.loc.gov/marc/bibliographic/bdleader.html
http://www.loc.gov/marc/ldr06guide.html

local system id number = 001
author = 100__a, 245__c, 710__a
title = 245__a
publisher = 260__b
pubdate = 260__c

page count = 300##a

300	##$a408 p., [20] p. of plates : $bill. ; $c21 cm. 

leader	01323ckm a2200313Ia 4500
300	##$a12 art reproductions : $bcol. ; $c17 x 23 cm.-27 x 36 cm. in portfolio 47 cm.

edition = 250__a
series title = 490_a
series volume or sequential designation = 490_v

general note = 500__a
description = 520__a
topic term (undefined controlled vocabulary) = 650_4a
genre term (undefined controlled vocabulary) = 655_4a
target audience = 521__a, MPAA rating, etc.

Person as topic
600	10$aMozart, Wolfgang Amadeus, $d1756-1791.

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

100	1#$aMozart, Wolfgang Amadeus, $d1756-1791.

MEDIA/FORMAT

300	##$a1 sound disc : $banalog, 33 1/3 rpm, stereo. ; $c12 in.
300	##$a1 compact disc (68 min.) : $bdigital, mono./stereo. ; $c4 3/4   in. + $e1 booklet. 

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
</pre>
