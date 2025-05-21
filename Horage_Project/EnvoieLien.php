<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-6.10.0/PHPMailer-6.10.0/src/Exception.php';
require 'PHPMailer-6.10.0/PHPMailer-6.10.0/src/PHPMailer.php';
require 'PHPMailer-6.10.0/PHPMailer-6.10.0/src/SMTP.php';

function sendPasswordResetEmail($toEmail, $token) {
    $mail = new PHPMailer(true);
    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';       
        $mail->SMTPAuth   = true;
        $mail->Username   = 'dan.bavamian958@gmail.com'; 
        $mail->Password   = 'rdxz zzyt bfjs ymjt'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Expéditeur et destinataire
        $mail->setFrom('dan.bavamian958@gmail.com', 'Horage');
        $mail->addAddress($toEmail);
        $mail->addReplyTo('dan.bavamian958@gmail.com', 'Horage');

        // Contenu
        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation de votre mot de passe';
        $mail->Body    = "Bonjour,<br><br>Voici votre lien de réinitialisation : 
            <a href='https://horage.infinityfreeapp.com/Horage_Project/ReinitialiserMDP.php?token=$token'>Réinitialiser mon mot de passe</a><br><br>
            Ce lien expire dans 1 heure.";
        $mail->AltBody = "Voici votre lien de réinitialisation : https://horage.infinityfreeapp.com/Horage_Project/ReinitialiserMDP.php?token=$token";
        $mail->Debugoutput = 'error_log';


        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur lors de l'envoi : " . $mail->ErrorInfo);
        return false;
    }
}
?> 