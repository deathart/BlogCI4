<?php

/**
 * Email language strings.
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
	'mustBeArray'          => 'La méthode de validation de l\'email doit être passée avec un tableau.',
	'invalidAddress'       => 'L\'adress email suivante est invalide : {0, string}',
	'attachmentMissing'    => 'Impossible de localiser la pièce jointe suivante : {0, string}',
	'attachmentUnreadable' => 'Impossible d\'ouvrir cette pièce jointe : {0, string}',
	'noFrom'               => 'Impossible d\'envoyer du courrier sans en-tête "From".',
	'noRecipients'         => 'Vous devez inclure les destinataires : To, Cc, or Bcc',
	'sendFailurePHPMail'   => 'Impossible d\'envoyer des courriels en PHP mail(). Il se peut que votre serveur ne soit pas configuré pour envoyer du courrier en utilisant cette méthode.',
	'sendFailureSendmail'  => 'Impossible d\'envoyer des courriels en PHP Sendmail. Il se peut que votre serveur ne soit pas configuré pour envoyer du courrier en utilisant cette méthode.',
	'sendFailureSmtp'      => 'Impossible d\'envoyer des courriels en utilisant PHP SMTP. Il se peut que votre serveur ne soit pas configuré pour envoyer du courrier en utilisant cette méthode.',
	'sent'                 => 'Votre message a été envoyé avec succès en utilisant le protocole suivant : {0, string}',
	'noSocket'             => 'Impossible d\'ouvrir une socket pour Sendmail. Veuillez vérifier les paramètres.',
	'noHostname'           => 'Vous n\'avez pas spécifié de nom d\'hôte SMTP.',
	'SMTPError'            => 'L\'erreur SMTP suivante a été rencontrée : {0, string}',
	'noSMTPAuth'           => 'Erreur : Vous devez attribuer un nom d\'utilisateur et un mot de passe SMTP.',
	'failedSMTPLogin'      => 'Échec de l\'envoi de la commande AUTH LOGIN. Erreur : {0, string}',
	'SMTPAuthUsername'     => 'N\'a pas réussi à authentifier le nom d\'utilisateur. Erreur : {0, string}',
	'SMTPAuthPassword'     => 'Échec de l\'authentification du mot de passe. Erreur : {0, string}',
	'SMTPDataFailure'      => 'Impossible d\'envoyer les données suivants : {0, string}',
	'exitStatus'           => 'Code d\'état de sortie : {0, string}',
];
