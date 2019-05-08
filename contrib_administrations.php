<?php

/**
 * Installation/maj du plugin
 *
 * Crée les champs categorie et préfixe pour les rubriques
 *
 * @param string $nom_meta_base_version
 * @param string $version_cible
 */
function contrib_upgrade($nom_meta_base_version, $version_cible) {

	$maj = array();

	include_spip('inc/cextras');
	include_spip('base/contrib_declarations');
	cextras_api_upgrade(contrib_declarer_champs_extras(), $maj['create']);

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}

/**
 * Désinstalle les données du plugin
 *
 * Supprime les champs categorie et préfixe pour les rubriques
 *
 * @param string $nom_meta_base_version
 */
function contrib_vider_tables($nom_meta_base_version) {

	include_spip('inc/cextras');
	include_spip('base/contrib_declarations');
	cextras_api_vider_tables(contrib_declarer_champs_extras());

	// on efface la meta du schéma du plugin
	effacer_meta($nom_meta_base_version);
}
