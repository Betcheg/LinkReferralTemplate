<?php

include 'db_connect.php';
include 'code_metier.php';

$numPerso; // Le numéro personnel (correspond à l'id à la fin du lien)
$nclic; // Le nombre de clic totalisé sur le lien

?>


<html>
<head>
	<link rel="stylesheet" type="text/css" href="css.css">
</head>

<body>

	<div id="menu" >
		<p/><center>
			<div id="lien_persoavant"> Votre lien perso: <br/></div>
			<div id="lien_perso">http://betcheg.xyz/clic/?id=<?php echo $numPerso;?> </div>

			<p/>

			Vous avez eu <span class="nombreclic"> <?php echo $nclic; ?></span> visites sur votre lien !<p/>

		</div>

		<p/>
		<div id="bandeaubas">

			<br><br>
			Il vous faut <span class="nombreclic">5</span> clics pour debloquer le contenu <br/>

			<?php

			// Exemple de verrouillage, ici le contenu est verrouillé tant que l'utilisateur n'a pas obtenu 5 clics
			if ($nclic >= 5)	{
				echo "<h1>Bravo</h1>";
			}
			else {
				echo "<b>Vous n'avez pas encore atteint les 5 ...</b>";
			}

			?>

			<p/>
		</div>
	</div>
</div>

</body>
</html>
