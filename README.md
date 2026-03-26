## MaRCXMLMaker

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

<pre>
MaRC documentation here: http://www.loc.gov/marc/bibliographic/, or use the crib sheet below

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