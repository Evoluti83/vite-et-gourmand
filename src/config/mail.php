<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

function sendMail(string $to, string $toName, string $subject, string $body): bool {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bauduin.teddy62@gmail.com';
        $mail->Password   = 'tmqhpythpbpckmsn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('contact@viteetgourmand.fr', 'Vite & Gourmand');
        $mail->addAddress($to, $toName);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur mail : " . $mail->ErrorInfo);
        return false;
    }
}

function mailBienvenue(string $to, string $prenom): void {
    $subject = 'Bienvenue chez Vite & Gourmand !';
    $body = "
    <div style='font-family:sans-serif;max-width:600px;margin:0 auto'>
        <div style='background:#6B2737;padding:24px;text-align:center'>
            <h1 style='color:#C9A84C;margin:0'>Vite &amp; Gourmand</h1>
        </div>
        <div style='padding:32px;background:#FAF7F2'>
            <h2 style='color:#6B2737'>Bienvenue {$prenom} !</h2>
            <p>Votre compte a bien été créé. Vous pouvez dès maintenant découvrir nos menus et passer commande.</p>
            <a href='http://localhost/vite-et-gourmand/public/?page=menus' style='background:#6B2737;color:#FAF7F2;padding:12px 24px;text-decoration:none;border-radius:4px;display:inline-block;margin-top:16px'>Découvrir nos menus</a>
        </div>
        <div style='background:#2C2C2C;padding:16px;text-align:center'>
            <p style='color:#aaa;font-size:12px;margin:0'>Vite &amp; Gourmand — 12 rue des Saveurs, 33000 Bordeaux</p>
        </div>
    </div>";
    sendMail($to, $prenom, $subject, $body);
}

function mailConfirmationCommande(string $to, string $prenom, string $numero_cmd, string $menu_titre, string $date_prestation, float $prix_total): void {
    $subject = "Confirmation de votre commande {$numero_cmd}";
    $body = "
    <div style='font-family:sans-serif;max-width:600px;margin:0 auto'>
        <div style='background:#6B2737;padding:24px;text-align:center'>
            <h1 style='color:#C9A84C;margin:0'>Vite &amp; Gourmand</h1>
        </div>
        <div style='padding:32px;background:#FAF7F2'>
            <h2 style='color:#6B2737'>Commande confirmée !</h2>
            <p>Bonjour {$prenom},</p>
            <p>Votre commande <strong>{$numero_cmd}</strong> a bien été enregistrée.</p>
            <table style='width:100%;border-collapse:collapse;margin-top:16px'>
                <tr><td style='padding:8px;border-bottom:1px solid #e0d8d0;color:#888'>Menu</td><td style='padding:8px;border-bottom:1px solid #e0d8d0'>{$menu_titre}</td></tr>
                <tr><td style='padding:8px;border-bottom:1px solid #e0d8d0;color:#888'>Date prestation</td><td style='padding:8px;border-bottom:1px solid #e0d8d0'>{$date_prestation}</td></tr>
                <tr><td style='padding:8px;color:#888'>Total</td><td style='padding:8px;color:#6B2737;font-weight:bold'>" . number_format($prix_total, 2, ',', ' ') . " €</td></tr>
            </table>
        </div>
        <div style='background:#2C2C2C;padding:16px;text-align:center'>
            <p style='color:#aaa;font-size:12px;margin:0'>Vite &amp; Gourmand — 12 rue des Saveurs, 33000 Bordeaux</p>
        </div>
    </div>";
    sendMail($to, $prenom, $subject, $body);
}

