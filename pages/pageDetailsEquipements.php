<?php
require_once "../config/database.php";
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
            header("Location: ./pageDetailsEquipements.php?id=" . $idEquipement);
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
    <title>Détails équipement</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-5xl mx-auto py-10">

    <div class="mb-6">
        <a href="pageEquipements.php" class="text-blue-600 hover:underline text-sm">
            ← Retour à la liste des équipements
        </a>
    </div>

    <!-- HEADER -->
    <div class="flex items-center justify-between bg-white shadow rounded-xl p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Détails de l'équipement</h1>
            <p class="text-gray-500 text-sm">Visualisez les informations et les cours liés.</p>
        </div>

        <div class="flex items-center gap-2 bg-green-100 text-green-700 px-4 py-2 rounded-full">
            <span class="w-2 h-2 bg-green-600 rounded-full"></span>
            Équipement #<?= $idEquipement ?>
        </div>
    </div>


    <!-- GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">

        <!-- 🟦 INFO ÉQUIPEMENT -->
        <section class="bg-white shadow-lg rounded-xl p-6">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-blue-600">
                    <?= htmlspecialchars($equip['equipement_nom']); ?>
                </h2>

                <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs">
                    <?= htmlspecialchars($equip['equipement_type']); ?>
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-gray-700 text-sm">

                <div class="flex flex-col bg-gray-50 p-3 rounded-lg">
                    <span class="text-gray-500 text-xs">Quantité</span>
                    <?= htmlspecialchars($equip['equipement_qt']); ?>
                </div>

                <div class="flex flex-col bg-gray-50 p-3 rounded-lg">
                    <span class="text-gray-500 text-xs">État</span>
                    <?= htmlspecialchars($equip['equipement_etat']); ?>
                </div>

            </div>

        </section>


        <!-- 🟨 COURS ASSOCIÉS -->
        <section class="bg-white shadow-lg rounded-xl p-6">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Cours utilisant cet équipement</h2>

                <div class="px-3 py-1 bg-gray-100 rounded-full text-xs text-gray-600">
                    <?= count($coursEquipement); ?> cours
                </div>
            </div>

            <?php if (isset($messageErreur)): ?>
                <div class="bg-red-100 text-red-600 px-4 py-2 rounded-lg mb-3">
                    <?= htmlspecialchars($messageErreur) ?>
                </div>
            <?php endif; ?>

            <?php if (empty($coursEquipement)): ?>

                <p class="text-gray-500 text-sm">Aucun cours ne l’utilise encore.</p>

            <?php else: ?>

                <div class="space-y-4">

                    <?php foreach ($coursEquipement as $c): ?>
                        <article class="border border-gray-200 rounded-xl p-4 bg-gray-50">

                            <div class="text-blue-600 font-semibold mb-2 text-lg">
                                <?= htmlspecialchars($c['cour_nom']) ?>
                            </div>

                            <div class="flex flex-col gap-1 text-sm text-gray-600">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full w-fit text-xs">
                                    <?= htmlspecialchars($c['cour_category']) ?>
                                </span>

                                <div>
                                    <?= htmlspecialchars($c['cour_date']) ?> —
                                    <?= htmlspecialchars($c['cour_heure']) ?>
                                </div>
                            </div>

                            <a href="cours_detail.php?id=<?= $c['cour_id'] ?>"
                               class="mt-2 inline-block text-sm text-blue-600 hover:underline">
                                Voir le cours →
                            </a>

                        </article>
                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <!-- FORMULAIRE AJOUT -->
            <div class="mt-6 pt-4 border-t">
                <h3 class="font-semibold text-gray-700 mb-3">Associer à un cours</h3>

                <?php if (empty($coursDispo)): ?>

                    <p class="text-sm text-gray-500">
                        Tous les cours utilisent déjà cet équipement.
                    </p>

                <?php else: ?>

                    <form method="POST" class="flex gap-3">

                        <select name="cour_id" required class="flex-1 p-2 border rounded-lg bg-gray-50">
                            <option value="">-- Choisir un cours --</option>

                            <?php foreach ($coursDispo as $c): ?>
                                <option value="<?= $c['cour_id'] ?>">
                                    <?= $c['cour_nom'] ?> (<?= $c['cour_category'] ?>)
                                </option>
                            <?php endforeach; ?>

                        </select>

                        <button type="submit" name="ajouterCourEquipement"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            + Associer
                        </button>

                    </form>

                <?php endif; ?>
            </div>

        </section>

    </div>

</div>

</body>
</html>
