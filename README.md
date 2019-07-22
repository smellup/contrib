# Introduction

Le chantier "**Refonte de Contrib**" consiste à réaménager entièrement le site SPIP-Contrib et voire d’y intégrer le site Plugins SPIP. Cela revient entre autres choses à :

* effectuer un nettoyage des articles de Contrib (archives, obsolescence...) et à pérenniser une solution simple permettant d'éviter l'engorgement des articles obsolètes dans le futur;
* revoir la sectorisation du site au plus haut niveau et intégrer une nouvelle catégorisation des plugins;
* simplifier la "synchronisation" avec Plugins SPIP voire intégrer les pages de Plugins SPIP dans Contrib;
* Organiser les autres contributions (carnet, projets...) pour leur offrir une meilleure visibilité et ergonomie;
* Améliorer la visibilité des squelettes et des thèmes;
* Améliorer la recherche de tous les éléments précités.

Tous ces objectifs sont discutés dans un groupe Framavox dédié à ce chantier et constitué d’une quinzaine de membres. Le ***look & feel* du site de Contrib n’est pas concerné par ce chantier** car la mise à jour avec le squelette Galactic est récente et fait consensus dans la communauté.

# Organisation des rubriques de Contrib

## Spécifications

La refonte de Contrib passe d’abord par une réorganisation des secteurs du site afin qu’ils correspondent d’une part, à la volonté éditoriale de la communauté SPIP et d’autre part, aux objectifs de robustesse, de clarification et d’accessibilité souhaités. Les spécifications qui suivent correspondent aux conclusions des actions 2, 3 et 8 du chantier.

### Les secteurs

L’idée de base qui gouverne cette nouvelle structuration consiste à distinguer: 

* Les **secteurs-plugin**, les contributions relatives aux plugins de SPIP qui constituent aujourd’hui la majeure partie de la documentation; 
* Le "**Carnet Wiki**", les contributions du Carnet Wiki dont le le caractère "en chantier" ne correspond pas à la logique éditorial des autres contributions même si les thèmes abordés peuvent être communs;
* Le secteur "**Galaxie SPIP**", les contributions liées à la vie de SPIP et de sa communauté;
* Le secteur "**A propos de Contrib**", l’aide sur le contenu du site et son utilisation.

Cette sectorisation ne remet pas en cause le profil éditorial actuel de Contrib mais s’attache juste à fournir une structure plus stricte et compréhensible.

### Les secteurs-plugin

