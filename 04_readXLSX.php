<?php
/* Variablen aus index.php * /
$data 	= array(); // verwendete Daten
$table 	= array(); // ausführliche Daten
$dataA 	= array(); // Assoziativ
/**/
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
	
// Abkürzungen 
# $abrr = array(); //  abbreviation
$s = array(); $e = array();
foreach($table["Abkürzungen"] as $v) {

	#$abrr[$k] = $v;
	$s[] = " ".$v[1]." "; $e[] = " ".$v[2]." ";
	}
	


// Assoziatives Array in Variablen für die Berechnung konvertieren
// treffen mehrere Zeilen der Excel-Datei zu, gewinnt die letzte Zeile
$vars 			= Array() ;  // alle eingelesenen Variablen ...
$varsDisplay	= "" ;  // ... mit Wert und Einheit (für Kontrollausgabe)
$toCalc			= Array(); // auzuführende Berechungen
foreach($dataA["Daten"] as $dateSet) {
	// für Berechnungen
	$vars[$dateSet['ET']."_".$dateSet['G']]	 = $dateSet['V'];
	$vars[$dateSet['ET']."_".$dateSet['G']."_U"]	 = $dateSet['U'];
	$vars[$dateSet['ET']."_".$dateSet['G']."_B"]	 = $dateSet['B'];
	$vars[$dateSet['ET']."_".$dateSet['G']."_Q"]	 = $dateSet['Q'];
	$vars[$dateSet['ET']."_".$dateSet['G']."_Gtxt"]	 = $dateSet['Gtxt'];
	
	
	// um Berechnung durchzuführen
	if($dateSet['B'] == 'CALC') $toCalc[] =  $dateSet['ET']."_".$dateSet['G'];
	
	// Für Anzeige
	if($dateSet['B'] == 'CALC') continue;
	 $varsDisplay .= "// ".$dateSet['ET']."_".$dateSet['G']." \t".$dateSet['V']; 
	if($dateSet['B'] == 'CALC') $varsDisplay .= " // in ";
	 $varsDisplay .= $dateSet['U']; 
	 if($dateSet['B'] and $dateSet['B'] != 'CALC') $varsDisplay .= " // ".$dateSet['B'];
	 if($dateSet['Z']) $varsDisplay .= " // ".$dateSet['Z'];
	 if($dateSet['Q'])$varsDisplay .= " [".$dateSet['Q']."]";
	 $varsDisplay .= "<br>";
    }
foreach($vars as $k => $v) { $$k = $v; } // wird in der calc-Funktion global


$calcMethod = ""; // zum Nachvollziehen
foreach($toCalc as $v) { 
	// z.B. $calcMethod .= CALC("BK_StrN");
	// schreibt gleichzeitig die globalen Variablen
	$calcMethod .=  CALC($v); 
	} 
?>