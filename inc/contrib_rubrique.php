<?php
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function rubrique_lire_profondeur($id_rubrique) {

	static $profondeurs = array();

	if (!isset($profondeurs[$id_rubrique])) {
		$from = 'spip_rubriques';
		$where = array('id_rubrique=' . intval($id_rubrique));
		$profondeurs[$id_rubrique] = sql_getfetsel('profondeur', $from, $where);
	}

	return $profondeurs[$id_rubrique];
}


function rubrique_lire_parent($id_rubrique) {

	static $ids_parent = array();

	if (!isset($ids_parent[$id_rubrique])) {
		$ids_parent[$id_rubrique] = 0;
		
		$from = 'spip_rubriques';
		$where = array('id_rubrique=' . intval($id_rubrique));
		$id = sql_getfetsel('id_parent', $from, $where);
		if ($id !== null) {
			$ids_parent[$id_rubrique] = $id;
		}
	}

	return $ids_parent[$id_rubrique];
}


function rubrique_lire_secteur($id_rubrique) {

	static $ids_secteur = array();

	if (!isset($ids_secteur[$id_rubrique])) {
		$ids_secteur[$id_rubrique] = 0;
		
		$from = 'spip_rubriques';
		$where = array('id_rubrique=' . intval($id_rubrique));
		$id = sql_getfetsel('id_secteur', $from, $where);
		if ($id !== null) {
			$ids_secteur[$id_rubrique] = $id;
		}
	}

	return $ids_secteur[$id_rubrique];
}


function rubrique_lire_categorie($id_rubrique) {

	static $categories = array();

	if (!isset($categories[$id_rubrique])) {
		$categories[$id_rubrique] = '';

		$from = 'spip_rubriques';
		$where = array('id_rubrique=' . intval($id_rubrique));
		$categorie = sql_getfetsel('categorie', $from, $where);
		if ($categorie !== null) {
			$categories[$id_rubrique] = $categorie;
		}
	}
	
	return $categories[$id_rubrique];
}


function rubrique_determiner_type($id_rubrique) {

	static $types = array();

	if (!isset($types[$id_rubrique])) {
		if (rubrique_dans_secteur_plugin($id_rubrique)) {
			$types[$id_rubrique] = 'plugin';
		} elseif (rubrique_dans_secteur_apropos($id_rubrique)) {
			$types[$id_rubrique] = 'apropos';
		} elseif (rubrique_dans_secteur_carnet($id_rubrique)) {
			$types[$id_rubrique] = 'carnet';
		} elseif (rubrique_dans_secteur_galaxie($id_rubrique)) {
			$types[$id_rubrique] = 'galaxie';
		} else {
			$types[$id_rubrique] = '';
		}
	}

	return $types[$id_rubrique];
}

function rubrique_determiner_couleur($categorie) {

	static $couleurs = array(
		'auteur' => '1310b2',
		'communication' => 'acbd70',
		'date' => '471bb2',
		'interactivite' => '50699b',
		'contenu' => 'b22ba4',
		'administration' => '09b2a3',
		'multimedia' => 'de175f',
		'navigation' => 'b26714',
		'developpement' => 'dfb811',
		'multilinguisme' => '11b23c',
		'activite' => 'bd87c0',
		'interface-publique' => '40dd5d',
	);

	$couleur = (!$categorie	or empty($couleurs[$categorie])) ? 'b9274d' : $couleurs[$categorie];

	return $couleur;
}

/**
 * Vérifie que la rubrique concernée fait bien partie du secteur-apropos.
 * Le secteur-apropos est déterminé par la configuration du secteur exclus dans
 * le plugin Exclure Secteur.
 *
 * @param int $id
 * 		Id de la rubrique concernée.
 *
 * @return bool
 *       True si la rubrique fait partie du secteur-apropos, false sinon.
 */
function rubrique_dans_secteur_apropos($id_rubrique) {

	$est_apropos = false;

	include_spip('inc/config');
	$apropos = lire_config('secteur/exclure_sect', array());

	if ($apropos and in_array(rubrique_lire_secteur($id_rubrique), $apropos)) {
		$est_apropos = true;
	}

	return $est_apropos;
}


/**
 * Vérifie que la rubrique concernée fait bien partie du secteur-carnet.
 * Le secteur-carnet est déterminé par la configuration de l'espace wiki dans le plugin
 * Autorité.
 *
 * @param int $id
 * 		Id de la rubrique concernée.
 *
 * @return bool
 *       True si la rubrique fait partie du secteur-carnet, false sinon.
 */
function rubrique_dans_secteur_carnet($id_rubrique) {

	$est_carnet = false;

	include_spip('inc/config');
	$carnet = lire_config('autorite/espace_wiki', array());

	if ($carnet and in_array(rubrique_lire_secteur($id_rubrique), $carnet)) {
		$est_carnet = true;
	}

	return $est_carnet;
}


/**
 * Vérifie que la rubrique concernée fait bien partie du secteur-carnet.
 * Le secteur-carnet est déterminé par la configuration de l'espace wiki dans le plugin
 * Autorité.
 *
 * @param int $id
 * 		Id de la rubrique concernée.
 *
 * @return bool
 *       True si la rubrique fait partie du secteur-carnet, false sinon.
 */
function rubrique_dans_secteur_galaxie($id_rubrique) {

	$est_galaxie = false;

	include_spip('inc/config');
	$galaxie = lire_config('contrib/secteurs', array());

	if ($galaxie and in_array(rubrique_lire_secteur($id_rubrique), $galaxie)) {
		$est_galaxie = true;
	}

	return $est_galaxie;
}


/**
 * Vérifie que la rubrique concernée fait bien partie d'un secteur-plugin.
 * Il suffit de vérifier que le secteur a bien une catégorie non vide.
 *
 * @param int $id
 * 		Id de la rubrique concernée.
 *
 * @return bool
 *       True si la rubrique fait partie d'un secteur-plugin, false sinon.
 */
function rubrique_dans_secteur_plugin($id_rubrique) {

	static $est_plugin = array();

	if (!isset($est_plugin[$id_rubrique])) {
		$est_plugin[$id_rubrique] = false;

		if (rubrique_lire_categorie(rubrique_lire_secteur($id_rubrique))) {
			$est_plugin[$id_rubrique] = true;
		}
	}
	
	return $est_plugin[$id_rubrique];
}

/**
 * Récupère les id de tous les secteurs-plugin.
 *
 * @return array
 *        Liste des id des secteurs-pllugin ou tableau vide.
 */
function rubrique_lister_secteur_plugin() {

	$from = 'spip_rubriques';
	$where = array('profondeur=0', 'categorie!=' . sql_quote(''));
	if ($secteurs = sql_allfetsel('id_rubrique', $from, $where)) {
		$secteurs = array_map('reset', $secteurs);
	}
	

	return $secteurs;
}
