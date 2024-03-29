<?php
/*
============================================================================================
Filename: 
---------
xml2json_test.php

Description: 
------------
The code in this PHP program is just a test driver that exercises the XML to JSON converter
class. This PHP program reads the contents of a filename passed in the command line and 
converts the XML contents in that file to JSON formatted data.

This program takes a XML filename as a command line argument as shown below:
php -f xml2json_test.php test1.xml

License:
--------
This code is made available free of charge with the rights to use, copy, modify,
merge, publish and distribute. This Software shall be used for Good, not Evil.

First Created on:
-----------------
Oct/04/2006

Last Modified on:
-----------------
Oct/07/2006
============================================================================================
*/
require_once("xml2json.php");

// Filename from where XML contents are to be read.


// Read the filename from the command line.
/*if ($argc <= 1) {
	print("Please provide the XML filename as a command-line argument:\n");
	print("\tphp -f xml2json_test.php test1.xml\n");
	//return;
} else {
	$testXmlFile = $argv[1];
}*/

$testXmlFile = "test1.xml";
//Read the XML contents from the input file.
//file_exists("test2.xml") or die('Could not find file ' . $testXmlFile);
$xmlStringContents = file_get_contents($testXmlFile);
$jsonContents = "";

// Convert it to JSON now. 
// xml2json simply takes a String containing XML contents as input.
$jsonContents = xml2json::transformXmlStringToJson($xmlStringContents);

echo($jsonContents);
?>