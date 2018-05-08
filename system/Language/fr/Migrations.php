<?php

/**
 * Migration language strings.
 *
 * @package      CodeIgniter
 * @author       CodeIgniter Dev Team
 * @copyright    2014-2018 British Columbia Institute of Technology (https://bcit.ca/)
 * @license      https://opensource.org/licenses/MIT	MIT License
 * @link         https://codeigniter.com
 * @since        Version 3.0.0
 * @filesource
 */

return [
	// Migration Runner
	'missingTable'      => 'La table des migrations doit être mise en place.',
	'invalidType'       => 'Un type de numérotation de migration invalide a été spécifié : ',
	'disabled'          => 'Les migrations ont été chargées mais sont désactivées ou mal configurées.',
	'notFound'          => 'Le fichier de migration n\'a pas été trouvé : ',
	'empty'             => 'Aucun fichier de migration trouvé',
	'gap'               => 'Il y a une lacune dans la séquence de migration près du numéro de version : ',
	'classNotFound'     => 'La classe de migration "%s" n\'a pas pu être trouvée.',
	'missingMethod'     => 'Il manque à la classe de migration une méthode "%s".',

	// Migration Command
	'migHelpLatest'     => "\t\tMigrer la base de données vers la dernière migration disponible.",
	'migHelpCurrent'    => "\t\tMigrer la base de données vers la version définie comme 'actuelle' dans la configuration.",
	'migHelpVersion'    => "\tMigrer la base de données en version {v}.",
	'migHelpRollback'   => "\tExécute toutes les migrations vers la version 0.",
	'migHelpRefresh'    => "\t\tDésinstalle et réexécute toutes les migrations pour rafraîchir la base de données.",
	'migHelpSeed'       => "\tExécute le seeder nommé [name].",
	'migCreate'         => "\tCrée une nouvelle migration nommée [name]",
	'nameMigration'     => 'Nommez le fichier de migration',
	'badCreateName'     => 'Vous devez fournir un nom de fichier de migration.',
	'writeError'        => 'Erreur lors de la création d\'un fichier.',

	'toLatest'          => 'Migration vers la dernière version...',
	'migInvalidVersion' => 'Numéro de version fourni est non valide.',
	'toVersionPH'       => 'Migration vers la version %s...',
	'toVersion'         => 'Migration vers la version actuel...',
	'rollingBack'       => 'Faire reculer toutes les migrations...',
	'noneFound'         => 'Aucune migration n\'a été trouvée.',
	'on'                => 'Migrés le : ',
	'migSeeder'         => 'Nom du Seeder',
	'migMissingSeeder'  => 'Vous devez fournir un nom de seeder..',
	'historyFor'        => 'Historique de migration ',
	'removed'           => 'Retourn en arrière : ',
	'added'             => 'En cours : ',

	'version'           => 'Version',
	'filename'          => 'Filename',
];
