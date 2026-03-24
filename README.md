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
