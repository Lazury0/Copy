<?php
// Simple secure contact form handler for COPYBA
// Requirements: PHP mail configured on hosting (OVH compatible).

function sanitize($v) {
  return trim(filter_var($v, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
}
function is_header_injection($str) {
  return preg_match("/[\r\n]/", $str);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /contact.html'); exit;
}

// Honeypot
if (!empty($_POST['website'])) { // field not present in form visibly
  header('Location: /merci.html'); exit;
}

$nom = isset($_POST['nom']) ? sanitize($_POST['nom']) : '';
$entreprise = isset($_POST['entreprise']) ? sanitize($_POST['entreprise']) : '';
$telephone = isset($_POST['telephone']) ? sanitize($_POST['telephone']) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : false;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (!$nom || !$email || !$message) {
  header('Location: /contact.html?e=1'); exit;
}
if (is_header_injection($nom) || is_header_injection($entreprise) || is_header_injection($telephone)) {
  header('Location: /contact.html?e=2'); exit;
}

$to = 'contact@copyba.fr';
$subject = 'Nouveau message site COPYBA';
$body = "Nom: $nom\nEntreprise: $entreprise\nTéléphone: $telephone\nEmail: $email\n\nMessage:\n$message\n";
$headers = "From: COPYBA <no-reply@copyba.fr>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

if (@mail($to, $subject, $body, $headers)) {
  header('Location: /merci.html'); exit;
} else {
  header('Location: /contact.html?e=3'); exit;
}
