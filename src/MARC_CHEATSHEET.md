# MARC Field Mapping Cheat Sheet

A practical reference for mapping common metadata labels to MARC21 fields, indicators, subfields, and leader values.

---

## Common Bibliographic Fields

| Label        | MARC Tag | Indicators | Subfields | Notes |
|--------------|----------|------------|-----------|-------|
| System ID    | 001      | —          | —         | Local system control number |
| Author       | 100      | 1_         | $a        | Personal author |
|              | 245      | __         | $c        | Statement of responsibility |
|              | 710      | __         | $a        | Corporate author |
| Title        | 245      | __         | $a        | Main title |
| Edition      | 250      | __         | $a        | Edition statement |
| Publisher    | 260      | __         | $b        | Publisher name |
| Pub Date     | 260      | __         | $c        | Date of publication |

---

## Physical Description (300 Field)

| Use Case     | Example |
|--------------|--------|
| Page count   | `300 ## $a408 p., [20] p. of plates : $bill. ; $c21 cm.` |
| Art/media    | `300 ## $a12 art reproductions : $bcol. ; $c17 x 23 cm.` |
| Audio disc   | `300 ## $a1 sound disc : $banalog, 33 1/3 rpm ; $c12 in.` |
| CD           | `300 ## $a1 compact disc (68 min.) : $bdigital ; $c4 3/4 in. + $e1 booklet` |

---

## Series

| Label                         | MARC Tag | Subfield |
|------------------------------|----------|----------|
| Series title                 | 490      | $a       |
| Series volume / designation  | 490      | $v       |

---

## Notes & Description

| Label            | MARC Tag | Subfield |
|------------------|----------|----------|
| General note     | 500      | $a       |
| Description      | 520      | $a       |
| Target audience  | 521      | $a       |

---

## Subjects & Genres

| Label                                 | MARC Tag | Indicators | Subfield |
|--------------------------------------|----------|------------|----------|
| Topic term (uncontrolled)            | 650      | _4         | $a       |
| Genre term (uncontrolled)            | 655      | _4         | $a       |
| Person as subject                    | 600      | 10         | $a,$d    |

**Example:**
```
600 10 $aMozart, Wolfgang Amadeus, $d1756-1791.
```

---

## Identifiers

| Label   | MARC Tag | Subfield |
|---------|----------|----------|
| LCCN    | 010      | $a       |
| ISBN    | 020      | $a       |
| ISSN    | 022      | $a       |
| System ID (alt) | 035 | $a    |
| Item ID (holdings) | 876 | $a |

---

## Language

| Label          | MARC Tag | Subfield | Notes |
|----------------|----------|----------|-------|
| Language code  | 041      | $a       | Uses MARC language codes |

Reference: http://www.loc.gov/marc/languages/language_name.html

---

## Classification

| System                | MARC Tag | Subfield | Notes |
|----------------------|----------|----------|-------|
| Library of Congress  | 050      | $a,$b    | Classification + item number |
| Dewey Decimal        | 082      | $a       | Classification number |

**Example (LC):**
```
050 _4 $aQH367.1
050 _4 $b.G37 2009eb
```

---

## Electronic Access (856 Field)

| Purpose         | MARC Encoding |
|-----------------|--------------|
| Link text       | 856 40 $3     |
| URL             | 856 40 $u     |

---

## Media / Format

| Label            | MARC Tag | Subfield | Notes |
|------------------|----------|----------|-------|
| File type        | 538      | $a       | DVD, CD, digital formats |
| Genre (ebook)    | 655      | _4 $a    | e.g. "Electronic books" |

---

## Leader Reference (Common Values)

| Position | Meaning                  | Example |
|----------|--------------------------|--------|
| 06       | Type of record           | a (language material) |
| 07       | Bibliographic level      | m (monograph) |

**Example leader:**
```
01323ckm a2200313Ia 4500
```

---

## Common Record Types

### Book (Print)

- Standard leader
- 300 field with physical pages

---

### eBook

| Field        | Value |
|--------------|-------|
| Leader       | `^^^^^^am^^^^^^^^^^^^^^^^` |
| 245 $h       | `[electronic resource]` |
| 300 $a       | `1 online resource` |
| 655 _4 $a    | `Electronic books` |
| 856          | URL + link text |

---

## Notes

- `__` = blank indicators  
- `_` = blank indicator (shown for clarity)  
- `$` = subfield delimiter  
- Some fields (e.g., 260) may be replaced by newer standards (e.g., 264), depending on cataloging rules  

---

## References

- MARC21 Format Documentation: http://www.loc.gov/marc/
- Holdings (876 field): http://www.loc.gov/marc/holdings/hd876878.html
