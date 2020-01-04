<?php
/**
 * Ce fichier contient des compléments de l'API de gestion de l'objet plugin.
 *
 * @package SPIP\SVP\PLUGIN\API
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Renvoie, pour un plugin donné, la catégorie affectée ou la chaine vide si aucune affectation n'a encore été faite.
 * Il est possible de retourner uniquement la catégorie parent.
 *
 * @param int|string $plugin La valeur du préfixe ou de l'id du plugin.
 * @param string     $niveau Indique si l'on veut la catégorie complète ou uniquement l'identifiant
 *                           de la racine ou de la feuille. Prend les valeurs `''` par défaut, `racine` ou `feuille`.
 *
 * @return string Identifiant de la catégorie ou de la catégorie racine uniquement.
 */
function plugin_lire_categorie($plugin, $niveau = '') {

	// Initialisation à vide de la catégorie pour repérer les plugins non affectés.
	$categorie = '';

	// On détermine le préfixe du plugin qui est utilisé dans la table des affectations :
	// -- si c'est le préfixe on le passe en majuscules pour être cohérent avec le stockage en base.
	// -- sinon on lit le préfixe à partir de l'id du plugin.
	if ($id_plugin = intval($plugin)) {
		include_spip('inc/svp_plugin');
		$prefixe = plugin_lire($id_plugin, 'prefixe');
	} else {
		$prefixe = strtoupper($plugin);
	}

	// On récupère l'id du groupe pour la typologie catégorie.
	include_spip('inc/config');
	$id_groupe = lire_config('svptype/typologies/categorie/id_groupe', 0);

	// Cibler la catégorie par le préfixe du plugin et l'id du groupe représentant la typologie.
	$from = array(
		'spip_mots as m',
		'spip_plugins_typologies as pt'
	);
	$where = array(
		'pt.prefixe=' . sql_quote($prefixe),
		'pt.id_groupe=' . $id_groupe,
		'pt.id_mot=m.id_mot'
	);
	if (
		$categorie = sql_getfetsel('m.identifiant', $from, $where)
		and $niveau
	) {
		// On extrait la catégorie racine. Si elle existe, la catégorie d'un plugin est toujours
		// sous la forme xxx/yyy: on extrait xxx.
		$types_plugin = explode('/', $categorie);
		$categorie = $niveau == 'racine' ? $types_plugin[0] : $types_plugin[1];
	}

	return $categorie;
}
