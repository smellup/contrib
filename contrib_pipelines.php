<?php
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function contrib_formulaire_charger($flux) {

	// Personnalisation du formulaire de choix de l'article d'accueil
	if ($flux['args']['form'] == 'editer_article_accueil') {
		// Choisir la liste des statuts autorisés, le filtre sur le type d'article
		// et le titre du bloc
		$flux['data']['_statuts'] = array('prepa', 'prop', 'publie');
		$flux['data']['_where'] = "type_article=''";
		$flux['data']['_titre'] = _T('contrib:article_accueil_titre');
	}

	return $flux;
}

function contrib_affiche_droite($flux) {

	// Identification de la page et de l'objet
	$exec = $flux['args']['exec'];

	// Récupérer la liste des objets qui supporte une couleur
	include_spip('inc/config');
	$objets_config = lire_config('couleur_objet/objets', array());

	include_spip('inc/svptype_type_plugin');
	if (
		in_array('spip_mots', $objets_config) // si configuration objets ok
		and ($exec == 'type_plugin') // page d'un objet éditorial
		and ($id_objet = intval($flux['args']['id_mot']))
		and ($typologie = _request('typologie'))
		and ($typologie == 'categorie')
		and (type_plugin_lire($typologie, $id_objet, 'profondeur') == 0)
	) {
		$couleur = sql_getfetsel(
			'couleur_objet',
			'spip_couleur_objet_liens',
			array(
				'objet=' . sql_quote('mot'),
				'id_objet=' . $id_objet
			)
		);
		$contexte = array(
			'objet'         => 'mot',
			'id_objet'      => $id_objet,
			'couleur_objet' => $couleur
		);
		$flux['data'] .= recuperer_fond('inclure/couleur_objet', $contexte);
	}

	return $flux;
}

/**
 * Insertion dans le pipeline boite_infos (SPIP).
 * - Fiche objet d'un plugin :
 *   - Rajouter un lien privé vers la rubrique associée si elle existe.
 *   - Enrichir le préfixe avec la couleur de la catégorie du plugin.
 *
 * @pipeline boite_infos
 *
 * @param $flux array Le contexte du pipeline
 *
 * @return $flux array Le contexte du pipeline modifié
 */
function contrib_boite_infos($flux) {
	if (isset($flux['args']['type'])) {
		// Initialisation du type d'objet concerné.
		$objet = $flux['args']['type'];

		if (
			($objet_exec = trouver_objet_exec($objet))
			and !$objet_exec['edition']
			and ($objet == 'plugin')
			and ($id_plugin = intval($flux['args']['id']))
		) {
			// Page d'un plugin.

			// Ajout du bouton "voir la rubrique..."
			// -- On recherche le préfixe du plugin
			include_spip('inc/svp_plugin');
			$prefixe = plugin_lire($id_plugin, 'prefixe');

			// -- Inclure le bouton "voir la rubrique" si elle existe
			$contexte = array(
				'id_plugin' => $id_plugin,
				'prefixe'   => $prefixe,
			);
			if ($bouton = recuperer_fond('prive/squelettes/inclure/inc-bouton_voir_rubrique_plugin', $contexte)) {
				$flux['data'] .= $bouton;
			}

			// Coloration du préfixe du plugin
			// -- on recherche la catégorie du plugin
			include_spip('inc/contrib_plugin');
			$categorie = plugin_lire_categorie($prefixe, 'racine');

			if ($categorie) {
				// -- on ajoute au span une class décrivant la couleur de la catégorie
				$cherche = "/(<p[^>]*class=(?:'|\")prefixe[^>]*>\s*)(<span>)/is";
				if (preg_match($cherche, $flux['data'], $m)) {
					$flux['data'] = preg_replace(
						$cherche,
						'$1' . "<span class=\"couleur_${categorie}\">",
						$flux['data'],
						1
					);
				}
			}
		}
	}

	return $flux;
}
