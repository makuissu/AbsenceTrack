<?php
// Inclusion manuelle des fichiers PHPMailer
require 'mail/PHPMailer/src/PHPMailer.php';
require 'mail/PHPMailer/src/SMTP.php';
require 'mail/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailParent = $_POST['email_parent'];
    $message = $_POST['message'];

    if (!empty($emailParent) && !empty($message)) {
        $mail = new PHPMailer(true);

        try {
            // Paramètres du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ton_email@gmail.com'; // Remplace avec ton email
            $mail->Password = 'ton_mot_de_passe';     // Utilise un mot de passe d'application Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Expéditeur
            $mail->setFrom('ton_email@gmail.com', 'AbsenceTrack Admin');
            $mail->addAddress($emailParent);

            $mail->isHTML(true);
            $mail->Subject = 'Notification d\'absence';
            $mail->Body    = nl2br($message);

            $mail->send();
            echo "Email envoyé avec succès.";
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
        }
    } else {
        echo "Tous les champs sont requis.";
    }
}
?>
