<?php
/**
 * Ce fichier contient l'action `rubrique_textes_synchroniser` utilisée lors de la migration
 * pour synchroniser le titre et la description d'une rubrique-catégorie avec sa catégorie.
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Cette action permet de copier le titre et la description d'une catégorie dans les champs idoines
 * de la rubrique-catégorie associée si elle existe. Seuls les textes non vides sont copiés.
 *
 * Cette action est réservée aux webmestres. Elle ne nécessite aucun argument.
 *
 * @return void
 */
function action_rubrique_plugin_synchroniser_texte_dist($arguments = null) {

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

	// Récupération des rubriques-plugin idenfiées parce que leur préfixe est initialisé.
	include_spip('inc/contrib_rubrique');
	$filtres = array(
		'categorie' => '',
		'prefixe'   => '!'
	);
	$rubriques_plugin = rubrique_repertorier($filtres);

	// Pour chaque rubrique on recherche le titre et le slogan du plugin associé pour remplir respectivement
	// le titre, le descriptif et le texte de la rubrique.
	include_spip('inc/svp_plugin');
	include_spip('action/editer_objet');
	foreach ($rubriques_plugin as $_rubrique) {
		// Récupération des champs du plugin
		if ($plugin = plugin_lire($_rubrique['prefixe'])) {
			// Identification des modifications
			$set = array();
			// -- On traite le titre. Si le plugin possède un nom il est systématiquement utilisé.
			if ($plugin['nom']) {
				$set['titre'] = $plugin['nom'];
			}

			// -- On traite le descriptif et le texte:
			//    - Si le descriptif existe dans la rubrique et que le texte est vide on recopie le descriptif dans le texte
			//    - Ensuite, on ne le remplace le descriptif que si l'option de forçage est active ou que celui-ci est vide.
			if ($_rubrique['descriptif']) {
				// Recopie du descriptif
				if (!$_rubrique['texte']) {
					$set['texte'] = $_rubrique['descriptif'];
				}
				// Forçage du descriptif
				if ($forcer) {
					if ($plugin['slogan']) {
						$set['descriptif'] = $plugin['slogan'];
					}
				}
			} else {
				if ($plugin['slogan']) {
					$set['descriptif'] = $plugin['slogan'];
				}
			}

			// Si il y a un champ à modifier, on met à jour la rubrique.
			if ($set) {
				objet_modifier('rubrique', intval($_rubrique['id_rubrique']), $set);
			}
		}
	}

	// TODO : ajouter le déblocage de toutes les éditions de l'auteur
	rubrique_debloquer_edition($GLOBALS['visiteur_session']['id_auteur']);
}
