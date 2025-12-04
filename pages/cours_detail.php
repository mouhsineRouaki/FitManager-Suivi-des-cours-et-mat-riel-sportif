<?php
require_once "../php/config.php";
require_once "../php/allFunction.php";
session_start();

// Récupérer l'id du cours dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Cours introuvable.");
}

$idCour = (int)$_GET['id'];

// Traitement du formulaire d'ajout d'équipement au cours
if (isset($_POST['ajouterEquipementCours'])) {
    $equipement_id = isset($_POST['equipement_id']) ? (int)$_POST['equipement_id'] : 0;

    if ($equipement_id > 0) {
        if (ajouterEquipementAuCour($idCour, $equipement_id)) {
            // Rafraîchir la page pour voir la liste mise à jour
            header("Location: cours_detail.php?id=" . $idCour);
            exit;
        } else {
            $messageErreur = "Erreur lors de l'ajout de l'équipement au cours.";
        }
    } else {
        $messageErreur = "Veuillez choisir un équipement.";
    }
}

// Récupérer les infos du cours
$cour = getCourById($idCour);
if (!$cour) {
    die("Cours introuvable.");
}

// Récupérer équipements liés et non liés
$equipementsCours = getEquipementsByCour($idCour);
$equipementsDispo = getEquipementsNonLiesAuCour($idCour);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du cours - <?php echo htmlspecialchars($cour['cour_nom']); ?></title>
    <link rel="stylesheet" href="../css/templatemo-graph-page.css">

    <style>
        .detail-wrapper {
            padding: 120px 50px;
            max-width: 1300px;
            margin: auto;
        }

        .detail-title {
            font-size: 38px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #ffffff 0%, #00ffcc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .detail-card {
            background: rgba(10, 15, 40, 0.9);
            border-radius: 18px;
            padding: 25px 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .detail-card h2 {
            margin-bottom: 15px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .form-block {
            margin-top: 25px;
            background: rgba(255,255,255,0.04);
            border-radius: 14px;
            padding: 15px 20px;
        }

        .form-block h3 {
            margin-bottom: 15px;
        }

        .form-block select,
        .form-block button {
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.06);
            color: white;
            font-size: 15px;
        }

        .form-block button {
            border: none;
            cursor: pointer;
            margin-left: 10px;
            background: linear-gradient(135deg,#00ffcc,#00ccff);
            color: #000;
            font-weight: 600;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #00ffcc;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .message-erreur {
            color: #ff6b6b;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <!-- NAVBAR (copiée de cours.php) -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 13h2v8H3zm4-8h2v13H7zm4-2h2v15h-2zm4 4h2v11h-2zm4-2h2v13h-2z"/>
                    </svg>
                </div>
                <span class="logo-text">Salle de Sport</span>
            </a>

            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php#dashboard">Dashboard</a></li>
                <li><a href="cours.php" class="active">Cours</a></li>
                <li><a href="index.php#equipements">Équipements</a></li>
                <li><a href="index.php#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <div class="detail-wrapper">

        <a class="back-link" href="cours.php">&larr; Retour à la liste des cours</a>

        <h1 class="detail-title">Détails du cours</h1>

        <!-- Info du cours -->
        <div class="detail-card">
            <h2><?php echo htmlspecialchars($cour['cour_nom']); ?></h2>
            <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($cour['cour_category']); ?></p>
            <p><strong>Date :</strong> <?php echo htmlspecialchars($cour['cour_date']); ?></p>
            <p><strong>Heure :</strong> <?php echo htmlspecialchars($cour['cour_heure']); ?></p>
            <p><strong>Durée :</strong> <?php echo htmlspecialchars($cour['cour_dure']); ?> h</p>
            <p><strong>Participants max :</strong> <?php echo htmlspecialchars($cour['nb_participants']); ?></p>
        </div>

        <!-- Liste des équipements liés -->
        <div class="detail-card">
            <h2>Équipements liés à ce cours</h2>

            <?php if (isset($messageErreur)): ?>
                <p class="message-erreur"><?php echo htmlspecialchars($messageErreur); ?></p>
            <?php endif; ?>

            <?php if (empty($equipementsCours)): ?>
                <p>Aucun équipement n'est encore lié à ce cours.</p>
            <?php else: ?>
                <div class="detail-grid">
                    <?php foreach ($equipementsCours as $eq): ?>
                        <?php cardEquipement($eq); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire pour ajouter un équipement à ce cours -->
            <div class="form-block">
                <h3>Ajouter un équipement à ce cours</h3>

                <?php if (empty($equipementsDispo)): ?>
                    <p>Tous les équipements sont déjà liés à ce cours.</p>
                <?php else: ?>
                    <form method="POST">
                        <select name="equipement_id" required>
                            <option value="">-- Choisir un équipement --</option>
                            <?php foreach ($equipementsDispo as $eq): ?>
                                <option value="<?php echo $eq['equipement_id']; ?>">
                                    <?php echo htmlspecialchars($eq['equipement_nom']); ?> 
                                    (<?php echo htmlspecialchars($eq['equipement_type']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit" name="ajouterEquipementCours">Ajouter</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

    </div>

</body>
</html>
