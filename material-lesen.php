<!doctype html>
<html lang="">
<head>
<meta http-equiv="cache-control" content="no-cache" />
<meta charset="utf-8">
<title>...</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
@import url('https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap');
body {font-family: 'Open Sans', sans-serif;}
th, td {border:1px solid black}
table {border-collapse:collapse; margin:0.5em 0;}

.firstLineAsHead tr:first-child   { font-weight: bold;}
caption { font-size:1em; text-align:left; font-weight: bold;}

h3 {font-size:1.3em;}
</style>

</head>
<body>

<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once 'SimpleXLSX.php';
$xlsxFile = 'material.xlsx';


$data 	= array(); // verwendete Daten
$table 	= array(); // ausführliche Daten
$dataA 	= array(); // Assoziativ

// Excel-Datei lesen
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
		// Datenarray schreiben: Assoziativ
		$keys = $xlsx->rows( $sheetNr )[2];
		
		# print_r($keys);
		$rNr = 3;
		foreach ( $xlsx->rows( $sheetNr ) as $r ) {
			if($r[0] < 2 ) continue; // deaktivierte Zeilen übergehen
			$dataA[$sheetName][++$rNr] = array();
			for ( $i = 0; $i < $num_cols; $i ++ ) {	
				if($selCol[$i] < 2) continue; // deaktivierte Spalten übergehen
				$dataA[$sheetName][$rNr][$keys[$i]] = $r[ $i ];
				}
			}
		}
	
	} else {  echo SimpleXLSX::parseError(); }
	

// Assiziatives Array in Variablen für die Berechnung konvertieren
// treffen mehrere Zeilen der Excel-Datei zu, gewinnt die letzte Zeile
$vars = Array() ;  // alle eingelesenen Variablen
foreach($dataA["Daten"] as $dateSet) {
	$vars[$dateSet['ET']."_".$dateSet['G']]	 = $dateSet['W'];
	$vars[$dateSet['ET']."_".$dateSet['G']."_E"]	 = $dateSet['E'];
	echo $dateSet['ET']."_".$dateSet['G'].": ".$dateSet['W']." ".$dateSet['E']."<br>"; 
    }
foreach($vars as $k => $v) { $$k = $v;}







// Abkürzungen
echo getTable("Abkürzungen",0);

/* Anhang */
echo "<h2>Anhang</h2>";
echo "EXCEL-Arbeitsblätter: ".implode($sheetArr,", ");

echo "<h3>Als Variablen erzeugt</h3>";
print_r($vars ); 
 echo "<br>";
 echo json_encode ($vars);

echo "<h3>Für Berechnung verwendet</h3>";

echo "<h4>Assoziativ: PHP-Array und json_encode</h4>";
 print_r($dataA);
 echo "<br>";
 echo json_encode ($dataA["Daten"]);


echo "<h4>als Tabelle</h4>";
echo getTable("Daten", 1, "data");

echo "<h4>Num: PHP-Array und json_encode</h4>";
print_r($data["Daten"]);
echo "<br>";
echo json_encode ($data["Daten"]);


echo "<h4>Daten ausführlich</h4>";
echo getTable("Daten");

echo getTable("Quellen");
/* Ende Anhang*/


// Funktionen
function getTable($me, $firstLineAsHead = 1, $source = "table") {
	global ${$source}; // Quelle? entsprechend Auswahl in der EXCEL-Datei
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