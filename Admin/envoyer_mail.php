<?php
// envoyer_mail.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destinataire = !empty($_POST['email_manual']) ? $_POST['email_manual'] : $_POST['email_etudiant'];
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    //$expediteur = "admin@absencetrack.local"; // adapte à ton domaine
    $expediteur = "noreply@" . $_SERVER['HTTP_HOST'];

    if (filter_var($destinataire, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        
        // Sujet de l'e-mail
        $sujet = "Message important d'AbsenceTrack";

        // En-têtes
	$headers = "From: $expediteur\r\n";
	$headers .= "Reply-To: $expediteur\r\n";
	//$headers .= "Reply-To: stephaneatabong45@gmail.com\r\n"; 
	$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
	


        // Envoi de l'e-mail
        if (mail($destinataire, $sujet, $message, $headers)) {
            $confirmation = "✅ Email envoyé avec succès à $destinataire.";
        } else {
            $confirmation = "❌ Erreur lors de l’envoi de l’email.";
        }

    } else {
        $confirmation = "❌ Adresse e-mail invalide ou message vide.";
    }

    // Affiche le message + lien retour
    echo "<p>$confirmation</p>";
    echo '<a href="admin.php?action=send_email">⬅ Retour</a>';
} else {
    header("Location: admin.php?action=send_email");
    exit;
}
?>
