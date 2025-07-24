<?php
$to = "tonadresse@gmail.com";
$subject = "Test email local";
$message = "Ceci est un test d'envoi d'email via SMTP local";
$headers = "From: admin@absencetrack.local";

if (mail($to, $subject, $message, $headers)) {
    echo "✅ Email envoyé.";
} else {
    echo "❌ Échec de l'envoi.";
}
?>
