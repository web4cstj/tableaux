<?php
include "debug.inc.php" ;
?><!DOCTYPE html>
<head>
<title>Tableaux</title>
<meta charset="utf-8" />
<style>
	var {
		font-weight: bold;
		color: #997700;
	}
</style>
</head>

<body>
<div style="width:600px;margin:0 auto;">
<h1 style="text-align:center;">Manipulation de tableaux (array)</h1>
<?php
echo "<ol>";

echo '<li><p>Inclure le fichier "<code>debug.inc.php</code>" et utiliser la fonction '.
	'<code>trace</code> pour faire l\'affichage</p></li>';

echo '<li><p>Trouver, au besoin, la fonction de manipulation adéquate sur '.
	'<a href="http://php.net/manual/fr/ref.array.php" target="_blank">php.net</a></p>';

// Création de la variable
echo '</li><li><p>Créer la variable <var>$prenoms</var></p>';
$prenoms = array("André", "Bruno", "Charles", "David", "Étienne", "Fabrice", "Gaston");
trace($prenoms);

// Affichage du nombre d'éléments
echo '</li><li><p>À l\'aide de <var>$prenoms</var>, faire afficher ceci:</p>';
trace(count($prenoms));

// Ajout à la fin
echo '</li><li><p>Transformer <var>$prenoms</var> en ceci:</p>';
array_push($prenoms, "Henri");
trace($prenoms);

// Inversion de l'ordre du tableau
echo '</li><li><p>Transformer <var>$prenoms</var> en ceci:</p>';
$prenoms = array_reverse($prenoms);
trace($prenoms);

// Ajout au début
echo '</li><li><p>Transformer <var>$prenoms</var> en ceci:</p>';
array_unshift($prenoms, "Igor");
trace($prenoms);

// Suppression de la fin
echo '</li><li><p>Transformer <var>$prenoms</var> en ceci:</p>';
array_pop($prenoms);
trace($prenoms);

// Brassage du tableau
echo '</li><li><p>Transformer <var>$prenoms</var> en ceci:</p>';
shuffle($prenoms);
trace($prenoms);

// Choix d'une clé au hasard
echo '</li><li><p>Créer la variable <var>$unprenom</var> en utilisant <code>array_rand</code>:</p>';
$unprenom = array_rand($prenoms);
trace($unprenom);

// Affichge de l'élément correspondant à la clé trouvée précédemment
echo '</li><li><p>Transformer <var>$unprenom</var> en ceci:</p>';
$unprenom = $prenoms[$unprenom];
trace($unprenom);

// Vérification de la présence d'une certaine valeur
echo '</li><li><p>À l\'aide de <var>$unprenom</var> et de <var>$prenoms</var>, faire afficher ceci:</p>';
trace(in_array($unprenom, $prenoms));

// Interversion des clés pour les valeurs
echo '</li><li><p>Transformer <var>$prenoms</var> en ceci:</p>';
$prenoms = array_flip($prenoms);
trace($prenoms);

// Suppression d'un élément correspondant à une clé
echo '</li><li><p>Utiliser <code>unset($prenoms[$unprenom])</code>:</p>';
unset($prenoms[$unprenom]);
trace($prenoms);

// Vérification de la présence d'une certaine valeur
echo '</li><li><p>À l\'aide de <var>$unprenom</var> et de <var>$prenoms</var>, faire afficher ceci:</p>';
trace(array_key_exists($unprenom, $prenoms));

// Réinversion de l'ordre du tableau
echo '</li><li><p>Transformer <var>$prenoms</var> en ceci:</p>';
$prenoms = array_reverse($prenoms);
trace($prenoms);
echo '</li></ol>';
?>
</div>
</body>
</html>
