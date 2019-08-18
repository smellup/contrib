<?php
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

// fonction pour le pipeline, n'a rien a effectuer
function contrib_autoriser() {
}

/**
 * Autorisation minimale d'accès à toutes les pages du plugin Contrib.
 * Par défaut, seuls les administrateurs complets sont autorisés à utiliser le plugin.
 * Cette autorisation est à la base de la plupart des autres autorisations du plugin.
 *
 * @param $faire
 * @param $type
 * @param $id
 * @param $qui
 * @param $options
 *
 * @return bool
 */
function autoriser_contrib_dist($faire, $type, $id, $qui, $options) {
	return autoriser('defaut');
}

/**
 * Autorisation de modifier le champ extra catégorie d'une rubrique.
 * Il faut :
 * - être un webmestre,
 * - que la rubrique ait une profondeur égale à 0 ou 1,
 * - que le secteur ne soit ni un secteur-carnet, ni un secteur-apropos, ni un secteur-galaxie.
 * - et que si la rubrique est de profondeur 1, le secteur a déjà sa catégorie remplie.
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
 * @param mixed $opt
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
			// - le secteur galaxie
			include_spip('inc/contrib_rubrique');
			if (!rubrique_dans_secteur_apropos($id_rubrique)
			and !rubrique_dans_secteur_carnet($id_rubrique)
			and !rubrique_dans_secteur_galaxie($id_rubrique)) {
				// On vérifie la profondeur de la rubrique qui ne peut-être que 0 ou 1
				// et si 1, on vérifie que la rubrique parent a une catégorie non vide.
				$profondeur = rubrique_lire_profondeur($id_rubrique);
				if (($profondeur !== null)
				and ($profondeur < 2)) {
					if (($profondeur == 0)
					or (($profondeur == 1)
						and ($id_parent = rubrique_lire_parent($id_rubrique))
						and rubrique_lire_categorie($id_parent))) {
						$autoriser = true;
					}
				}
			}
		}
	}

	return $autoriser;
}

/**
 * Autorisation de modifier le champ extra préfixe d'une rubrique.
 * Il faut :
 * - être un webmestre,
 * - que la rubrique ait une profondeur égale à 2,
 * - que la catégorie de sa rubrique parente soit non vide (rubrique-categorie).
 * Cela implique que le préfixe ne peut être positionné que si les rubriques parentes
 * ont déjà une catégorie.
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
 * @param mixed $opt
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
			include_spip('inc/contrib_rubrique');
			$profondeur = rubrique_lire_profondeur($id_rubrique);
			if (($profondeur !== null)
			and ($profondeur == 2)) {
				// On vérifie que l'on est bien dans une rubrique-categorie ce qui implique la rubrique
				// parente possède une catégorie non vide (la rubrique parent n'est jamais un secteur du fait
				// de la profondeur).
				if ($id_parent = rubrique_lire_parent($id_rubrique)
				and rubrique_lire_categorie($id_parent)) {
					$autoriser = true;
				}
			}
		}
	}

	return $autoriser;
}

/**
 * Autorisation d'affichage du menu d'accès à gestion des typologies de plugin (page=svptype_typologie).
 * Il faut être autorisé à utiliser le plugin.
 *
 * @param $faire
 * @param $type
 * @param $id
 * @param $qui
 * @param $options
 *
 * @return bool
 */
function autoriser_contrib_menu_dist($faire, $type, $id, $qui, $options) {

	// Initialisation de l'autorisation
	$autoriser = autoriser('contrib');

	return $autoriser;
}
