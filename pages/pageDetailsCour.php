<?php require_once "../config/database.php";
require_once "../php/functionsCour.php";
session_start();
if (!isset($_GET['id'])) {
    die("Cours introuvable.");
}
$idCour = $_GET['id'];
if (isset($_POST['ajouterEquipementCours'])) {
    $equipement_id = isset($_POST['equipement_id']) ? (int) $_POST['equipement_id'] : 0;
    if ($equipement_id > 0) {
        if (ajouterEquipementAuCour($idCour, $equipement_id)) {
            header("Location: pageDetailsCour.php?id=" . $idCour);
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
$usersInscriptions = getUsersByCour($idCour); 
$isUserInscrit = false;
$currentUserId=$_SESSION["user_id"];

foreach ($usersInscriptions as $u) {
    if ($u['user_id'] == $currentUserId) {
        $isUserInscrit = true;
        break;
    }
}



?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Détails du cours - <?php echo $cour['cour_nom']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="max-w-5xl mx-auto py-10">
        <div class="mb-6">
            <a href="./pageCours.php" class="text-blue-600 hover:underline text-sm">
                ← Retour à la liste des cours
            </a>
        </div>

        <!-- HEADER -->
        <div class="flex items-center justify-between bg-white shadow rounded-xl p-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Détails du cours</h1>
                <p class="text-gray-500 text-sm">Visualisez les informations du cours et ses équipements.</p>
            </div>

            <div class="flex items-center gap-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-full">
                <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                Cours #<?php echo $idCour; ?>
            </div>
        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">

            <!-- 🟦 CARD INFOS COURS -->
            <section class="bg-white shadow-lg rounded-xl p-6">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-blue-600">
                        <?php echo htmlspecialchars($cour['cour_nom']); ?>
                    </h2>

                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs">
                        Catégorie : <?php echo htmlspecialchars($cour['cour_category']); ?>
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-gray-700 text-sm">

                    <div class="flex flex-col bg-gray-50 p-3 rounded-lg">
                        <span class="text-gray-500 text-xs">Date</span>
                        <?php echo htmlspecialchars($cour['cour_date']); ?>
                    </div>

                    <div class="flex flex-col bg-gray-50 p-3 rounded-lg">
                        <span class="text-gray-500 text-xs">Heure</span>
                        <?php echo htmlspecialchars($cour['cour_heure']); ?>
                    </div>

                    <div class="flex flex-col bg-gray-50 p-3 rounded-lg">
                        <span class="text-gray-500 text-xs">Durée</span>
                        <?php echo htmlspecialchars($cour['cour_dure']); ?> h
                    </div>

                    <div class="flex flex-col bg-gray-50 p-3 rounded-lg">
                        <span class="text-gray-500 text-xs">Participants max</span>
                        <?php echo htmlspecialchars($cour['nb_participants']); ?>
                    </div>

                </div>

            </section>

            <!-- 🟨 CARD ÉQUIPEMENTS -->
            <section class="bg-white shadow-lg rounded-xl p-6">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Équipements liés</h2>

                    <div class="px-3 py-1 bg-gray-100 rounded-full text-xs text-gray-600">
                        <?php echo count($equipementsCours); ?> équipement(s)
                    </div>
                </div>

                <?php if (isset($messageErreur)): ?>
                    <div class="bg-red-100 text-red-600 px-4 py-2 rounded-lg mb-3">
                        <?= htmlspecialchars($messageErreur) ?>
                    </div>
                <?php endif; ?>

                <!-- LISTE DES ÉQUIPEMENTS -->
                <?php if (empty($equipementsCours)): ?>

                    <p class="text-gray-500 text-sm">Aucun équipement n'est encore lié.</p>

                <?php else: ?>

                    <div class="grid grid-cols-1 gap-4">

                        <?php foreach ($equipementsCours as $eq): ?>
                            <article class="border border-gray-200 rounded-xl p-4 bg-gray-50">

                                <div class="text-blue-600 font-semibold mb-2">
                                    <?= htmlspecialchars($eq['equipement_nom']) ?>
                                </div>

                                <div class="flex flex-wrap gap-2 text-xs">

                                    <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded-full">
                                        Type : <?= htmlspecialchars($eq['equipement_type']) ?>
                                    </span>

                                    <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full">
                                        Qté : <?= htmlspecialchars($eq['equipement_qt']) ?>
                                    </span>

                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full">
                                        État : <?= htmlspecialchars($eq['equipement_etat']) ?>
                                    </span>

                                </div>

                            </article>
                        <?php endforeach; ?>

                    </div>
                <?php endif; ?>


                <!-- FORMULAIRE AJOUT -->
                <div class="mt-6 pt-4 border-t">
                    <h3 class="font-semibold text-gray-700 mb-3">Ajouter un équipement</h3>

                    <?php if (empty($equipementsDispo)): ?>

                        <p class="text-sm text-gray-500">
                            Tous les équipements sont déjà liés à ce cours.
                        </p>

                    <?php else: ?>

                        <form method="POST" class="flex gap-3">

                            <select name="equipement_id" required class="flex-1 p-2 border rounded-lg bg-gray-50">
                                <option value="">-- Choisir un équipement --</option>

                                <?php foreach ($equipementsDispo as $eq): ?>
                                    <option value="<?= $eq['equipement_id'] ?>">
                                        <?= $eq['equipement_nom'] ?> (<?= $eq['equipement_type'] ?>)
                                    </option>
                                <?php endforeach; ?>

                            </select>

                            <button type="submit" name="ajouterEquipementCours"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                + Ajouter
                            </button>

                        </form>

                    <?php endif; ?>
                </div>

            </section>
            <!-- 🟩 CARD — Personnes inscrites au cours -->
            <section class="bg-white shadow-lg rounded-xl p-6">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Personnes inscrites</h2>

                    <div class="px-3 py-1 bg-gray-100 rounded-full text-xs text-gray-600">
                        <?= count($usersInscriptions); ?> personne(s)
                    </div>
                </div>

                <?php if (!$isUserInscrit): ?>
                    <div class="mb-4">
                        <a href="../php/inscription_cour.php?id=<?= $idCour; ?>"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow-md transition">
                            Sinscrire à ce cours
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (empty($usersInscriptions)): ?>
                    <p class="text-sm text-gray-500">Aucune inscription pour ce cours.</p>

                <?php else: ?>

                    <div class="space-y-4">

                        <?php foreach ($usersInscriptions as $user): ?>
                            <div class="flex items-center gap-4 p-3 border border-gray-200 bg-gray-50 rounded-xl">

                                <img src="<?= htmlspecialchars($user['user_image']); ?>"
                                    class="w-12 h-12 rounded-full object-cover border">

                                <div class="flex-1">
                                    <div class="text-gray-800 font-semibold">
                                        <?= htmlspecialchars($user['user_name']); ?>
                                    </div>

                                    <div class="text-gray-500 text-sm">
                                        <?= htmlspecialchars($user['user_email']); ?>
                                    </div>
                                </div>

                                <a href="profile.php?id=<?= $user['user_id']; ?>" class="text-blue-600 text-sm hover:underline">
                                    Voir profil
                                </a>

                            </div>
                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </section>



        </div>


    </div>

</body>

</html>