Ces secteurs contiendront la majeure partie des contributions actuelles du site. L’objectif est de structurer ces secteurs en suivant une nouvelle catégorisation des plugins de façon à simplifier et pérenniser l’affectation des contributions dans le site. Cette nouvelle catégorisation des plugins est basée sur une arborescence à deux niveaux qui couvrira mieux l’ensemble des contributions SPIP que la catégorisation actuelle (voir la feuille "Proposition" de la Google Sheet [Liste des catégories](https://docs.google.com/spreadsheets/d/1DQsCdnjnM5Q4CB1hlZr1Zhww39ocXDTljZXHtj8IKpc/edit?usp=sharing)).

Le niveau des secteurs-plugin correspond au premier niveau de la nouvelle arborescence des plugins, par exemple, ```auteur```, ```communication```, etc. Chaque secteur est subdivisé en autant de rubriques que de sous-catégories de niveau 2, par exemple, pour la catégorie ```auteur```, ```auteur/connexion```, ```auteur/extension```, ```auteur/inscription```, etc. Cette arborescence est créée une fois pour toute et ne peut plus être modifiée (sauf à modifier l’arborescence des catégories). Ces rubriques sont appelées  des "**rubriques-catégorie**".
Les rubriques-catégorie peuvent posséder des articles qui décrivent des concepts liés à la catégorie en question.

Dans chaque catégorie de niveau 2, un plugin documenté de la catégorie est représenté par une rubrique qui porte le nom du plugin. Tous les articles inclus dans une telle "**rubrique-plugin**" sont réputés décrire le plugin.

### Le secteur “Carnet Wiki”

Ce secteur, appelé par la suite **secteur-carnet**, correspond au secteur homonyme du site actuel. Pour ce secteur, l’objectif de la refonte est d’abord de nettoyer l’existant et de transférer dans les secteurs-plugin les articles aboutis qui sont parfois référencés dans les ```paquet.xml```.

En outre, il pourra être possible de plaquer le rubricage sur celui des secteurs-plugin niveau 1. Une fonctionnalité de transfert du Carnet Wiki vers les secteurs-plugin sera proposée.

### Le secteur "Galaxie SPIP"

Ce secteur, appelé par la suite **secteur-galaxie**, sera construit à partir des secteurs "Documentation", "S’orienter dans la documentation de SPIP" et "Vie de SPIP et "autour de SPIP" du site actuel. Le rubriquage sera adapté après le nettoyage des articles devenus obsolètes (voir le chapitre sur le nettoyage).

### Le secteur "A propos de Contrib"

Ce secteur, appelé par la suite **secteur-apropos**, sera surement entièrement réécrit car il entend répondre aux questions des utilisateurs sur le contenu et l’utilisation du nouveau Contrib. Son rubriquage sera adapté au sommaire de l’aide. Ce secteur sera exclu des boucles SPIP (plugin Exclure Secteur) et les articles identifiés par sélection (plugin Sélections éditoriales).

### Typologie des articles

Une typologie des articles est nécessaire car la refonte de Contrib va permettre de regrouper tout type d’article dans une rubrique. La typologie des articles est la suivante:

* les articles qui décrivent une conception - en général d’un plugin ou d’un site de la galaxie. On désignera ce type d’article par le terme **article-conception**;
* les articles qui informent sur une actualité d’un plugin ou de la galaxie. On désignera ce type d’article par le terme **article-actualite**;
* les articles qui documentent l’utilisation d’un plugin, le contenu du site et tout autre contribution liée à la vie de SPIP. On désignera ce type d’article par le terme **article-utilisation**. Par défaut, tout article possède ce type;

Cette typologie est principalement utile pour les secteurs-plugin mais les autres secteurs peuvent tout à fait en bénéficier. Le carnet Wiki par contre ne l’utilise pas.

Outre cette typologie, une notion d’**article principal** d’une rubrique sera mise en place. Un article principal identifie le point d’entrée d’une rubrique. Cet article est mis en exergue.
La mise en place de cet article dans les rubriques n’est pas obligatoire mais fortement conseillé en particulier pour les secteurs-plugin. Par cohérence, il est recommandé de remplir le lien de documentation d’un ```paquet.xml``` avec l’article principal.

### Notion de “Projet”

L’une des idées nouvelles de la refonte de Contrib est de permettre de lancer et de suivre des projets qui déboucheront, qui par un ou plusieurs plugins, qui par une refonte de site, qui par une autre contribution à SPIP. Le type article-conception est en particulier destiné à supporter la notion de projet. Un projet sera matérialisé par une **rubrique-projet** logée dans un secteur-plugin, dans le secteur-carnet (à confirmer) ou dans le secteur-galaxie. 

Dans le cas simple mais fréquent d’un projet de plugin, la rubrique-projet devra coïncider avec une rubrique-plugin, c’est-à-dire être intégrée au bon endroit dans l’arborescence des catégories. Une fois le plugin distribué via SVP, la rubrique-projet sera muée en une rubrique-plugin.

Un projet a donc une durée de vie limitée, il conviendra d’identifier son "statut de développement", à savoir, en cours ou clos.

## Conception

### Secteurs et rubriques

Les rubriques de Contrib possèderont des attributs spécifiques matérialisés par des champs additionnels à l’objet Rubrique (créés par Champs Extras ou manuellement dans le pipeline dédié). Ces champs permettent de reconnaître les rubriques-catégorie, les rubriques-plugins, les rubriques-projet et les autres rubriques (secteur-galaxie, secteur-carnet et secteur-apropos).

Le tableau suivant décrit l’affectation champ - type de rubrique, la chaîne vide représente toujours la valeur par défaut:

| Rubriques | Champ ```categorie``` | Champ ```prefixe``` |
| -------- | -------- | -------- |
| rubrique-categorie niveau 1 (secteur) | alias catégorie comme "auteur" | chaîne vide |
| rubrique-categorie niveau 2 | alias catégorie comme "auteur/connexion" | chaîne vide |
| rubrique-plugin | chaîne vide | Préfixe du plugin comme "a2a" |
| rubrique-projet | chaîne vide | "projet-<id_rubrique>" |
| autres rubriques | chaîne vide | chaîne vide |

Le secteur-carnet sera identifié comme actuellement par son secteur wiki via le plugin Autorisation.

Le secteur-galaxie sera a priori repéré par son id ou par défaut si le secteur n’est ni celui du Carnet Wiki ni un secteur-plugin.

Le secteur-apropos est exclu des boucles SPIP en utilisant le plugin Exclure Secteur. L’affichage d’un article précis se fera dans l’interface par une sélection éditoriale.


### Articles

La typologie des articles est également mise en place au travers d’un champ additionnel à l’objet Article nommé ```type_article``` qui contiendra les valeurs suivantes:

* "conception", pour un article-conception,
* "actualite", pour un article-actualité,
* "", pour les autres articles. C’est la valeur par défaut du champ.

La valeur "" est interprétée différemment suivant le secteur. Pour un secteur-plugin, cette valeur sera interprétée comme un article-utilisation. Pour le secteur-carnet ou le secteur-apropos c’est la seule valeur possible. Pour le secteur-galaxie l’interprétation est identique à celle des secteurs plugins.

La notion d’article principal est mise en place au travers de l’utilisation du plugin Article d’accueil.

### Autorisations

Pour s’assurer de la pérennité de l’organisation proposée sur laquelle s’appuiera des automatismes et des vérifications de cohérence, la création et la modification des rubriques sont sujettes à des autorisations qui dépendent du secteur concerné. il est possible que ces nouvelles autorisations remettent en cause celles configurées actuellement avec le plugin Autorité.

Les autorisations actuellement disponibles sur Contrib et qui ne sont pas redéfinies ci-dessous restent inchangées.


| Autorisation | Explication |
| -------- | -------- |
| Création, modification ou suppression d’une rubrique-catégorie | **webmestre** uniquement. Ces rubriques sont normalement créées une fois pour toute |
| Création, modification ou suppression d’une rubrique-plugin ou d’une rubrique-projet | **administrateur complet** et **webmestre**. Ces rubriques sont créées manuellement en fournissant le préfixe et après vérification de la cohérence du préfixe et de la catégorie ou automatiquement par transformation d’un projet de plugin (avec ou sans déplacement) |
| Création d’une rubrique dans une rubrique-plugin | **personne**. Les rubriques-plugin sont des feuilles. |
| Création d’un article hors secteur-carnet et secteur-apropos | **administrateur restreint** de la rubrique |
| Création d’un article du secteur-apropos | **administrateur complet** et **webmestre**. |

### Workflows

Pour s’assurer que des manipulations ne viendront pas casser l’organisation des rubriques, il est essentiel de motoriser les actions récurrentes au travers de workflows qui déclencheront des vérifications et/ou des automatismes de mise à jour.

Les workflows liés à la gestion des rubriques et des articles sont définis ci-dessous.

#### Documenter un plugin

Cette action renvoie vers un formulaire où le demandeur doit saisir le préfixe de son plugin:

* Si une rubrique-plugin avec ce préfixe existe déjà l’utilisateur est prévenu et renvoyé vers la rubrique (espace privé). En fonction de son profil il aura la possibilité de créer, modifier… un ou plusieurs articles
* Si aucune rubrique-plugin n’existe avec ce préfixe et que le préfixe est bien celui d’un plugin, la rubrique-plugin associée est créée automatiquement dans la bonne catégorie et un article-utilisation principal est créé avec l’utilisateur comme auteur.
* Si le préfixe n’est pas (encore) celui d’un plugin l’utilisateur est prévenu et le workflow s’arrête. 

#### Lancer la conception d’un plugin

Cette action renvoie vers un formulaire où le demandeur doit saisir le descriptif du projet. La liste des projets de plugin en cours est affiché pour que l’utilisateur puisse vérifier que sa demande n’est pas déjà prise en compte.

* Si l’utilisateur valide sa demande, le requête de projet-plugin est sauvegardée et mise en attente de validation par un administrateur complet.
* Si un administrateur valide la demande, la rubrique-projet est créée avec un article-conception pour lequel le demandeur est positionné comme auteur. Le demandeur est aussi positionné en administrateur restreint de la rubrique-projet.
* Si la demande est invalidée par un administrateur complet, le workflow s'arrête là.

Dans tous les cas, le demandeur est notifié de la réponse.

#### Clore la conception d’un plugin

Quand la conception est terminée et que le plugin est publié - donc connu de SVP - l’administrateur restreint de la rubrique-projet peut demander la clôture du projet et la promotion en rubrique-plugin.
Pour ce faire, il doit fournir le préfixe du plugin qui sera vérifié. 

* Si le préfixe est bien celui d'un plugin, la rubrique-projet est muée en rubrique-plugin (mise à jour du champ ```prefixe```) et un article-utilisation principal est créé avec le demandeur comme auteur (sauf si cet article a déjà été préparé);
* sinon, la promotion est refusée (a priori c'est une erreur de préfixe ou une anticipation de sa publication SVP).


#### Promouvoir un article du Carnet en article d’une rubrique-plugin

Le demandeur est renvoyé sur un formulaire où il doit saisir le préfixe du plugin.

* Si la rubrique-plugin n’existe pas le demandeur est notifié et le workflow s’arrête. Il faudra d’abord créer la rubrique-plugin avant de refaire une demande de promotion.
* Si la rubrique-plugin existe la demande de promotion est mise en attente d’une validation d’un administrateur complet ou de l’administrateur restreint de la rubrique-plugin destination. Une fois la demande validée, l’article est transféré vers la rubrique-plugin sans modification ni de son type ni d’aucune autre donnée. 

#### Promouvoir une rubrique du Carnet en rubrique-plugin

Le demandeur est renvoyé sur un formulaire où il doit saisir le préfixe du plugin qui est vérifié.
Si le préfixe est bien celui d'un plugin publié par SVP, la demande de promotion est mise en attente d’une validation d’un administrateur complet.
Une fois la demande validée, la rubrique-carnet est muée en rubrique-plugin (mise à jour du champ ```prefixe```), transférée dans sa catégorie d’accueil et un article-utilisation est créé avec le demandeur comme auteur si il n’en existe pas encore.

### Vérifications

Les rubriques-catégorie et les rubriques-plugin étant identifiées strictement par les champs ```categorie``` et ```prefixe```, il est possible de déclencher régulièrement ou à la demande (administrateurs complets) des vérifications et des mises à jour, à savoir:

* vérifier que les rubriques-plugin possèdent un préfixe existant et qu’elles sont positionnées dans la bonne catégorie;
* vérifier le titre des rubriques-catégorie et des rubriques-plugin et les mettre à jour à partir de la base SVP Typologie en profitant des traductions si elles existent. Le descriptif des rubriques-plugin peut aussi être mis à jour.
* vérifier que le préfixe est bien vide pour les rubriques-catégories. 

# Annexe 1 - Plugins utilisés

## SVP

Pour vérifier les autorisations, réaliser les vérifications et dérouler les workflows, l’utilisation des catégories et des préfixes nécessitent que le plugin SVP soit activé. En outre, pour construire la base complète de tous les plugins comme sur Plugins SPIP il faut:

1. configurer SVP dans le mode “non run-time” qui permet le chargement de l’ensemble des plugins disponibles qu’ils soient ou pas compatibles avec la version SPIP de Contrib.
1. intégrer dans la base les mêmes dépôts que sur Plugins SPIP.

En outre, le plugin SVP sera modifié et débarrassé de la gestion des catégories qui sera repris par le plugin SVP Typologie.

## SVP Typologie

Ce plugin permettra d'externaliser complètement la gestion des catégories de plugins (et aussi des tags de plugin).

## Exclure secteur

Ce plugin est utilisé pour exclure le secteur-apropos des boucles standard des squelettes. L’adressage de ses articles se fait en direct pour court-circuiter le plugin ou en utilisant le critère ```{tout}```.

## Champs Extras

Ce plugin est utilisé pour créer les champs additionnels des rubriques (```categorie``` et ```prefixe```) et des articles (```type_article```). Il permet aussi la gestion des autorisations et des affichages associés. 

## Article d’accueil

Ce plugin est utilisé pour définir l’article principal d’une rubrique, en particulier, pour les rubriques-plugin. 

## Sélections éditoriales

Ce plugin est utilisé pour identifier unitairement les articles d'aide du secteur-apropos. 

## Autorité

Ce plugin est utilisé pour identifier le secteur-carnet et lui associer les autorisations nécessaires. 

Ce plugin permettra d'externaliser complètement la gestion des catégories de plugins (et aussi des tags de plugin).