function mailInvitationAvis(string $to, string $prenom, string $numero_cmd): void {
    $subject = "Donnez votre avis sur votre commande {$numero_cmd}";
    $body = "
    <div style='font-family:sans-serif;max-width:600px;margin:0 auto'>
        <div style='background:#6B2737;padding:24px;text-align:center'>
            <h1 style='color:#C9A84C;margin:0'>Vite &amp; Gourmand</h1>
        </div>
        <div style='padding:32px;background:#FAF7F2'>
            <h2 style='color:#6B2737'>Votre avis nous intéresse !</h2>
            <p>Bonjour {$prenom},</p>
            <p>Votre commande <strong>{$numero_cmd}</strong> est terminée. Nous espérons que vous avez été satisfait de notre prestation.</p>
            <p>Prenez un moment pour nous laisser votre avis — cela nous aide à améliorer nos services.</p>
            <a href='http://localhost/vite-et-gourmand/public/?page=espace-user&action=commandes' style='background:#6B2737;color:#FAF7F2;padding:12px 24px;text-decoration:none;border-radius:4px;display:inline-block;margin-top:16px'>Laisser mon avis</a>
        </div>
        <div style='background:#2C2C2C;padding:16px;text-align:center'>
            <p style='color:#aaa;font-size:12px;margin:0'>Vite &amp; Gourmand — 12 rue des Saveurs, 33000 Bordeaux</p>
        </div>
    </div>";
    sendMail($to, $prenom, $subject, $body);
}

function mailRetourMateriel(string $to, string $prenom, string $numero_cmd): void {
    $subject = "Retour de matériel — Commande {$numero_cmd}";
    $body = "
    <div style='font-family:sans-serif;max-width:600px;margin:0 auto'>
        <div style='background:#6B2737;padding:24px;text-align:center'>
            <h1 style='color:#C9A84C;margin:0'>Vite &amp; Gourmand</h1>
        </div>
        <div style='padding:32px;background:#FAF7F2'>
            <h2 style='color:#6B2737'>Retour de matériel</h2>
            <p>Bonjour {$prenom},</p>
            <p>Du matériel a été prêté lors de votre commande <strong>{$numero_cmd}</strong>.</p>
            <div style='background:#FAEEDA;border:1px solid #C9A84C;border-radius:6px;padding:16px;margin-top:16px'>
                <strong style='color:#854F0B'>Important :</strong>
                <p style='color:#854F0B;margin-top:8px'>Vous disposez de <strong>10 jours ouvrés</strong> pour restituer le matériel. Passé ce délai, des frais de <strong>600,00 €</strong> vous seront facturés conformément aux CGV.</p>
            </div>
            <p style='margin-top:16px'>Pour organiser le retour du matériel, contactez-nous :</p>
            <p>📞 06 12 34 56 78<br>✉️ contact@viteetgourmand.fr</p>
        </div>
        <div style='background:#2C2C2C;padding:16px;text-align:center'>
            <p style='color:#aaa;font-size:12px;margin:0'>Vite &amp; Gourmand — 12 rue des Saveurs, 33000 Bordeaux</p>
        </div>
    </div>";
    sendMail($to, $prenom, $subject, $body);
}

function mailCreationCompteEmploye(string $to, string $prenom): void {
    $subject = 'Votre compte employé Vite & Gourmand';
    $body = "
    <div style='font-family:sans-serif;max-width:600px;margin:0 auto'>
        <div style='background:#6B2737;padding:24px;text-align:center'>
            <h1 style='color:#C9A84C;margin:0'>Vite &amp; Gourmand</h1>
        </div>
        <div style='padding:32px;background:#FAF7F2'>
            <h2 style='color:#6B2737'>Votre compte a été créé</h2>
            <p>Bonjour {$prenom},</p>
            <p>Un compte employé a été créé pour vous sur l'application Vite &amp; Gourmand.</p>
            <p>Votre identifiant de connexion est votre adresse email : <strong>{$to}</strong></p>
            <p style='color:#888'>Pour obtenir votre mot de passe, rapprochez-vous de l'administrateur.</p>
        </div>
        <div style='background:#2C2C2C;padding:16px;text-align:center'>
            <p style='color:#aaa;font-size:12px;margin:0'>Vite &amp; Gourmand — 12 rue des Saveurs, 33000 Bordeaux</p>
        </div>
    </div>";
    sendMail($to, $prenom, $subject, $body);
}