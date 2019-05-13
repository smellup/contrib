<?php
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function contrib_formulaire_charger($flux) {

	if ($flux['args']['form'] == 'editer_article_accueil') {
		// Récupérer les classes attribuées
		$flux['data']['_saisies'] = array('publie');
		$flux['data']['_where'] = "type_article=''";
	}

	return $flux;
}
