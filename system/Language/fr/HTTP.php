<?php

/**
 * HTTP language strings.
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
    // CurlRequest
    'missingCurl'                => 'CURL doit être activé pour utiliser la classe CURLRequest.',
    'invalidSSLKey'              => 'Impossible de définir la clé SSL. {0} n\'est pas un fichier valide.',
    'sslCertNotFound'            => 'Certificat SSL introuvable sur : {0}',
    'curlError'                  => '{0} : {1}',

    // IncomingRequest
    'invalidNegotiationType'     => '{0} n\'est pas un type de négociation valide. Doit être un de : média, charset, encodage, langue.',

    // Message
    'invalidHTTPProtocol'        => 'Version du protocole HTTP invalide. Ça doit être l\'un des : {0}',

    // Negotiate
    'emptySupportedNegotiations' => 'Vous devez fournir un tableau des valeurs soutenues à toutes les Négociations.',

    // RedirectResponse
    'invalidRoute'               => '{0, string} n\'est pas une route valide.',

    // Response
    'missingResponseStatus'      => 'Il manque un code d\'état à la réponse HTTP',
    'invalidStatusCode'          => '{0, chaîne} n\'est pas un code d\'état de retour HTTP valide.',
    'unknownStatusCode'          => 'Code d\'état HTTP inconnu fourni sans message : {0}',

    // URI
    'cannotParseURI'             => 'Incapable d\'analyser URI : {0}.',
    'segmentOutOfRange'          => 'Request URI segment est notre gamme de gamme : {0}',
    'invalidPort'                => 'Les ports doivent être compris entre 0 et 65535. Port Actuel : {0}',
    'malformedQueryString'       => 'Les chaînes de requête ne peuvent pas inclure des fragments URI.',

    // Page Not Found
    'pageNotFound'               => 'Page introuvable',
    'emptyController'            => 'Aucun contrôleur spécifié.',
    'controllerNotFound'         => 'Le contrôleur ou sa méthode n\'est pas trouvé : {0} ::{1}',
    'methodNotFound'             => 'La méthode du contrôleur n\'est pas trouvée : {0}',

    // CSRF
    'disallowedAction'           => 'L\'action que vous avez demandée n\'est pas autorisée.',
];
