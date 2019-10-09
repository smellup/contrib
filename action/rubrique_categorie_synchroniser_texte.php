<?php
/**
 * Ce fichier contient l'action `rubrique_categorie_synchroniser_texte` utilisée lors de la migration
 * pour synchroniser le titre et la description d'une rubrique-catégorie avec sa catégorie.
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Cette action permet de copier le titre et la description d'une catégorie dans les champs idoines
 * de la rubrique-catégorie associée si elle existe.
 *
 * Cette action est réservée aux webmestres. Elle accepte un argument optionnel pour forcer la mise à jour.
 *
 * @return void
 */
function action_rubrique_categorie_synchroniser_texte_dist($arguments = null) {

	// Récupération des arguments de façon sécurisée.
	if (is_null($arguments)) {
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arguments = $securiser_action();
	}

	// Le seul argument accepté est l'indicateur de forçage.
	$forcer = false;
	if ($arguments) {
		$forcer = true;
	}

	// Verification des autorisations
	if (!autoriser('webmestre')) {
		include_spip('inc/minipres');
		echo minipres();
		exit();
	}

	// Pour permettre de ranger les rubriques selon le classement alphabétique basé sur l'identifiant et donc
	// ajouter un numéro adéquat dans le titre, il faut synchroniser par niveau :
	// - les catégories de regroupement d'abord,
	// - et ensuite, pour chaque catégorie de regroupement, les catégories filles.

	// Récupération des catégories de regroupement (profondeur = 0). Les catégories sont déjà fournies avec
	// un tri alphabétique sur l'identifiant.
	include_spip('inc/svptype_type_plugin');
	$categories_meres = type_plugin_repertorier('categorie', array('profondeur' => 0));

	// On synchronise les rubriques mère et filles dans la même boucle.
	$rang_mere = 0;
	foreach ($categories_meres as $_categorie_mere) {
		// On synchronise la rubrique de regroupement. Le rang de la catégorie est toujours incrémenté que la rubrique
		$rang_mere++;
		rubrique_categorie_synchroniser($_categorie_mere, $rang_mere, $forcer);

		// Récupération des catégories filles
		$categories_filles = type_plugin_repertorier('categorie', array('id_parent' => $_categorie_mere['id_mot']));

		// On synchronise les rubriques de filles selon la même heuristique.
		$rang_fille = 0;
		foreach ($categories_filles as $_categorie_fille) {
			$rang_fille++;
			rubrique_categorie_synchroniser($_categorie_fille, $rang_fille, $forcer);
		}
	}

	// TODO : ajouter le déblocage de toutes les éditions de l'auteur
	include_spip('inc/contrib_rubrique');
	rubrique_debloquer_edition($GLOBALS['visiteur_session']['id_auteur']);
}

function rubrique_categorie_synchroniser($categorie, $rang, $forcer = false) {

	// Initialiser la sortie de la fonction à false qui indique qu'aucune mise à jour n'a été faite.
	$rubrique_synchronisee = false;

	// On récupère la rubrique-catégorie correspondante, si elle existe.
	$select = array('id_rubrique', 'titre', 'descriptif');
	$from = 'spip_rubriques';
	$where = array('categorie=' . sql_quote($categorie['identifiant']));
	if ($rubrique = sql_fetsel($select, $from, $where)) {
		// La rubrique existe :
		// - On traite le titre. Si la catégorie possède un titre différent de l'identifiant
		//   il est systématiquement utilisé.
		$set = array();
		if ($categorie['titre'] and ($categorie['titre'] != $categorie['identifiant'])) {
			$numero = 10 * $rang;
			$set['titre'] = "${numero}. {$categorie['titre']}";
		}

		// -- On traite le descriptif :
		//    on ne remplace le descriptif que si l'option de forçage est active ou que celui-ci est vide.
		if ($rubrique['descriptif']) {
			// Forçage du descriptif
			if ($forcer) {
				if ($categorie['descriptif']) {
					$set['descriptif'] = $categorie['descriptif'];
				}
			}
		} else {
			if ($categorie['descriptif']) {
				$set['descriptif'] = $categorie['descriptif'];
			}
		}

		// Si il y a un champ à modifier, on met à jour la rubrique.
		if ($set) {
			include_spip('action/editer_objet');
			objet_modifier('rubrique', intval($rubrique['id_rubrique']), $set);
			$rubrique_synchronisee = true;
		}
	}

	return $rubrique_synchronisee;
}
