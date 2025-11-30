<?php
// Script de traitement du formulaire de contact COPYBA

// Fonction de nettoyage basique
function sanitize($v) {
    return trim(filter_var($v, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
}
// Vérification des injections dans les en-têtes
function is_header_injection($str) {
    return preg_match("/[\r\n]/", $str);
}

// Si on essaie d'accéder au fichier directement sans soumettre le formulaire
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html'); 
    exit;
}

// Protection Honeypot (champ caché 'website' qui doit rester vide)
if (!empty($_POST['website'])) {
    // Si rempli, c'est un robot -> on simule un succès
    header('Location: merci.html'); 
    exit;
}

// Récupération et nettoyage des données
$nom = isset($_POST['nom']) ? sanitize($_POST['nom']) : '';
$entreprise = isset($_POST['entreprise']) ? sanitize($_POST['entreprise']) : '';
$telephone = isset($_POST['telephone']) ? sanitize($_POST['telephone']) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : false;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validation des champs obligatoires
if (!$nom || !$email || !$message) {
    // Erreur 1 : Champs manquants
    header('Location: contact.html?e=1'); 
    exit;
}

// Sécurité anti-injection
if (is_header_injection($nom) || is_header_injection($entreprise) || is_header_injection($telephone)) {
    // Erreur 2 : Tentative d'injection
    header('Location: contact.html?e=2'); 
    exit;
}

// Configuration de l'email
$to = 'contact@copyba.fr'; // REMPLACEZ PAR VOTRE ADRESSE EMAIL RÉELLE POUR TESTER
$subject = 'Nouveau message site COPYBA : ' . $nom;

$body = "Nouveau message reçu depuis le site web COPYBA.\n\n";
$body .= "Nom : $nom\n";
$body .= "Entreprise : $entreprise\n";
$body .= "Téléphone : $telephone\n";
$body .= "Email : $email\n\n";
$body .= "Message :\n$message\n";

$headers = "From: Site COPYBA <no-reply@copyba.fr>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Envoi
if (@mail($to, $subject, $body, $headers)) {
    // Succès
    header('Location: merci.html'); 
    exit;
} else {
    // Erreur 3 : Échec technique de l'envoi
    header('Location: contact.html?e=3'); 
    exit;
}
?>