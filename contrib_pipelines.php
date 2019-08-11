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

	if (
		in_array('spip_mots', $objets_config) // si configuration objets ok
		and ($exec == 'type_plugin') // page d'un objet éditorial
		and ($id_objet = intval($flux['args']['id_mot']))
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
