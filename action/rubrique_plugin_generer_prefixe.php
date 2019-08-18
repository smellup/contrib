<?php
/**
 * Ce fichier contient l'action `rubrique_plugin_generer_prefixe` utilisée lors de la migration
 * pour actualiser le préfixe des rubrique-plugin à partir de l'url sur Plugins SPIP si elle existe.
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Cette action permet d'actualiser le préfixe des rubrique-plugin à partir de l'url
 * sur Plugins SPIP si elle existe.
 *
 * Cette action est réservée aux webmestres. Elle ne nécessite aucun argument.
 *
 * @return void
 */
function action_rubrique_plugin_generer_prefixe_dist() {

	// Securisation: aucun argument attendu.

	// Verification des autorisations
	if (!autoriser('webmestre')) {
		include_spip('inc/minipres');
		echo minipres();
		exit();
	}

	// Actualisation des rubriques-plugin :
	// Un rubrique-plugin a une profondeur de 2 et est incluse dans un secteur-plugin.
	// -- on récupère les secteurs-plugin
	include_spip('inc/contrib_rubrique');
	$secteurs_plugin = rubrique_lister_secteur_plugin();

	// Pour limiter le nombre de rubriques récupérées, on boucle par secteur.
	foreach ($secteurs_plugin as $_id_secteur) {
		// -- on récupère les rubriques-plugin
		$from = 'spip_rubriques';
		$where = array('id_secteur=' . intval($_id_secteur));
		$rubriques_plugin = sql_allfetsel('id_rubrique', $from, $where);
		if ($rubriques_plugin) {
			// Pour chaque rubrique-plugin on identifie si il existe un article possédant une url_site
			// pointant vers plugins spip car le basename est égal au préfixe (http[s]://plugins.spip.net/prefixe[.html]).
			include_spip('action/editer_objet');
			foreach ($rubriques_plugin as $_rubrique) {
				$from = 'spip_articles';
				$where = array('id_rubrique=' . intval($_rubrique['id_rubrique']));
				if ($urls = sql_allfetsel(array('url_site', 'id_article'), $from, $where)) {
					foreach ($urls as $_url) {
						$set = array();
						if ($_url['url_site']
						and ((stripos($_url['url_site'], 'https://plugins.spip.net/') === 0)
							or (stripos($_url['url_site'], 'http://plugins.spip.net/') === 0))) {
							$set['prefixe'] = basename($_url['url_site'], '.html');
							objet_modifier('rubrique', intval($_rubrique['id_rubrique']), $set);
						}
					}
				}
			}
		}
	}

	// TODO : ajouter le déblocage de toutes les éditions de l'auteur
	rubrique_debloquer_edition($GLOBALS['visiteur_session']['id_auteur']);
}
