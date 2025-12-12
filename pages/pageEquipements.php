<?php
require_once "../php/check_connecter.php";
require_once "../config/database.php";
require_once "../php/functionsEquipements.php";
require_once "../php/functionsCour.php";

session_start();

function getUserById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE user_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}

$user = getUserById($_SESSION["user_id"]);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Gestion Équipements</title>
</head>

<body class="bg-gray-100 text-gray-900">
    <nav class="w-full bg-white shadow fixed top-0 left-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
            <a href="./home.php" class="flex items-center gap-2"><span class="text-2xl font-bold text-blue-600">Salle de Sport</span> </a>
            <ul class="hidden md:flex gap-6 text-gray-700 font-medium">
                <li><a href="./pageHome.php#home" class="hover:text-blue-600">Accueil</a></li>
                <li><a href="./pageHome.php#dashboard" class="hover:text-blue-600">Dashboard</a></li>
                <li><a href="./pageCours.php" class="hover:text-blue-600">Cours</a></li>
                <li><a href="./pageEquipements.php" class="hover:text-blue-600">Équipements</a></li>
                <li><a href="./pageHome.php#contact" class="hover:text-blue-600">Contact</a></li>
            </ul>
            <div class="hidden md:flex items-center gap-3 cursor-pointer" onclick="openSidebar()">
                <img src="<?php echo $user['user_image']; ?>" class="w-10 h-10 rounded-full border-2 border-blue-600" />
                <div>
                    <p class="font-semibold"><?php echo $user['user_name']; ?></p>
                    <p class="text-sm text-green-600 font-semibold"><?php echo $user['user_sold']; ?> DH</p>
                </div>
            </div>
            <button id="hamburger" class="md:hidden flex flex-col space-y-1">
                <span class="w-6 h-1 bg-gray-700"></span>
                <span class="w-6 h-1 bg-gray-700"></span>
                <span class="w-6 h-1 bg-gray-700"></span>
            </button>
        </div>

        <ul id="mobileMenu" class="md:hidden hidden flex-col bg-white shadow px-6 py-4 text-gray-700 font-medium">
            <li><a href="./home.php" class="py-2 block">Accueil</a></li>
            <li><a href="./cours.php" class="py-2 block">Cours</a></li>
            <li><a href="./equipements.php" class="py-2 block text-blue-600">Équipements</a></li>
        </ul>
    </nav>
    <div id="sidebar" class="fixed top-0 right-0 w-80 h-full bg-white shadow-xl transform translate-x-full transition-transform duration-300 z-[999]">
        <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-lg font-semibold">Profil Utilisateur</h2>
            <button onclick="closeSidebar()" class="text-red-600 text-xl font-bold">✕</button>
        </div>
        <div class="p-4 text-center">
            <img src="<?php echo $user['user_image']; ?>" class="w-24 h-24 rounded-full mx-auto border-4 border-blue-500" />
            <h3 class="mt-3 text-xl font-bold"><?php echo $user['user_name']; ?></h3>
            <p class="text-green-600 text-lg font-semibold">Solde : <?php echo $user['user_sold']; ?> DH</p>
            <button class="mt-4 w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                <a href="../php/deconecter.php">Déconnexion</a>
            </button>
        </div>
        <hr>
        <div class="p-4 overflow-y-auto h-[calc(100%-200px)]">
            <h3 class="text-lg font-semibold mb-3">Mes Cours</h3>

            <div class="grid grid-cols-1 gap-3">
                <?php getCoursByUtilisateur($_SESSION["user_id"]) ?>

            </div>
        </div>
    </div>
    <div class="pt-32 max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Gestion des Équipements</h1>
            <button class="bg-blue-600 text-white px-5 py-2 rounded shadow hover:bg-blue-700" onclick="openEquipModal()"> Ajouter un équipement </button>
        </div>
        <input type="text" id="searchInput" class="w-full md:w-1/2 p-2 border rounded mb-6"placeholder=" Rechercher un équipement...">
        <div class="flex gap-3 mb-6">
            <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded" data-type="bon">bon</button>
            <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded" data-type="moyenne">moyenne</button>
            <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded" data-type="faible">faible</button>
        </div>
        <div class="grid md:grid-cols-3 gap-6" id="equipGrid">
            <?php afficherEquipements(); ?>
        </div>
    </div>
    <div id="equipModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-40 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-96 shadow-xl">

            <h3 id="modalTitle" class="text-xl font-bold mb-4">Ajouter un Équipement</h3>

            <form method="POST">
                <input type="hidden" id="equip_id" name="equip_id">

                <input type="text" id="equip_nom" name="equip_nom" class="w-full border p-2 rounded mb-3"
                    placeholder="Nom" required>

                <input type="text" id="equip_type" name="equip_type" class="w-full border p-2 rounded mb-3"
                    placeholder="Type" required>

                <input type="number" id="equip_qt" name="equip_qt" class="w-full border p-2 rounded mb-3"
                    placeholder="Quantité" required>

                <select id="equip_etat" name="equip_etat" class="w-full border p-2 rounded mb-3" required>
                    <option value="">-- État --</option>
                    <option value="bon">Bon</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="faible">Faible</option>
                </select>

                <button class="w-full bg-blue-600 text-white py-2 rounded" name="ajoutEquipement" id="equipBtn">
                    Ajouter
                </button>
            </form>

        </div>
    </div>

    <script>
        function openEquipModal() {
            document.getElementById("equipModal").classList.remove("hidden");
        }
        function closeEquipModal() {
            document.getElementById("equipModal").classList.add("hidden");
        }

        window.onclick = (e) => {
            if (e.target.id === "equipModal") closeEquipModal();
        };

        function openSidebar() {
            document.getElementById("sidebar").classList.remove("translate-x-full");
        }
        function closeSidebar() {
            document.getElementById("sidebar").classList.add("translate-x-full");
        }

        document.getElementById("searchInput").addEventListener("input", function () {
            const value = this.value.trim();

            fetch(`../php/rechercheEquipementsAjax.php?query=${value}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById("equipGrid").innerHTML = html;
                });
        });

        document.querySelectorAll(".filter-btn").forEach(btn => {
            btn.addEventListener("click", () => {

                document.querySelectorAll(".filter-btn").forEach(b => {
                    b.classList.remove("bg-blue-600", "text-white");
                    b.classList.add("bg-gray-200", "text-gray-700");
                });

                btn.classList.add("bg-blue-600", "text-white");

                fetch(`../php/getEquipementsAjax.php?etat=${btn.dataset.type}`)
                    .then(res => res.text())
                    .then(html => document.getElementById("equipGrid").innerHTML = html);
            });
        });

        function supprimerEquipement(id) {
            if (confirm("Voulez-vous supprimer cet équipement ?")) {
                location.href = "../php/supprimerEquipement.php?id=" + id;
            }
        }

        function editEquipement(e) {
            openEquipModal();

            document.getElementById("modalTitle").innerText = "Modifier un Équipement";

            document.getElementById("equip_id").value = e.equipement_id;
            document.getElementById("equip_nom").value = e.equipement_nom;
            document.getElementById("equip_type").value = e.equipement_type;
            document.getElementById("equip_qt").value = e.equipement_qt;
            document.getElementById("equip_etat").value = e.equipement_etat;

            document.getElementById("equipBtn").name = "modifierEquipement";
            document.getElementById("equipBtn").textContent = "Modifier";
        }
    </script>

</body>

</html>