<?php
require_once "../php/config.php";
require_once "../php/functionsCour.php";
session_start();

if (!isset($_GET['id'])) {
    die("Cours introuvable.");
}

$idCour = $_GET['id'];

if (isset($_POST['ajouterEquipementCours'])) {
    $equipement_id = isset($_POST['equipement_id']) ? (int)$_POST['equipement_id'] : 0;

    if ($equipement_id > 0) {
        if (ajouterEquipementAuCour($idCour, $equipement_id)) {
            header("Location: cours_detail.php?id=" . $idCour);
            exit;
        } else {
            $messageErreur = "Erreur lors de l'ajout de l'équipement au cours.";
        }
    } else {
        $messageErreur = "Veuillez choisir un équipement.";
    }
}
$cour = getCourById($idCour);
if (!$cour) {
    die("Cours introuvable.");
}
$equipementsCours = getEquipementsByCour($idCour);
$equipementsDispo = getEquipementsNonLiesAuCour($idCour);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du cours - <?php echo $cour['cour_nom']; ?></title>
    <link rel="stylesheet" href="../css/details_cours.css">
</head>
<body>

<div class="page-shell">
    <div class="detail-wrapper">

        <div class="breadcrumb">
            <a href="cours.php">&larr; Retour à la liste des cours</a>
        </div>

        <div class="page-header">
            <div class="title-block">
                <h1>Détails du cours</h1>
                <p>Visualisez les informations du cours et les équipements qui lui sont associés.</p>
            </div>
            <div class="tag-pill">
                <span class="tag-dot"></span>
                Cours #<?php echo $idCour; ?>
            </div>
        </div>

        <div class="layout">

            <!-- Carte Infos du cours -->
            <section class="card">
                <div class="card-header">
                    <h2><?php echo htmlspecialchars($cour['cour_nom']); ?></h2>
                    <span class="badge">
                        Catégorie : <?php echo htmlspecialchars($cour['cour_category']); ?>
                    </span>
                </div>

                <div class="course-meta">
                    <div class="meta-item">
                        <span class="meta-label">Date</span>
                        <?php echo htmlspecialchars($cour['cour_date']); ?>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Heure</span>
                        <?php echo htmlspecialchars($cour['cour_heure']); ?>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Durée</span>
                        <?php echo htmlspecialchars($cour['cour_dure']); ?> h
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Participants max</span>
                        <?php echo htmlspecialchars($cour['nb_participants']); ?>
                    </div>
                </div>
            </section>

            <!-- Carte Équipements -->
            <section class="card card-soft">
                <div class="equip-header-line">
                    <h2 style="margin:0;font-size:18px;">Équipements liés</h2>
                    <div class="equip-count">
                        <?php echo count($equipementsCours); ?> équipement(s) associé(s)
                    </div>
                </div>

                <?php if (isset($messageErreur)): ?>
                    <div class="message-erreur">
                        <?php echo htmlspecialchars($messageErreur); ?>
                    </div>
                <?php endif; ?>

                <?php if (empty($equipementsCours)): ?>
                    <p style="font-size:14px;color:var(--text-muted);margin-top:6px;">
                        Aucun équipement n'est encore lié à ce cours.
                    </p>
                <?php else: ?>
                    <div class="equip-grid">
                        <?php foreach ($equipementsCours as $eq): ?>
                            <article class="equip-card">
                                <div class="equip-name">
                                    <?php echo htmlspecialchars($eq['equipement_nom']); ?>
                                </div>
                                <div class="equip-meta-row">
                                    <span class="equip-chip">
                                        Type : <?php echo htmlspecialchars($eq['equipement_type']); ?>
                                    </span>
                                    <span class="equip-chip">
                                        Qté : <?php echo htmlspecialchars($eq['equipement_qt']); ?>
                                    </span>
                                    <span class="equip-chip equip-chip--good">
                                        État : <?php echo htmlspecialchars($eq['equipement_etat']); ?>
                                    </span>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Formulaire pour ajouter un équipement à ce cours -->
                <div class="form-block">
                    <h3>Ajouter un équipement à ce cours</h3>

                    <?php if (empty($equipementsDispo)): ?>
                        <p style="font-size:13px;color:var(--text-muted);margin:0;">
                            Tous les équipements existants sont déjà liés à ce cours.
                        </p>
                    <?php else: ?>
                        <form method="POST" class="form-inline">
                            <select name="equipement_id" required>
                                <option value="">-- Choisir un équipement --</option>
                                <?php foreach ($equipementsDispo as $eq): ?>
                                    <option value="<?php echo $eq['equipement_id']; ?>">
                                        <?php echo $eq['equipement_nom']; ?>
                                        (<?php echo htmlspecialchars($eq['equipement_type']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" name="ajouterEquipementCours">
                                + Ajouter à ce cours
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </section>

        </div>

    </div>
</div>

</body>
</html>
