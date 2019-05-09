<?php
/**
 * Gestion du formulaire de configuration du plugin
 *
 * @package SPIP\TAXONOMIE\CONFIGURATION
 */

if (!defined("_ECRIRE_INC_VERSION")) return;

/**
 * Chargement des données : le formulaire propose la liste des langues possibles.
 * L'utilisateur doit cocher les langues qu'il souhaite utiliser parmi les langues possibles.
 *
 * @return array
 * 		Tableau des données à charger par le formulaire (affichage ou données de configuration).
 * 		- `_langues`			: (affichage) codes de langue et libellés des langues possibles.
 * 		- `langues_utilisees`	: (configuration) la liste des langues utilisées. Par défaut, le plugin
 * 								  propose la langue française.
 */
function formulaires_configurer_contrib_charger() {

	$valeurs = array();

	// Liste des secteurs apropos et carnet pour les exclure car il ne peuvent pas être choisis.
	include_spip('inc/config');
	$exclusions = lire_config('secteur/exclure_sect', array());
	$exclusions = array_merge($exclusions, lire_config('autorite/espace_wiki', array()));

	// Sélection des secteurs pouvant être choisis pour la galaxie en excluant aussi les secteurs-plugin qui
	// sont ceux qui ont déjà une catégorie non vide.
	$from = 'spip_rubriques';
	$where = array(
		'profondeur=0',
		'categorie!=' . sql_quote(''),
		sql_in('id_rubrique', $exclusions, 'NOT')
	);
	$secteurs = sql_allfetsel('id_rubrique, titre', $from, $where);
	$valeurs['_secteurs'] = array_column($secteurs, 'titre', 'id_rubrique');

	return $valeurs;
}
