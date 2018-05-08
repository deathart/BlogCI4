<?php

/**
 * Validation language strings.
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
	// Core Messages
	'noRuleSets'            => 'Aucune règle n\'est spécifiée dans la configuration Validation.',
	'ruleNotFound'          => '{rule} n\'est pas une règle valide.',
	'groupNotFound'         => '%s n\'est pas un groupe de règles de validation.',
	'groupNotArray'         => 'La validation %s doit être un tableau.',
    'invalidTemplate'       => '{0} n\'est pas un template de validation valide.',

	// Rule Messages
	'alpha'                 => 'Le champ {field} ne peut contenir que des caractères alphabétiques.',
	'alpha_dash'            => 'Le champ {field} ne peut contenir que des caractères alphanumériques, des soulignés et des tirets.',
	'alpha_numeric'         => 'Le champ {field} ne peut contenir que des caractères alphanumériques.',
	'alpha_numeric_space'   => 'Le champ {field} ne peut contenir que des caractères alphanumériques et des espaces.',
	'alpha_space'  			=> 'Le champ {field} ne peut contenir que des caractères alphabétiques et des espaces.',
	'decimal'               => 'Le champ {field} doit contenir un nombre décimal.',
	'differs'               => 'Le champ {field} doit être différent du champ {param}.',
	'exact_length'          => 'Le champ {field} doit avoir exactement la longueur des caractères {param}.',
	'greater_than'          => 'Le champ {field} doit contenir un nombre supérieur à {param}.',
	'greater_than_equal_to' => 'Le champ {field} doit contenir un nombre supérieur ou égal à {param}.',
	'in_list'               => 'Le champ {field} doit être l\'un de : {param}.',
	'integer'               => 'Le champ {field} doit contenir un entier.',
	'is_natural'            => 'Le champ {field} ne doit contenir que des chiffres.',
	'is_natural_no_zero'    => 'Le champ {field} doit contenir uniquement des chiffres et doit être supérieur à zéro.',
	'is_unique'             => 'Le champ {field} doit contenir une valeur unique.',
	'less_than'             => 'Le champ {field} doit contenir un nombre inférieur à {param}.',
	'less_than_equal_to'    => 'Le champ {field} doit contenir un nombre inférieur ou égal à {param}.',
	'matches'               => 'Le champ {field} ne correspond pas au champ {param}.',
	'max_length'            => 'Le champ {field} ne peut pas dépasser la longueur des caractères {param}.',
	'min_length'            => 'Le champ {field} doit être composé d\'au moins {param} caractères de longueur.',
	'numeric'               => 'Le champ {field} ne doit contenir que des chiffres.',
	'regex_match'           => 'Le champ {field} n\'est pas dans le bon format.',
	'required'              => 'Le champ {field} est obligatoire.',
	'required_with'         => 'Le champ {field} est requis lorsque {param} est présent.',
	'required_without'      => 'Le champ {field} est requis lorsque {param} n\'est pas présent.',
	'timezone'              => 'Le champ {field} doit être un fuseau horaire valide.',
	'valid_base64'          => 'Le champ {field} doit être une chaîne base64 valide.',
	'valid_email'           => 'Le champ {field} doit contenir une adresse e-mail valide.',
	'valid_emails'          => 'Le champ {field} doit contenir toutes les adresses électroniques valides.',
	'valid_ip'              => 'Le champ {field} doit contenir une adresse IP valide.',
	'valid_url'             => 'Le champ {field} doit contenir une URL valide.',
	'valid_date'            => 'Le champ {field} doit contenir une date valide.',

	// Credit Cards
	'valid_cc_num'          => '{field} ne semble pas être un numéro de carte de crédit valide.',

	// Files
	'uploaded'              => '{field} n\'est pas un fichier téléchargé valide.',
	'max_size'              => '{field} est un fichier trop volumineux.',
	'is_image'              => '{field} n\'est pas un fichier image valide et téléchargé.',
	'mime_in'               => '{field} n\'a pas de type mime valide.',
	'ext_in'                => '{field} n\'a pas d\'extension de fichier valide.',
	'max_dims'              => '{field} n\'est pas une image, ou elle est trop large ou trop haute.',
];
