<?php 

require_once dirname(__FILE__) . '/../src/MARCXMLMaker.php';
$mm = new MARCXMLMaker();

$file = $_FILES['csv']['tmp_name'];

$delimiter = "\t";

$data = array();

if (($handle = fopen($file, "r")) !== FALSE) {
    $headers = fgetcsv($handle, null, $delimiter);
    while ( $row = fgetcsv($handle, null, $delimiter) ) {
        $data[] = array_combine($headers, $row);
    }
}

foreach ($data as $i => $row) {
    
    $mm->buildLeader(6, 'a');
    $mm->buildLeader(7, 'm');
    $mm->buildControlfield('001', '10001235');

    if (!empty($row['isbn'])){
        $mm->buildSubfield('a', $row['isbn']);
        $mm->buildDatafield('020', '', '');
    }

    if (!empty($row['language'])){
        $mm->buildSubfield('a', $row['language']);
        $mm->buildDatafield('041', '', '');
    }

    if (!empty($row['author'])){
        $mm->buildSubfield('a', $row['author']);
        $mm->buildDatafield('100', '', '');
    }

    if (!empty($row['title'])){
        $mm->buildSubfield('a', $row['title']);
        if (!empty($row['author'])){
            $mm->buildSubfield('c', $row['author']);
        }
        $mm->buildDatafield('245', '', '');
    }

    if (!empty($row['pubdate'])){
        $mm->buildSubfield('c', $row['pubdate']);
        $mm->buildDatafield('260', '', '');
    }

    if (!empty($row['pages'])){
        $mm->buildSubfield('a', $row['pages'] . ' p.');
        $mm->buildDatafield('300', '#', '#');
    }

    if (!empty($row['description'])){
        $mm->buildSubfield('a', $row['description']);
        $mm->buildDatafield('520', '', '');
    }

    if (!empty($row['topic'])){
        $mm->buildSubfield('a', $row['topic']);
        $mm->buildDatafield('650', '1', '0');
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
