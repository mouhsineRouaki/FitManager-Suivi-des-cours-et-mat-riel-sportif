<?php
require_once "../php/config.php";
require_once "../php/allFunction.php";
session_start();

// Récupérer l'id du cours dans l'URL
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
    <title>Détails du cours - <?php echo htmlspecialchars($cour['cour_nom']); ?></title>

    <style>
        :root{
            --bg-page: #050816;
            --bg-card: #0b1220;
            --bg-card-soft: #111827;
            --primary: #22d3ee;
            --primary-soft: rgba(34,211,238,0.1);
            --accent: #4ade80;
            --danger: #f97373;
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
            --border-soft: rgba(148,163,184,0.35);
            --radius-lg: 18px;
            --radius-pill: 999px;
        }

        *{
            box-sizing: border-box;
        }

        body{
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top, #1d2548 0, transparent 55%),
                radial-gradient(circle at bottom, #020617 0, #020617 55%);
            color: var(--text-main);
        }

        .page-shell{
            min-height: 100vh;
            padding: 24px 16px 40px;
            display: flex;
            justify-content: center;
        }

        .detail-wrapper{
            width: 100%;
            max-width: 1100px;
        }

        .breadcrumb{
            font-size: 14px;
            margin-bottom: 10px;
        }
        .breadcrumb a{
            color: var(--primary);
            text-decoration: none;
        }
        .breadcrumb a:hover{
            text-decoration: underline;
        }

        .page-header{
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        .title-block h1{
            margin: 0;
            font-size: 26px;
            letter-spacing: 0.03em;
        }
        .title-block p{
            margin: 5px 0 0;
            font-size: 14px;
            color: var(--text-muted);
        }

        .tag-pill{
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: var(--radius-pill);
            border: 1px solid var(--border-soft);
            background: rgba(15,23,42,0.8);
            font-size: 12px;
            color: var(--text-muted);
        }
        .tag-dot{
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: var(--primary);
        }

        /* Layout principal */
        .layout{
            display: grid;
            grid-template-columns: minmax(0,1.3fr) minmax(0,1fr);
            gap: 22px;
        }
        @media (max-width: 900px){
            .layout{
                grid-template-columns: 1fr;
            }
        }

        .card{
            background: radial-gradient(circle at top left, rgba(56,189,248,0.12), transparent 55%),
                        radial-gradient(circle at bottom right, rgba(45,212,191,0.08), transparent 55%),
                        var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-soft);
            padding: 20px 22px;
            box-shadow: 0 18px 40px rgba(15,23,42,0.8);
        }
        .card-soft{
            background: var(--bg-card-soft);
        }
        .card-header{
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 14px;
        }
        .card-header h2{
            margin: 0;
            font-size: 18px;
        }
        .badge{
            font-size: 12px;
            border-radius: var(--radius-pill);
            padding: 4px 10px;
            background: var(--primary-soft);
            color: var(--primary);
            border: 1px solid rgba(34,211,238,0.3);
        }

        .course-meta{
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(120px,1fr));
            gap: 10px;
            margin-top: 8px;
        }
        .meta-item{
            padding: 8px 10px;
            border-radius: 10px;
            background: rgba(15,23,42,0.7);
            border: 1px dashed rgba(148,163,184,0.4);
            font-size: 13px;
        }
        .meta-label{
            display: block;
            color: var(--text-muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 2px;
        }

        /* Equipements */
        .equip-header-line{
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .equip-count{
            font-size: 13px;
            color: var(--text-muted);
        }

        .message-erreur{
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.4);
            color: #fecaca;
            padding: 8px 10px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .equip-grid{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px,1fr));
            gap: 14px;
            margin-top: 10px;
            margin-bottom: 14px;
        }

        .equip-card{
            background: linear-gradient(135deg, rgba(15,23,42,0.95), rgba(15,23,42,0.9));
            border-radius: 16px;
            border: 1px solid rgba(148,163,184,0.4);
            padding: 12px 13px;
            font-size: 13px;
            position: relative;
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .equip-card::before{
            content: "";
            position: absolute;
            inset: -40%;
            opacity: 0;
            background: radial-gradient(circle at top, rgba(34,211,238,0.13), transparent 55%);
            transition: opacity .25s ease;
        }
        .equip-card:hover{
            transform: translateY(-3px);
            box-shadow: 0 16px 35px rgba(15,23,42,0.9);
            border-color: rgba(56,189,248,0.65);
        }
        .equip-card:hover::before{
            opacity: 1;
        }
        .equip-name{
            font-weight: 600;
            margin-bottom: 4px;
        }
        .equip-meta-row{
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 4px;
        }
        .equip-chip{
            font-size: 11px;
            border-radius: 999px;
            padding: 2px 8px;
            background: rgba(31,41,55,0.9);
            border: 1px solid rgba(148,163,184,0.6);
            color: var(--text-muted);
        }
        .equip-chip--good{
            border-color: rgba(74,222,128,0.7);
            color: #bbf7d0;
        }

        /* Formulaire ajout équipement */
        .form-block{
            margin-top: 8px;
            padding: 12px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(15,23,42,0.9), rgba(15,23,42,0.96));
            border: 1px dashed rgba(148,163,184,0.6);
        }
        .form-block h3{
            margin: 0 0 10px;
            font-size: 14px;
        }
        .form-inline{
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        .form-inline select{
            padding: 7px 10px;
            border-radius: 10px;
            border: 1px solid rgba(148,163,184,0.7);
            background: #020617;
            color: var(--text-main);
            font-size: 13px;
        }
        .form-inline button{
            border-radius: var(--radius-pill);
            border: none;
            padding: 8px 16px;
            background: linear-gradient(135deg,#22c55e,#22d3ee);
            color: #020617;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 12px 25px rgba(34,197,94,0.4);
            transition: transform .12s ease, box-shadow .12s ease, filter .12s ease;
        }
        .form-inline button:hover{
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 16px 30px rgba(34,197,94,0.55);
        }
        .form-inline button:active{
            transform: translateY(0);
            box-shadow: 0 8px 18px rgba(34,197,94,0.35);
        }

        @media (max-width: 600px){
            .page-shell{
                padding-inline: 12px;
            }
            .card{
                padding-inline: 16px;
            }
        }
    </style>
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
