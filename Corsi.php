<?php
// 
// Esercitazione10
// Alberto Marini
//

// Importo la classe che interagisce con il DBMS e i Bean che vengono utilizzati
require_once("DBMS.php");
require_once("CorsoStudiBean.php");

// Recupero i parametri che mi aspetto in input
// e gestisco l'eventuale mancanza del parametro
if (is_null($_GET["idDip"])) {
   echo("Parametro idDip non specificato!");
   exit;
} else {
   $id = $_GET["idDip"];
}

$dbms = new DBMS();
?>
<html>
<head>
	<title>Corsi di studio di un dipartimento</title>
</head>
<body bgcolor="white">
<h2>Corsi di studio attivi gestiti dal <i><?=$dbms->getNomeDipart($id)?></i></h2>

<? 
$corsi = $dbms->getCorsiLaurea($id);
$all = $dbms->getAll($id);

if (!$corsi) {
	echo ("<p>Presso il dipartimento non sono attivi corsi di laurea</p>");
} else {
?>
<h2>
<?   echo("CORSI DI LAUREA:"); ?>
</h2>

<?
	foreach($corsi as $cs) { 
        foreach($all as $all){
?>
	<br>
<?    //cerco le lauree
    if(($all->get_Id_tipocs() == 5 || $all->get_Id_tipocs() == 20 )&&($all->get_CreditiTot() > 1 && $all->get_NumIns() > 1)) {   
        
?>
	<li><b><?=$all->get_Codice() ?></b> - <i><?=$all->get_NomeCorso() ?></i></br>

    	<ul>
    
	    <li> <b> 
		<? echo ("Durata anni:");  ?>
		</b> <?= $all->get_Durata() ?> </li>

	    <li> <b> <? echo ("Sede:");  ?> </b> <?= $all->get_Sede() ?> </li>

	    <li> <b> <? echo ("Presentazione:");  ?></b> <br><br><?= $all->get_Informativa() ?> </li>

	    <li> <b> <? echo ("Numero insegnamenti attivi nell'anno 2013/2014:");  ?></b> <?= $all->get_NumIns() ?> </li>

	    <li> <b> <? echo ("Numero totale crediti erogati nell'anno 2013/2014:");  ?></b> <?= $all->get_CreditiTot() ?> </li>

	</ul>

<? 	}}} ?>

<?
$corsi = $dbms->getCorsiLaurea($id);
$all = $dbms->getAll($id); ?>

<h2> 
<? echo("CORSI DI LAUREA MAGISTRALE:"); ?> </h2><br>

<?
	foreach($corsi as $cs) { 
        foreach($all as $all){
?>

<?   //cerco le lauree magistrali     
    if(($all->get_Id_tipocs() == 25 || $all->get_Id_tipocs() == 23)&&($all->get_CreditiTot() > 1 && $all->get_NumIns() > 1)) {
?>

	<li><b><?=$all->get_Codice() ?></b> - <i><?=$all->get_NomeCorso() ?></i></br>

    <ul>
    
    <li> <b> <? echo("Durata anni:");?> </b> <?= $all->get_Durata() ?> </li>

    <li> <b> <? echo("Sede:");?></b> <?= $all->get_Sede() ?> </li>

    <li> <b> <? echo("Presentazione:");?></b> <br><br><?= $all->get_Informativa() ?> </li>

    <li> <b> <? echo("Numero insegnamenti attivi nell'anno 2013/2014:");?></b> <?= $all->get_NumIns() ?> </li>

    <li> <b> <? echo("Numero totale crediti erogati nell'anno 2013/2014:");?></b> <?= $all->get_CreditiTot() ?> </li>

    </ul>
        <? }}} ?>

<?
$corsi = $dbms->getCorsiLaurea($id);
$all = $dbms->getAll($id); ?>

<h2> 
<? echo("CORSI DI DOTTORATO:"); ?> </h2><br>

<?
	foreach($corsi as $cs) { 
        foreach($all as $all){

    //cerco i dottorati
    if($all->get_Id_tipocs() == 14) {
?>


	<li><b><?=$all->get_Codice() ?></b> - <i><?=$all->get_NomeCorso() ?></i></br>

    <ul>
    
    <li> <b> <? echo("Durata anni:");?> </b> <?= $all->get_Durata() ?> </li>

    <li> <b> <? echo("Sede:");?></b> <?= $all->get_Sede() ?> </li>

    <li> <b> <? echo("Presentazione:");?></b> <br><br><?= $all->get_Informativa() ?> </li>

    </ul>
        <? }}} ?>

<?
}
$dbms->closeConnection();
?>

</body>
</html>
