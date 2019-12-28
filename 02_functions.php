<?php
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
	
function CALC($me) {
	global $$me, $vars, $kWh, ${$me."_Display"}, $s, $e ;
	foreach($vars as $k => $v) {global $$k; }
	
	$CALC = $$me; // Die Berechnung
	$CALC = str_replace("$", "",$CALC);
	
	// zum Weiterrechnen: der genaue Wert, globale Variable
	$$me = eval("return(".$$me.");"); 
	
	// zum Anzeige formatiert mit Maßeinheit und Beschriftung
	$meF = $me; 
	$meR = roundRate($$me, 2); // gerundet
	if($meR > 999)  $meRT = number_format($meR, 0,".","&thinsp;"); else $meRT = $meR; // mit Tausender-Sparator
	${$me."_Display"} = "<div><b>". $meRT."&thinsp;".${$me."_U"}."</b>";
	$txt = "<span> ".${$me."_Gtxt"}." </span>";
	$txt = str_replace($s, $e, $txt);
	${$me."_Display"} .= " ".$txt."</div>";
	
	// zur Dokumentation des Rechenweges
	return("\n".$meF."\t= ".$CALC ."\t= ".$meR."".${$me."_U"}." \t // ".$$me  );

	}

// https://stackoverflow.com/questions/37618679/format-number-to-n-significant-digits-in-php
function roundRate($rate, $digits){
    $mod = pow(10, intval(round(log10($rate))));
    $mod = $mod / pow(10, $digits);
    $answer = ((int)($rate / $mod)) * $mod;
    return $answer;
	}

function checkInput() {
	global $a, $kWh;
	if($_GET) {
		if(!isset($_GET["kWh"]) ) return;
		$kWh = $_GET["kWh"] *1;
		
		}
	}
?>