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
