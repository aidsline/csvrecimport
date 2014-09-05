<?php

require_once dirname(__FILE__) . '/lib/CsvImportInterface.php';
require_once dirname(__FILE__) . '/lib/CsvImport.php';
require_once dirname(__FILE__) . '/lib/CsvExportInterface.php';
require_once dirname(__FILE__) . '/lib/CsvExport.php';


$export = new CsvExport();
    $export->setHeader(array('ids','username','age'));
    
    $export->append(
        array(
            array('id'=>1,'username'=>'Michael','age'=>25),
            array('id'=>2,'username'=>'Han','age'=>24)
        )
    );
    $export->append(
        array(
            array('id'=>3,'username'=>'Mike','age'=>25),
        ),true
    );
    $export->export('user.csv');
