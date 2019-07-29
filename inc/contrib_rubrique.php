<?php
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Retourne la description complète ou une liste de champs précisés dans l'appel
 * d'un objet plugin identifié par son préfixe.
 *
 * @param int          $id_rubrique  Id de la rubrique.
 * @param array|string $informations Identifiant d'un champ ou de plusieurs champs de la description d'une rubrique.
 *                                   Si l'argument est vide, la fonction renvoie la description complète.
 *
 * @return mixed La description brute complète ou partielle de la rubrique :
 *               - sous la forme d'une valeur simple si l'information demandée est unique (chaine)
 *               - sous la forme d'un tabelau associatif indexé par le nom du champ sinon.
 */
function rubrique_lire($id_rubrique, $informations = array()) {

	// Initialisation du tableau statique des descriptions
	static $descriptions_rubrique = array();

	if (($id_rubrique = intval($id_rubrique))
		and !isset($descriptions_rubrique[$id_rubrique])) {
		// Initialisation des attributs de la requête.
		$from = array('spip_rubriques');
		$where[] = 'id_rubrique=' . $id_rubrique;

		// Acquisition de tous les champs du plugin et sauvegarde de celle-ci à l'index du préfixe.
		$descriptions_rubrique[$id_rubrique] = array();
		if ($description = sql_fetsel('*', $from, $where)) {
			$descriptions_rubrique[$id_rubrique] = $description;
		}
	}

	// On extrait la description complète maintenant que l'on sait qu'elle existe.
	$description = $descriptions_rubrique[$id_rubrique];

	// On ne retourne que les champs demandés
	if ($description and $informations) {
		// Extraction des seules informations demandées.
		// -- si on demande une information unique on renvoie la valeur simple, sinon on renvoie un tableau.
		// -- si une information n'est pas un champ valide elle n'est pas renvoyée sans monter d'erreur.
		if (is_array($informations)) {
			if (count($informations) == 1) {
				// Tableau d'une seule information : on revient à une chaine unique.
				$informations = array_shift($informations);
			} else {
				// Tableau des informations valides
				$description = array_intersect_key($description, array_flip($informations));
			}
		}

		if (is_string($informations)) {
			// Valeur unique demandée.
			$description = isset($description[$informations]) ? $description[$informations] : '';
		}
	}

	return $description;
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
		$types[$id_rubrique] = '';
		if (rubrique_dans_secteur_plugin($id_rubrique)) {
			// On vérifie que la rubrique est soit une catégorie de profondeur inférieure à 1, soit un plugin de
			// profondeur 2
			$rubrique = rubrique_lire($id_rubrique);
			if ((($rubrique['profondeur'] <= 1) and $rubrique['categorie'])
			or (($rubrique['profondeur'] == 2) and $rubrique['prefixe'])) {
				$types[$id_rubrique] = 'plugin';
			}
		} elseif (rubrique_dans_secteur_apropos($id_rubrique)) {
			$types[$id_rubrique] = 'apropos';
		} elseif (rubrique_dans_secteur_carnet($id_rubrique)) {
			$types[$id_rubrique] = 'carnet';
		} elseif (rubrique_dans_secteur_galaxie($id_rubrique)) {
			$types[$id_rubrique] = 'galaxie';
		}
	}

	return $types[$id_rubrique];
}

/**
 * Vérifie que la rubrique concernée fait bien partie du secteur-apropos.
 * Le secteur-apropos est déterminé par la configuration du secteur exclus dans
 * le plugin Exclure Secteur.
 *
 * @param int   $id
 *                           Id de la rubrique concernée.
 * @param mixed $id_rubrique
 *
 * @return bool
 *              True si la rubrique fait partie du secteur-apropos, false sinon.
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
 * @param int   $id
 *                           Id de la rubrique concernée.
 * @param mixed $id_rubrique
 *
 * @return bool
 *              True si la rubrique fait partie du secteur-carnet, false sinon.
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
 * @param int   $id
 *                           Id de la rubrique concernée.
 * @param mixed $id_rubrique
 *
 * @return bool
 *              True si la rubrique fait partie du secteur-carnet, false sinon.
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
 * @param int   $id
 *                           Id de la rubrique concernée.
 * @param mixed $id_rubrique
 *
 * @return bool
 *              True si la rubrique fait partie d'un secteur-plugin, false sinon.
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
 *               Liste des id des secteurs-pllugin ou tableau vide.
 */
function rubrique_lister_secteur_plugin() {

	// On sélectionne les rubriques de profondeur nulle et ayant une catégorie.
	$from = 'spip_rubriques';
	$where = array(
		'profondeur=0',
		'categorie!=' . sql_quote('')
	);
	if ($secteurs = sql_allfetsel('id_rubrique', $from, $where)) {
		$secteurs = array_column($secteurs, 'id_rubrique');
	}

	return $secteurs;
}
