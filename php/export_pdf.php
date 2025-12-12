<?php
session_start();
require_once "../config/database.php";
require_once "./functionsCour.php";

require_once "../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['id'])) {
    die("Cours introuvable.");
}

$cour_id = (int) $_GET['id'];
$cour = getCourById($cour_id);

if (!$cour) {
    die("Cours introuvable.");
}

$stmt = $conn->prepare("
    SELECT u.user_name, u.user_email 
    FROM cours_utilisateurs cu 
    JOIN utilisateur u ON u.user_id = cu.user_id
    WHERE cu.cour_id = ?
");
$stmt->execute([$cour_id]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);

$html = '
<h1 style="text-align:center;">Détails du cours</h1>

<h3>'.$cour["cour_nom"].'</h3>
<p><strong>Description :</strong> '.$cour["cour_description"].'</p>
<p><strong>Prix :</strong> '.$cour["cour_prix"].' MAD</p>

<h2>Liste des inscrits</h2>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
        <tr style="background:#f0f0f0;">
            <th>Nom</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>';

foreach ($users as $u) {
    $html .= '
        <tr>
            <td>'.$u["user_name"].'</td>
            <td>'.$u["user_email"].'</td>
        </tr>';
}

$html .= '
    </tbody>
</table>';

// Charger le HTML
$dompdf->loadHtml($html);

// Format A4
$dompdf->setPaper('A4', 'portrait');

// Générer PDF
$dompdf->render();

// Télécharger le PDF
$dompdf->stream("cours_$cour_id.pdf", ["Attachment" => true]);
exit;
