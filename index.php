<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

set_include_path("./");

include("01_header.php");
include("02_functions.php");
include('03_SimpleXLSX.php');


// Userinput
$kWh = 0;
checkInput() ;
?>
<!-- h1>Me in Your Back Yard</h1 -->
<h1>&#128119; Baustelle</h1>
<form method="get" >
<input type="number" name="kWh" id="kWh" placeholder="Dein Verbrauch" min="0"
value="<?php if($kWh) echo $kWh; ?>"

><label for="kWh"> kWh</label>
<input type="submit">
</form>

<p>z.B. Jahresverbrauch <a href="?kWh=2000">mein Haushalt</a> |
Jahresproduktion <a href="?kWh=1400000">WKA E40014</a> Nennleistung 500kW<br>
<small>ACHTUNG! Die Berechnung berücksichtigt keinen Strommix, keine Zwischenspeicher, kein Abfackeln. </small></p>

<?php


$data 	= array(); // verwendete Daten
$table 	= array(); // ausführliche Daten
$dataA 	= array(); // Assoziativ
$xlsxFile = 'material.xlsx';
include('04_readXLSX.php');

echo "<div class='tab'>";
echo  "<div><strong>".number_format($kWh, 0,".","&thinsp;")," kWh</strong> Energie entspricht </div>"; // number_format($kWh, 0,",",".") 

// genau Werte ohne Einheit ohne _Display $E_2BK;
echo $E_2BK_Display;
echo $E_2BK_Abr_Display;
echo $E_2BK_BF_Display;
echo $E_2BK_ReK_Display;
echo $E_2BK_CO2_Display;
echo $E_2BK_KS_Display;
echo $E_2BK_KSs_Display;

echo $E_2AK_CO2_Display;
echo $E_2AK_AM_Display;


echo "</div>";



echo "<div class='tab'>";
echo "<div>Zum Vergleich</div>";
echo $E_2W_CO2_Display;
echo "</div>";
echo "<small>// Verlängerte Laufzeit senkt die graue Energie!</small>";

echo "<div class='tab'>";
echo "<div>Zum Vergleich</div>";
echo $BK_KS∕kWh_Display;
echo $BK_KSs∕kWh_Display;

echo $W_KS∕kWh_Display;
echo $W_KSs∕kWh_Display;

echo "</div>";

/**/
echo "<small> <br>.... zum Vergleich Verkaufspreis incl. Steuern und Abgaben ???";
echo "<br>  Privatverbraucher ".($kWh * 0.3)."€ 	// @30ct/kWh";
echo "<br>  Industrie Großkunde ".($kWh * 0.035)."€ 	// @3,5ct/kWh ?? Direkteinkauf Börse ?? ";
echo  "aktueller Börsenpreis Strom/CO2 ...";
echo "</small>";
/**/


echo "<hr><pre class='calcMethod'>\$calcMethod Berechnung, Zwischenergebnisse gerundet // nicht gerundet\n".$calcMethod."</pre>";



// Anhang
// Abkürzungen
echo "<pre>\$varsDisplay Variablen-Anzeige  ".$varsDisplay."</pre>";


echo getTable("Abkürzungen",0);


echo getTable("Daten", 1, "data");

echo "<h4>Daten ausführlich</h4>";
echo getTable("Daten");

echo getTable("Quellen");
/* Ende Anhang*/


echo "<h2>PHP 2 JS </h2>
<h3>einzelne Variablen</h3>
< script ... // variablen schreiben
<pre>";
echo "\$E_2BK_Abr_Display: $E_2BK_Abr_Display";
echo "htmlentities: ".htmlentities($E_2BK_Abr_Display)."<br>";
echo "\$E_2BK_Abr: $E_2BK_Abr";
echo "</pre>";


$devArr = array(
'data["Daten"] – Die Excel-Tabelle',
 'data – alle Tabellen, verwendete Daten',
 'dataA – verwendete Daten Assoziativ',
 'vars – Die PHP-Variablen',
 'varsDisplay – Die Variablen lesbar',
 'calcMethod – Berechnung',
 );
$dev = 0;
if(isset($_POST["dev"])) $dev = $_POST["dev"] * 1;
?>
<form id="dev" method="post" 
action="<?php 
echo $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"]."#dev"; 
?>">
<select name="dev">
<?php
foreach($devArr as $k => $v){
	echo "<option value='$k' ";
	if($k == $dev) echo "selected";
	echo ">";
	echo  '$'.$v;
	echo "</option>";
}
?>
</select><input type="submit">
</form>
<?php

$devTxt = explode(" – ", $devArr[$dev]);
if($dev == 0) $test = $data["Daten"]; else $test = ${$devTxt[0]};
$testNotiz = $devTxt[1];

echo "<h4>$testNotiz // json_encode</h4>";
echo json_encode ($test);
echo "<hr><b>$".$devTxt[0]."</b> PHP-Array für json_encode <input type='checkbox'>pre<div>";
print_r($test);
echo "</div>";

/**/

?>



</body></html>