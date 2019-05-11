<?php

// On force le mode d'utilisation de SVP a non runtime car on veut presenter tous les
// plugins contenus dans les depots quelque soit leur compatibilite spip
if (!defined('_SVP_MODE_RUNTIME'))
	define('_SVP_MODE_RUNTIME', false);

// Liste des pages publiques d'objet supportees par le squelette (depot, plugin).
// Permet d'afficher le bouton voir en ligne dans la page d'edition de l'objet
if (!defined('_SVP_PAGES_OBJET_PUBLIQUES'))
	define('_SVP_PAGES_OBJET_PUBLIQUES', 'depot:plugin');

// Taille des listes et pas de pagination de la page sommaire
if (!defined('_PLUGINSPIP_TAILLE_SELECTION_PLUGINS'))
define('_PLUGINSPIP_TAILLE_SELECTION_PLUGINS', 10);
if (!defined('_PLUGINSPIP_TAILLE_TOP_PLUGINS'))
define('_PLUGINSPIP_TAILLE_TOP_PLUGINS', 30);
if (!defined('_PLUGINSPIP_TAILLE_MAJ_PLUGINS'))
define('_PLUGINSPIP_TAILLE_MAJ_PLUGINS', 30);
if (!defined('_PLUGINSPIP_PAS_TOP_PLUGINS'))
define('_PLUGINSPIP_PAS_TOP_PLUGINS', 5);
if (!defined('_PLUGINSPIP_PAS_MAJ_PLUGINS'))
define('_PLUGINSPIP_PAS_MAJ_PLUGINS', 5);
if (!defined('_SVP_PERIODE_ACTUALISATION_DEPOTS'))
define('_SVP_PERIODE_ACTUALISATION_DEPOTS', 1);

// Branche SPIP stable
if (!defined('_PLUGINSPIP_BRANCHE_STABLE'))
	define('_PLUGINSPIP_BRANCHE_STABLE', '3.2');

// Branches SPIP maintenues
if (!defined('_PLUGINSPIP_BRANCHES_MAINTENUES'))
	define('_PLUGINSPIP_BRANCHES_MAINTENUES', '3.2,3.1,3.0');

// Période de rafaichissement du cache autodoc
if (!defined('_PLUGINSPIP_TIMEOUT_AUTODOC'))
	define('_PLUGINSPIP_TIMEOUT_AUTODOC', 3600*24);

// Forcer l'utilisation de la langue du visiteur
$GLOBALS['forcer_lang'] = true;


function generer_titre_rubrique($id_rubrique, $contenu_objet) {

	include_spip('inc/utils');
	$titre = $contenu_objet['titre'];
	if (test_espace_prive()) {
		// On affiche le titre suivi de sa catégorie ou de son préfixe (exclusif)
		$titre .= $contenu_objet['categorie'] ? " ({$contenu_objet['categorie']})" : '';
		$titre .= $contenu_objet['prefixe'] ? " ({$contenu_objet['prefixe']})" : '';
	}

	return $titre;
}

include_spip('inc/contrib_rubrique');
