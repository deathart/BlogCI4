<?php

/**
 * Session language strings.
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
    'missingDatabaseTable'   => '`SessionSavePath` doit avoir le nom de table pour que le gestionnaire de session de base de données fonctionne.',
    'invalidSavePath'        => "Session : Le chemin d'enregistrement configuré'{0}' n'est pas un répertoire, n'existe pas ou ne peut pas être créé.",
    'writeProtectedSavePath' => "Session : Le chemin d'enregistrement configuré'{0}' n'est pas accessible en écriture par le processus PHP.",
    'emptySavePath'          => 'Session : Aucun chemin d\'enregistrement configuré.',
    'invalidSavePathFormat'  => 'Session : Le format de chemin d\'enregistrement Redis est invalide : {0}',
];
