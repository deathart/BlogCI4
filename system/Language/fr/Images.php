<?php

/**
 * Image language strings.
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
	'sourceImageRequired'    => 'Vous devez spécifier une image source dans vos préférences.',
	'gdRequired'             => 'La bibliothèque d\'images GD est nécessaire pour utiliser cette fonctionnalité.',
	'gdRequiredForProps'     => 'Votre serveur doit prendre en charge la bibliothèque d\'images GD afin de déterminer les propriétés de l\'image.',
	'gifNotSupported'        => 'Les images GIF ne sont souvent pas prises en charge en raison des restrictions de licence. Vous devrez peut-être utiliser des images JPG ou PNG à la place.',
	'jpgNotSupported'        => 'Les images JPG ne sont pas prises en charge.',
	'pngNotSupported'        => 'Les images PNG ne sont pas prises en charge.',
	'unsupportedImagecreate' => 'Votre serveur ne prend pas en charge la fonction GD requise pour traiter ce type d\'image.',
	'jpgOrPngRequired'       => 'Le protocole de redimensionnement d\'image spécifié dans vos préférences ne fonctionne qu\'avec les types d\'images JPEG ou PNG.',
	'rotateUnsupported'      => 'La rotation des images ne semble pas être prise en charge par votre serveur.',
	'libPathInvalid'         => 'Le chemin d\'accès à votre bibliothèque d\'images n\'est pas correct. Veuillez définir le chemin d\'accès correct dans vos préférences d\'image.',
	'imageProcessFailed'     => 'Le traitement de l\'image a échoué. Veuillez vérifier que votre serveur supporte le protocole choisi et que le chemin d\'accès à votre bibliothèque d\'images est correct.',
	'rotationAngleRequired'  => 'Un angle de rotation est nécessaire pour faire tourner l\'image.',
	'invalidPath'            => 'Le chemin vers l\'image n\'est pas correct.',
	'copyFailed'             => 'La routine de copie d\'image a échoué.',
	'missingFont'            => 'Impossible de trouver une police à utiliser.',
	'saveFailed'             => 'Impossible d\'enregistrer l\'image. Veuillez vous assurer que l\'image et le répertoire de fichiers sont inscriptibles.',
	'invalidDirection'       => 'Le sens de rotation ne peut être que `vertical` ou `horizontal`.',
	'exifNotSupported'       => 'La lecture des données EXIF n\'est pas prise en charge par cette installation PHP.',
];
