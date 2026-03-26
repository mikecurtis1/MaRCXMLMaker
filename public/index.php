<?php 

require_once dirname(__FILE__) . '/../src/MaRCXMLMaker.php';
$mm = new MaRCXMLMaker();

$delimiter = "\t";

$data = array();

if (($handle = fopen("books.tsv", "r")) !== FALSE) {
    $headers = fgetcsv($handle, null, $delimiter);
    while ( $row = fgetcsv($handle, null, $delimiter) ) {
        $data[] = array_combine($headers, $row);
    }
}

foreach ($data as $i => $row) {
    
    $mm->buildLeader('book');
    $mm->buildControlfield('001', '10001235');

    if (!empty($row['isbn'])){
        $subfields = '';
        $subfields .= $mm->buildSubfield('a', $row['isbn']);
        $mm->buildDatafield('020', '', '', $subfields);
    }

    if (!empty($row['language'])){
        $subfields = '';
        $subfields .= $mm->buildSubfield('a', $row['language']);
        $mm->buildDatafield('041', '', '', $subfields);
    }

    if (!empty($row['author'])){
        $subfields = '';
        $subfields .= $mm->buildSubfield('a', $row['author']);
        $mm->buildDatafield('100', '', '', $subfields);
    }

    if (!empty($row['title'])){
        $subfields = '';
        $subfields .= $mm->buildSubfield('a', $row['title']);
        if (!empty($row['author'])){
            $subfields .= $mm->buildSubfield('c', $row['author']);
        }
        $mm->buildDatafield('245', '', '', $subfields);
    }

    if (!empty($row['pubdate'])){
        $subfields = '';
        $subfields .= $mm->buildSubfield('c', $row['pubdate']);
        $mm->buildDatafield('260', '', '', $subfields);
    }

    if (!empty($row['pages'])){
        $subfields = '';
        $subfields .= $mm->buildSubfield('a', $row['pages'] . ' p.');
        $mm->buildDatafield('300', '#', '#', $subfields);
    }

    if (!empty($row['description'])){
        $subfields = '';
        $subfields .= $mm->buildSubfield('a', $row['description']);
        $mm->buildDatafield('520', '', '', $subfields);
    }

    if (!empty($row['topic'])){
        $subfields = '';
        $subfields .= $mm->buildSubfield('a', $row['topic']);
        $mm->buildDatafield('650', '1', '0', $subfields);
    }

    $mm->addRec();

}

$collection =  '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . $mm->getCollection();

$mode = $_GET['mode'] ?? 'view';

switch ($mode) {
    case 'download':
        header('Content-Type: application/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="marc.xml"');
        echo $collection;
        break;

    case 'write':
        file_put_contents(__DIR__ . '/output/marc.xml', $collection);
        echo "File written.";
        break;

    case 'view':
    default:
        header('Content-Type: text/xml; charset=utf-8');
        echo $collection;
        break;
}
?>
