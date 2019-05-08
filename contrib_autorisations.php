<?php
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

// fonction pour le pipeline, n'a rien a effectuer
function contrib_autoriser(){}


/**
 * Autorisation de modifier la catégorie d'une rubrique.
 * Il faut :
 * - être un webmestre,
 * - que la rubrique ait une profondeur égale à 0 ou 1,
 * - que le secteur ne soit ni celui du carnet, ni celui de l'aide (apropos).
 *
 * @param $faire
 * 		L'action se nomme modifierextra
 * @param $type
 * 		Le type est toujours rubrique.
 * @param $id
 * 		Id de la rubrique concernée.
 * @param $qui
 * 		L'auteur connecté
 * @param $options
 *      Contient le contexte de la saisie mais n'est pas utilisé.
 *
 * @return bool
 */
function autoriser_rubrique_modifierextra_categorie($faire, $type, $id, $qui, $opt) {

	// Par défaut la modification est interdite.
	$autoriser = false;

	// Seuls les webmestres peuvent configurer la catégorie d'une rubrique.
	if (autoriser('webmestre')) {
		if ($id_rubrique = intval($id)) {
			// On vérifie si la rubrique est dans un secteur à exclure (non plugin).
			// - le carnet wiki
			// - le secteur apropos
			if (!rubrique_est_apropos($id_rubrique)
			and !rubrique_est_carnet($id_rubrique)) {
				// On vérifie la profondeur de la rubrique qui ne peut-être que 0 ou 1.
				$profondeur = rubrique_lire_profondeur($id_rubrique);
				if (($profondeur !== null)
				and ($profondeur < 2)) {
					$autoriser = true;
				}
			}
		}
	}

	return $autoriser;
}


/**
 * Autorisation de modifier le préfixe d'une rubrique.
 * Il faut :
 * - être un webmestre,
 * - que la rubrique ait une profondeur égale à 2,
 * - que le secteur ne soit secteur-plugin, donc que sa catégorie soit non vide.
 *
 * @param $faire
 * 		L'action se nomme modifierextra
 * @param $type
 * 		Le type est toujours rubrique.
 * @param $id
 * 		Id de la rubrique concernée.
 * @param $qui
 * 		L'auteur connecté
 * @param $options
 *      Contient le contexte de la saisie mais n'est pas utilisé.
 *
 * @return bool
 */
function autoriser_rubrique_modifierextra_prefixe($faire, $type, $id, $qui, $opt) {

	// Par défaut la modification est interdite.
	$autoriser = false;

	// Seuls les webmestres peuvent configurer le préfixe d'une rubrique-plugin.
	if (autoriser('webmestre')) {
		if ($id_rubrique = intval($id)) {
			// On vérifie la profondeur de la rubrique qui ne peut-être que 2.
			$profondeur = rubrique_lire_profondeur($id_rubrique);
			if (($profondeur !== null)
			and ($profondeur == 2)) {
				// On vérifie que l'on est bien dans une rubrique-plugin ce qui implique que le secteur ou la rubrique
				// parente possède une catégorie non vide.
				if ($id_secteur = rubrique_lire_secteur($id_rubrique)
				and rubrique_lire_categorie($id_secteur)) {
					$autoriser =true;
				}
			}
		}
	}

	return $autoriser;
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


function rubrique_lire_secteur($id_rubrique) {

	$id_secteur = 0;

	$from = 'spip_rubriques';
	$where = array('id_rubrique=' . intval($id_rubrique));
	$id = sql_getfetsel('id_secteur', $from, $where);
	if ($id !== null) {
		$id_secteur = $id;
	}

	return $id_secteur;
}


function rubrique_lire_categorie($id_rubrique) {

	$categorie = '';

	$from = 'spip_rubriques';
	$where = array('id_rubrique=' . intval($id_rubrique));
	$c = sql_getfetsel('categorie', $from, $where);
	if ($c !== null) {
		$categorie = $c;
	}

	return $categorie;
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
