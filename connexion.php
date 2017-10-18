<?php
try
{
	$pdo = new PDO('mysql:host=localhost;dbname=article_creator', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
}
catch(Exception $e)
{
	exit('<b>Catched exception at line '. $e->getLine() .' :</b> '. $e->getMessage());
}
