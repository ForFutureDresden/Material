<!doctype html>
<html lang="">
<head>
<meta http-equiv="cache-control" content="no-cache" />
<meta charset="utf-8">
<title>...</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
th, td {border:1px solid black}
table {border-collapse:collapse; margin:0.5em 0;}

.firstLineAsHead tr:first-child   { font-weight: bold;}
caption { font-size:1.5em; text-align:left; font-weight: bold;}

</style>

</head>
<body>

<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once 'SimpleXLSX.php';
$xlsxFile = 'material.xlsx';
# $xlsxFile = 'simplexlsx/books.xlsx';
# $xlsx = 'simplexlsx/countries_and_population.xlsx';

$data 	= array();
$table 	= array();

if ( $xlsx = SimpleXLSX::parse($xlsxFile)) {

	// Arbeitsblätter
	$sheetArr = $xlsx->sheetNames();
	$sheetArr = array_slice( $sheetArr, 0, 3); // Nur die ersten 3 Arbeitsblätter

	
	foreach ($sheetArr as $sheetNr => $sheetName) {
		$dim = $xlsx->dimension($sheetNr); $num_cols = $dim[0]; $num_rows = $dim[1];
		
		$table[$sheetName] = array();
		$data[$sheetName] = array();
		
		$selCol = $xlsx->rows( $sheetNr )[0];
		
		// TabellenArray schreiben: Zeilen/Spalten mit 1 oder 2 markiert
		$rNr = 0;
		foreach ( $xlsx->rows( $sheetNr ) as $r ) {
			if($r[0] != 1 && $r[0] != 2 ) continue; // deaktivierte Zeilen übergehen 
			$table[$sheetName][++$rNr] = array();
			for ( $i = 0; $i < $num_cols; $i ++ ) {	
				if($selCol[$i] != 1 && $selCol[$i] != 2) continue; // deaktivierte Spalten übergehen
				$table[$sheetName][$rNr][$i] = $r[ $i ];
				}
			}
		// Datenarray schreiben: Zeilen/Spalten mit 2 oder 3 markiert
		$rNr = 0;
		foreach ( $xlsx->rows( $sheetNr ) as $r ) {
			if($r[0] < 2 ) continue; // deaktivierte Zeilen übergehen
			$data[$sheetName][++$rNr] = array();
			for ( $i = 0; $i < $num_cols; $i ++ ) {	
				if($selCol[$i] < 2) continue; // deaktivierte Spalten übergehen
				$data[$sheetName][$rNr][$i] = $r[ $i ];
				}
			}
		
		}
	
	
	} else {  echo SimpleXLSX::parseError(); }

echo implode($sheetArr,", ");






echo getTable("Daten");
echo getTable("Daten", 1, "data");

echo getTable("Abkürzungen",0);
echo getTable("Quellen");







function getTable($me, $firstLineAsHead = 1, $source = "table") {
	global ${$source}; 
	$a = "\n<table class='$me";
	if($firstLineAsHead) {$a .= " firstLineAsHead";}
	$a .= "'><caption>$me</caption>";
	
	$me = ${$source}[$me];
	foreach($me as $row) {
		$a .="\n<tr><td>".implode($row,"</td><td>")."</td></tr>";
		}
	
	$a .= "\n</table>\n";
	return $a;
	}

?>

</body></html>