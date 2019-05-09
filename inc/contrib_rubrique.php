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

	if (!isset($ids_secteur[$id_rubrique])) {
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


function rubrique_est_apropos($id_rubrique) {

	$est_apropos = false;

	include_spip('inc/config');
	$apropos = lire_config('secteur/exclure_sect', array());

	if ($apropos and in_array(rubrique_lire_secteur($id_rubrique), $apropos)) {
		$est_apropos = true;
	}

	return $est_apropos;
}


function rubrique_est_carnet($id_rubrique) {

	$est_carnet = false;

	include_spip('inc/config');
	$carnet = lire_config('autorite/espace_wiki', array());

	if ($carnet and in_array(rubrique_lire_secteur($id_rubrique), $carnet)) {
		$est_carnet = true;
	}

	return $est_carnet;
}
