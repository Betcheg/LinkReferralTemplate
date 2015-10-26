<?php
try
{
	$bdd = new PDO('mysql:host=mysql.hostinger.com;dbname=u312214781_20', 'u312214781_20', 'aaaaaa64');
}
  catch(Exception $e)
{
  die('Erreur : '.$e->getMessage());
}
?>
