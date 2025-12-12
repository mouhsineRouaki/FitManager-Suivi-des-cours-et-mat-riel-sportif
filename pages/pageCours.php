<?php 
require_once "../config/database.php";
require_once "../php/functionsCour.php";
require_once "../php/functionsEquipements.php";
session_start();

function getUserById($id){
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
    <title>Gestion Cours</title>
</head>

<body class="bg-gray-100 text-gray-900">

<!-- ================= NAVBAR ================= -->
<nav class="w-full bg-white shadow fixed top-0 left-0 z-50">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">

    <!-- LOGO -->
    <a href="./home.php" class="flex items-center gap-2">
      <span class="text-2xl font-bold text-blue-600">Salle de Sport</span>
    </a>

    <!-- MENU -->
    <ul class="hidden md:flex gap-6 text-gray-700 font-medium">
      <li><a href="./pageHome.php#home" class="hover:text-blue-600">Accueil</a></li>
      <li><a href="./pageHome.php#dashboard" class="hover:text-blue-600">Dashboard</a></li>
      <li><a href="./pageCours.php" class="hover:text-blue-600">Cours</a></li>
      <li><a href="./pageEquipements.php" class="hover:text-blue-600">Équipements</a></li>
      <li><a href="./pageHome.php#contact" class="hover:text-blue-600">Contact</a></li>
    </ul>

    <!-- PROFIL -->
    <div class="hidden md:flex items-center gap-3 cursor-pointer"
         onclick="openSidebar()">

      <img src="<?php echo $user['user_image']; ?>"
           class="w-10 h-10 rounded-full border-2 border-blue-600" />

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

  <!-- MENU MOBILE -->
  <ul id="mobileMenu" class="md:hidden hidden flex-col bg-white shadow px-6 py-4 text-gray-700 font-medium">
    <li><a href="./pageHome.php" class="py-2 block">Accueil</a></li>
    <li><a href="./pageCours.php" class="py-2 block text-blue-600">Cours</a></li>
    <li><a href="./pageEquipements.php" class="py-2 block">Équipements</a></li>
  </ul>
</nav>

<!-- ================= SIDEBAR ================= -->
<div id="sidebar"
     class="fixed top-0 right-0 w-80 h-full bg-white shadow-xl transform translate-x-full transition duration-300 z-[999]">

  <div class="flex justify-between items-center p-4 border-b">
    <h2 class="text-lg font-semibold">Profil Utilisateur</h2>
    <button onclick="closeSidebar()" class="text-red-600 text-xl font-bold">✕</button>
  </div>

  <div class="p-4 text-center">
    <img src="<?php echo $user['user_image']; ?>"
         class="w-24 h-24 rounded-full mx-auto border-4 border-blue-500" />

    <h3 class="mt-3 text-xl font-bold"><?php echo $user['user_name']; ?></h3>

    <p class="text-green-600 text-lg font-semibold">
        Solde : <?php echo $user['user_sold']; ?> DH
    </p>

    <a href="../php/deconecter.php"
       class="mt-4 w-full block bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
       Déconnexion
    </a>
  </div>
</div>

<!-- ================= PAGE COURS ================= -->
<div class="pt-32 max-w-7xl mx-auto px-6">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Gestion des Cours</h1>
        <button class="bg-blue-600 text-white px-5 py-2 rounded shadow hover:bg-blue-700"
                onclick="openModal()">➕ Ajouter un cours</button>
    </div>

    <!-- BARRE DE RECHERCHE -->
    <input type="text" id="searchInput"
           class="w-full md:w-1/2 p-2 border rounded mb-6"
           placeholder="🔍 Rechercher un cours...">

    <!-- FILTRES -->
    <div class="flex gap-3 mb-6">
      <button class="filter-btn bg-blue-600 text-white px-4 py-2 rounded" data-category="all">Tous</button>
      <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded" data-category="Cardio">Cardio</button>
      <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded" data-category="Musculation">Musculation</button>
      <button class="filter-btn bg-gray-200 text-gray-700 px-4 py-2 rounded" data-category="Yoga">Yoga</button>
    </div>

    <!-- GRID DES COURS -->
    <div class="grid md:grid-cols-3 gap-6" id="coursGrid">
        <?php afficherCours(); ?>
    </div>
</div>

<!-- ================= MODAL AJOUT / EDIT ================= -->
<div id="coursModal"
     class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-40 hidden flex items-center justify-center">


  <div class="bg-white p-6 rounded-lg w-96 shadow-xl">
    <h3 id="modalTitle" class="text-xl font-bold mb-4">Ajouter un Cours</h3>

    <form method="POST">
      <input type="hidden" id="idCour" name="idCour">

      <input type="text" id="nomCour" name="nomCour" class="w-full border p-2 rounded mb-3" placeholder="Nom du cours" required>

      <select id="categorieCour" name="categorieCour" class="w-full border p-2 rounded mb-3" required>
        <option value="">-- Catégorie --</option>
        <option value="Cardio">Cardio</option>
        <option value="Musculation">Musculation</option>
        <option value="Yoga">Yoga</option>
        <option value="CrossFit">CrossFit</option>
      </select>

      <input type="date" id="dateCour" name="dateCour" class="w-full border p-2 rounded mb-3" required>
      <input type="time" id="heureCour" name="heureCour" class="w-full border p-2 rounded mb-3" required>
      <input type="number" id="dureeCour" name="dureeCour" class="w-full border p-2 rounded mb-3" placeholder="Durée (min)" required>
      <input type="number" id="maxCour" name="maxCour" class="w-full border p-2 rounded mb-3" placeholder="Max participants" required>

      <button class="w-full bg-blue-600 text-white py-2 rounded" name="ajoutCour" id="btnSubmit">
        Ajouter
      </button>
    </form>

  </div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
function openModal() {
    document.getElementById("coursModal").classList.remove("hidden");
}

function closeModal() {
    document.getElementById("coursModal").classList.add("hidden");
}

window.onclick = (e) => {
    if (e.target.id === "coursModal") closeModal();
};

// SIDEBAR
function openSidebar() {
    document.getElementById("sidebar").classList.remove("translate-x-full");
}
function closeSidebar() {
    document.getElementById("sidebar").classList.add("translate-x-full");
}

// SEARCH
document.getElementById("searchInput").addEventListener("input", function(){
    const value = this.value.trim();

    fetch(`../php/rechercheCourAjax.php?query=${value}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById("coursGrid").innerHTML = html;
        });
});

// FILTRE
document.querySelectorAll(".filter-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelectorAll(".filter-btn").forEach(b => {
            b.classList.remove("bg-blue-600", "text-white");
            b.classList.add("bg-gray-200", "text-gray-700");
        });

        btn.classList.add("bg-blue-600", "text-white");

        fetch(`../php/getCoursAjax.php?category=${btn.dataset.category}`)
            .then(res => res.text())
            .then(html => document.getElementById("coursGrid").innerHTML = html);
    });
});
function supprimerCour(id){
    if(confirm("bous vouler supprimer sa ")){
        location.href = "../php/supprimerCour.php?id="+id
    }
}
function editCours(c){
            openModal();
            document.getElementById("modalTitle").innerText = "Modifier un Cours";

            document.getElementById("idCour").value = c.cour_id;
            document.getElementById("nomCour").value = c.cour_nom;
            document.getElementById("categorieCour").value = c.cour_category;
            document.getElementById("dateCour").value = c.cour_date;
            document.getElementById("heureCour").value = c.cour_heure;
            document.getElementById("dureeCour").value = c.cour_dure;
            document.getElementById("maxCour").value = c.nb_participants;

            document.getElementById("btnSubmit").name = "modifierCour";
            document.getElementById("btnSubmit").textContent = "Modifer"
        }
</script>

</body>
</html>
