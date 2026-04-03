# MARCXMLMaker

MARCXMLMaker is a lightweight PHP utility for building MARCXML records programmatically.

It provides a simple, stateful interface for constructing MARC records piece by piece—handling leaders, controlfields, datafields, and subfields—without requiring a full MARC framework.

This project is designed for clarity and ease of use, making it suitable for small tools, scripts, and educational purposes.

---

## Features

- Simple object-oriented interface
- Incremental record construction
- Automatic XML escaping
- Support for:
  - Leader fields
  - Controlfields
  - Datafields with subfields
- Multiple record handling within a collection
- Minimal dependencies (pure PHP)

---

## Installation

Clone the repository or copy the class file into your project:

```bash
git clone https://github.com/mikecurtis1/MARCXMLMaker.git
```

Build Docker image and start container

```bash
docker build -t marcxmlmaker .
```

```bash
docker run -d -p 8080:80 -v $(pwd):/var/www/html marcxmlmaker
```

---

## Basic Usage

```php
<?php 

require_once 'src/MARCXMLMaker.php';

$mm = new MARCXMLMaker();

// Build leader
$mm->buildLeader(6, 'a');
$mm->buildLeader(7, 'm');

// Controlfield
$mm->buildControlfield('001', '10001234');

// Datafield with subfields
$mm->buildSubfield('a', '9780547750330');
$mm->buildDatafield('020');

$mm->buildSubfield('a', 'Schlosser, Eric');
$mm->buildDatafield('100');

$mm->buildSubfield('a', 'Fast Food Nation');
$mm->buildDatafield('245');

// Finalize record
$mm->addRec();

// Output collection
$collection = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . $mm->getCollection();

header('Content-Type: text/xml; charset=utf-8');
echo $collection;
```

XML response

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<collection>
    <record>
        <leader>^^^^^^am^^^^^^^^^^^^4500</leader>
        <controlfield tag="001">10001234</controlfield>
        <datafield tag="020" ind1="" ind2="">
            <subfield code="a">9780547750330</subfield>
        </datafield>
        <datafield tag="100" ind1="" ind2="">
            <subfield code="a">Schlosser, Eric</subfield>
        </datafield>
        <datafield tag="245" ind1="" ind2="">
            <subfield code="a">Fast Food Nation</subfield>
        </datafield>
    </record>
</collection>
```
---

## Simple web application

Place metadata into a csv/tsv file. (Demo tsv file including in the repo.)

```
http://localhost:8080/books.tsv
```

Upload the csv/tsv file to convert into MARC XML.

```
http://localhost:8080/form.html
```

---

## API Overview

### `buildLeader($pos = null, $str = '')`

- Sets or modifies the MARC leader (24 characters)
- If `$pos` is `null`, replaces the entire leader
- If `$pos` is an integer, inserts at that position
- Automatically pads with `^` to 24 characters

---

### `buildControlfield($tag, $value)`

Adds a controlfield to the current record.

```php
$mm->buildControlfield('001', '12345');
```

---

### `buildSubfield($code, $value)`

Queues a subfield to be included in the next datafield.

```php
$mm->buildSubfield('a', 'Example');
```

---

### `buildDatafield($tag, $ind1 = '', $ind2 = '')`

Creates a datafield using the currently queued subfields.

- Clears subfield buffer after execution
- Does nothing if no subfields are present

```php
$mm->buildDatafield('245', '1', '0');
```

---

### `addRec()`

Finalizes the current record:

- Prepends the leader
- Stores the record in the collection
- Resets internal buffers

---

### `getRec()`

Returns the current record field data wrapped in a `<record>` element.

---

### `getCollection()`

Returns all stored records wrapped in a `<collection>` element.

---

## MARC Field Mapping Cheat Sheet

A quick reference mapping common metadata terms (e.g., author, title, ISBN) 
to their corresponding MARC tags, indicators, subfields, and leader values 
is available here:

* [MARC Field Mapping Cheat Sheet](MARC_CHEATSHEET.md)

This is intended as a practical guide for building records with MARCXMLMaker.

---

## Design Notes

- The class is **stateful**: it accumulates fields until `addRec()` is called
- Subfields are buffered and attached only when `buildDatafield()` is invoked
- Internal buffers are reset after each record
- XML output is escaped using `htmlspecialchars` (ISO-8859-1)

---

## Limitations

- No schema validation
- No namespace support
- No MARC standard enforcement beyond structure
- Encoding is fixed to `ISO-8859-1` internally

---

## Use Cases

- Generating MARCXML for OAI-PMH repositories
- Lightweight metadata transformation scripts
- Educational tools for understanding MARC structure
- Prototyping metadata pipelines

---

## License

[![License: CC BY-NC-SA 4.0](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-lightgrey.svg)](https://creativecommons.org/licenses/by-nc-sa/4.0/)

This project is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.

---

## Author

Michael Curtis
