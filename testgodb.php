<?php

$startscript = microtime(true);

print "\033[1m****************************************\033[0m\n";
print "\033[1mRUNNING IMPORTING E-MAILS FROM CSV FILES\033[0m\n";
memUsage();
print "\033[1m****************************************\033[0m\n";

require_once 'vendor/autoload.php';
include_once 'corefrontend/goDB/autoload.php';
include_once 'corefrontend/project.php';
require_once 'corefrontend/csv/lib/CsvImportInterface.php';
require_once 'corefrontend/csv/lib/CsvImport.php';

use Egulias\EmailValidator\EmailValidator;
$dbs = dbGoDb::goDBsetup(); //Init DB


$importdir = 'import';
$filescsv = glob("$importdir/*.csv", GLOB_BRACE);
if ($filescsv) {
    print "Founded " . count($filescsv) . " files in import folder\n";

    //Iteration for all files
    foreach ($filescsv as $file) {
        $csvresult = getFileCSV($file);
        if ($csvresult) {
            print "Unique data in array...\n";
            print "Rows before unique: ".count($csvresult)."\n";
            $startread = microtime(true);
            $csvresult = array_map("unserialize", array_unique(array_map("serialize", $csvresult)));
            timeExec($startread);
            print "Rows after unique: ".count($csvresult)."\n";
            importCSV($csvresult,$dbs);
        } else {
            print "Nothing to importing...\n";
        }
        unset ($csvresult);
        unset ($file);
    }

//    gc_collect_cycles();

} else {
    print "No files for import...\n";
}

//End
print "\n\n\033[1m****************************************\033[0m\n";
print "\033[1mAll operation completed. End of work :-)\033[0m\n";
gc_collect_cycles();
timeExec($startscript);
print "\033[1m****************************************\033[0m\n";


/***************************************/

function getFileCSV($file)
{
    print "\n**************************\nRead CSV file " . $file. "\n";
    $import = new CsvImport();
    $startread = microtime(true);
    $import->setFile($file, 254, "\t");
    print "Reading OK.\n";
    timeExec($startread);
    print "Import data from file to array...\n";
    $starttime = microtime(true);
    $csvresult = $import->getRows();
    timeExec($starttime);
    print "Importing OK...";
    unset($import);
    return $csvresult;
}

function importCSV($csvresult,$dbs)
{
    $validator = new EmailValidator;
//    $importcount = count($csvresult);
    $mails = array();

    print "Validating E-mail...\n";
    $starttime = microtime(true);
    foreach ($csvresult as $item) {
        $email = $item['email'];
        if ($validator->isValid($email)) {
            $mails[] = $item['email'];
        }
    }
    print "Validating OK...\n";
    timeExec($starttime);

    $starttime = microtime(true);
    print "\033[36mStarting Database importing...\033[0m\n";
//    $importcount = count($mails);
    print "Importing ".count($mails)." rows...\n";
    $userData = array();
    foreach ($mails as $user) $userData[] = '("'.$user.'")';
//    $mails = implode(",",$userData);
    $dbs->query("INSERT IGNORE INTO subscribers (email) VALUES ".implode(",",$userData)."");     //Importing in DB
    print "\033[36mDatabase importing OK...\033[0m\n";
    timeExec($starttime);
}


function timeExec($starttime) {
    $exectime = round(microtime(true) - $starttime, 2);
    print "\t\033[32mOperation Time: " . $exectime . " sec\033[0m\n";
    memUsage();
}

function memUsage() {
    print  "\t\033[1mMemory Usage: " . convert(memory_get_usage()) . "\033[0m\n";
}


function convert($size)
{
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . @$unit[$i];
}
