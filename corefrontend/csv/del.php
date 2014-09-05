<?php

require_once dirname(__FILE__) . '/lib/CsvImportInterface.php';
require_once dirname(__FILE__) . '/lib/CsvImport.php';
require_once dirname(__FILE__) . '/lib/CsvExportInterface.php';
require_once dirname(__FILE__) . '/lib/CsvExport.php';

    $import = new CsvImport();
    $import->setFile('read.csv',500,"\t");
    print_r($import->getRows());
    print_r($import->getHeader());
