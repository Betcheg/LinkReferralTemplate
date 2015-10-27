<?php

$ipv = $_SERVER['REMOTE_ADDR']; // adresse ip du visiteur

// ETAPE 1 :

if (isset ($_COOKIE['id0']))    // Le cookie existe deja
{
	$cid = htmlspecialchars($_COOKIE['id0']);
}
else
{
	// DEBUT GENERATION CODE ALEATOIRE
	$characts    = 'abcdefghijklmnopqrstuvwxyz';
	$characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$characts   .= '1234567890';
	$code_aleatoire      = '';
	for($i=0;$i < 10;$i++)
	{
		$code_aleatoire .= substr($characts,rand()%(strlen($characts)),1);
	}
	// FIN GENERATION DU CODE ALEATOIRE

	$req = $bdd->prepare('INSERT INTO id (ip,aleatoire) VALUES(?,?)'); // Le cookie n'existe pas, on le cr��.
	$req->execute(array($ipv,$code_aleatoire));


	$reponse = $bdd->query("SELECT id,aleatoire FROM id WHERE ip = '$ipv'  ");
	$donnees = $reponse->fetch();
	$monid = $donnees['id'];
	$cid = $donnees['aleatoire'];

	setcookie('id0', $code_aleatoire, time() + 365*24*3600);

	$req = $bdd->prepare("UPDATE id SET idinitial = :monid WHERE aleatoire = '$code_aleatoire'  ");
	$req->execute(array('monid' => $monid));
}


// ETAPE 2

if (isset ($_GET['id']))
{
	$idi = $_GET['id'];
	$reponse = $bdd->prepare('SELECT id FROM id WHERE idinitial = ?')  ;
	$reponse ->execute(array($idi))  ;  //
	$donnees = $reponse->fetch();
	$idlevrai = $donnees['id'];

	if (isset ($idlevrai))
	{
		$today = date("Y-m-d");
		$reponse = $bdd->prepare('SELECT date FROM touteip WHERE idinitial = ? AND ip = ?')  ;
		$reponse ->execute(array($idi, $ipv))  ;  //
		$donnees = $reponse->fetch();
		$date_derniere_co = $donnees['date'];

		if(isset ($date_derniere_co))
		{

			if( $date_derniere_co < $today )
			{
				$reponse = $bdd->prepare('SELECT nclic FROM id WHERE idinitial = ? ')  ;
				$reponse ->execute(array($idi))  ;  // ON RECUPERE LE NOMBRE DE CLIC
				$donnees = $reponse->fetch();
				$nclic = $donnees['nclic'];


				$req = $bdd->prepare('UPDATE id SET nclic = :nclic WHERE idinitial = :id')  ;
				$req->execute(array('nclic' => $nclic+1, 'id' => $idi))   ;  // ON L'AUGMENTE DE 1


				$req = $bdd->prepare('UPDATE touteip SET date = :date WHERE ip = :ip AND idinitial = :id') ;
				$req->execute(array('date' => $today, 'ip' => $ipv, 'id' => $idi))  ;
				// echo " Date updat� + clic donn� ";

			}
			else
			{
				// echo " vous avez deja visit� ce lien odj ";
			}
		}

		else
		{

			$req = $bdd->prepare('INSERT INTO touteip (ip, date, idinitial) VALUES(:ip, :date, :idi)');
			$req->execute(array( 'ip' => $ipv, 'date' => $today,'idi' => $idi));


			$reponse = $bdd->prepare('SELECT nclic FROM id WHERE idinitial = ? ')  ;
			$reponse ->execute(array($idi))  ;  // ON RECUPERE LE NOMBRE DE CLIC


			$donnees = $reponse->fetch();
			$nclic = $donnees['nclic'];


			$req = $bdd->prepare('UPDATE id SET nclic = :nclic WHERE idinitial = :id')  ;
			$req->execute(array('nclic' => $nclic+1, 'id' => $idi))   ;  // ON L'AUGMENTE DE 1
			// echo " clic du premier jour ajout� !";

		}
	}

	else
	{
		// echo " L id n'existe pas dans la base ";
	}
}
else
{
	// echo " erreur aucun id sp�cifi�, getid n'a pas �t� trouv� ";
}


$reponse = $bdd->prepare('SELECT id,nclic FROM id WHERE aleatoire = ? ')  ;
$reponse ->execute(array($cid))  ;  // ON RECUPERE L'ID ET LE NOMBRE DE CLIC
$donnees = $reponse->fetch();
$numPerso = ($donnees['id'] == null) ? 0 : $donnees['id'];
$nclic = ($donnees['nclic'] == null) ? 0 : $donnees['nclic'];

?>
