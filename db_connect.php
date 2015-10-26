<?php
try
{
  $bdd = new PDO('mysql:host=VOTRE_HOTE;dbname=NOM_DE_LA_BDD', 'NOM_UTILISATEUR', 'MOT_DE_PASSE');
}
  catch(Exception $e)
{
  die('Erreur : '.$e->getMessage());
}
?>
