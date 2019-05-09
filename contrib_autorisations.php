<?php
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

// fonction pour le pipeline, n'a rien a effectuer
function contrib_autoriser(){}


/**
 * Autorisation de modifier le champ extra catégorie d'une rubrique.
 * Il faut :
 * - être un webmestre,
 * - que la rubrique ait une profondeur égale à 0 ou 1,
 * - que le secteur ne soit ni celui du carnet, ni celui de l'aide (apropos).
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
			include_spip('inc/contrib_rubrique');
			if (!rubrique_est_apropos($id_rubrique)
			and !rubrique_est_carnet($id_rubrique)) {
				// On vérifie la profondeur de la rubrique qui ne peut-être que 0 ou 1.
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
 * - que la catégorie de sa rubrique parente soit non vide.
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
				// On vérifie que l'on est bien dans une rubrique-plugin ce qui implique que le secteur ou la rubrique
				// parente possède une catégorie non vide.
				if ($id_parent = rubrique_lire_parent($id_rubrique)
				and rubrique_lire_categorie($id_parent)) {
					$autoriser =true;
				}
			}
		}
	}

	return $autoriser;
}
