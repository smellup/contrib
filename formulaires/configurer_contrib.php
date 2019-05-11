<?php
/**
 * Gestion du formulaire de configuration du plugin
 *
 * @package SPIP\TAXONOMIE\CONFIGURATION
 */

if (!defined("_ECRIRE_INC_VERSION")) return;

/**
 * Chargement des données : le formulaire propose la liste des secteurs disponibles (hors apropos, carnet et plugin).
 * L'utilisateur doit choisir le ou les secteurs qui corresponderont à la partie Galaxie du site.
 *
 * @return array
 * 		Tableau des données à charger par le formulaire (affichage ou données de configuration).
 * 		- `_secteur_possibles` : (affichage) liste des secteurs disponibles (hors apropos, carnet et plugin).
 * 		- `secteurs`           : (configuration) liste des secteurs galaxie choisis.
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
		'categorie=' . sql_quote(''),
		sql_in('id_rubrique', $exclusions, 'NOT')
	);
	$secteurs = sql_allfetsel('id_rubrique, titre', $from, $where);
	$valeurs['_secteur_possibles'] = array_column($secteurs, 'titre', 'id_rubrique');

	// Récupération des secteurs déjà choisis.
	include_spip('inc/config');
	$valeurs['secteurs'] = lire_config('contrib/secteurs', array());

	return $valeurs;
}
