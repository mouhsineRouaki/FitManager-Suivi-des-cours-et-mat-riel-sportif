<?php
require_once "../php/config.php";
require_once "../php/functionsEquipements.php";
require_once "../php/functionsCour.php";
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Équipement introuvable.");
}

$idEquipement = (int)$_GET['id'];

if (isset($_POST['ajouterCourEquipement'])) {
    $cour_id = isset($_POST['cour_id']) ? (int)$_POST['cour_id'] : 0;

    if ($cour_id > 0) {
        if (ajouterEquipementAuCour($cour_id, $idEquipement)) {
            header("Location: equipement_detail.php?id=" . $idEquipement);
            exit;
        } else {
            $messageErreur = "Erreur lors de l'association du cours à cet équipement.";
        }
    } else {
        $messageErreur = "Veuillez choisir un cours.";
    }
}

$equip = getEquipementById($idEquipement);
if (!$equip) {
    die("Équipement introuvable.");
}

$coursEquipement = getCoursByEquipement($idEquipement);
$coursDispo = getCoursNonLiesEquipement($idEquipement);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails équipement - <?= htmlspecialchars($equip['equipement_nom'], ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="../css/details_equipement.css">
</head>
<body>

<div class="page-shell">
    <div class="detail-wrapper">

        <div class="breadcrumb">
            <a href="equipements.php">&larr; Retour à la liste des équipements</a>
        </div>

        <div class="page-header">
            <div class="title-block">
                <h1>Détails de l'équipement</h1>
                <p>Visualisez les informations de l'équipement et les cours qui l'utilisent.</p>
            </div>
            <div class="tag-pill">
                <span class="tag-dot"></span>
                Équipement #<?= $idEquipement ?>
            </div>
        </div>

        <div class="layout">

            <!-- Carte infos équipement -->
            <section class="card">
                <div class="card-header">
                    <h2><?= htmlspecialchars($equip['equipement_nom'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <span class="badge">
                        Type : <?= htmlspecialchars($equip['equipement_type'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>

                <div class="equip-meta">
                    <div class="meta-item">
                        <span class="meta-label">Quantité</span>
                        <?= htmlspecialchars($equip['equipement_qt'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">État</span>
                        <?= htmlspecialchars($equip['equipement_etat'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>
            </section>

            <!-- Carte cours liés -->
            <section class="card card-soft">
                <div class="courses-header-line">
                    <h2 style="margin:0;font-size:18px;">Cours utilisant cet équipement</h2>
                    <div class="courses-count">
                        <?= count($coursEquipement) ?> cours associé(s)
                    </div>
                </div>

                <?php if (isset($messageErreur)): ?>
                    <div class="message-erreur">
                        <?= htmlspecialchars($messageErreur, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php if (empty($coursEquipement)): ?>
                    <p style="font-size:14px;color:var(--text-muted);margin-top:6px;">
                        Aucun cours n'utilise encore cet équipement.
                    </p>
                <?php else: ?>
                    <div class="courses-grid">
                        <?php foreach ($coursEquipement as $c): ?>
                            <article class="course-mini-card">
                                <div class="course-mini-title">
                                    <?= htmlspecialchars($c['cour_nom'], ENT_QUOTES, 'UTF-8') ?>
                                </div>
                                <div class="course-mini-meta">
                                    <?= htmlspecialchars($c['cour_category'], ENT_QUOTES, 'UTF-8') ?><br>
                                    <?= htmlspecialchars($c['cour_date'], ENT_QUOTES, 'UTF-8') ?> à
                                    <?= htmlspecialchars($c['cour_heure'], ENT_QUOTES, 'UTF-8') ?>
                                </div>
                                <a class="course-mini-link" href="cours_detail.php?id=<?= (int)$c['cour_id'] ?>">
                                    Voir le cours →
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="form-block">
                    <h3>Associer cet équipement à un cours</h3>

                    <?php if (empty($coursDispo)): ?>
                        <p style="font-size:13px;color:var(--text-muted);margin:0;">
                            Tous les cours existants utilisent déjà cet équipement.
                        </p>
                    <?php else: ?>
                        <form method="POST" class="form-inline">
                            <select name="cour_id" required>
                                <option value="">-- Choisir un cours --</option>
                                <?php foreach ($coursDispo as $c): ?>
                                    <option value="<?= (int)$c['cour_id'] ?>">
                                        <?= htmlspecialchars($c['cour_nom'], ENT_QUOTES, 'UTF-8') ?>
                                        (<?= htmlspecialchars($c['cour_category'], ENT_QUOTES, 'UTF-8') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" name="ajouterCourEquipement">
                                + Associer au cours
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
