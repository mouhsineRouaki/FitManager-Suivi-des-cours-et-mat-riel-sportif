<?php 
require_once "../config/database.php";
require_once "../php/functionsCour.php";
require_once "../php/functionsEquipements.php";

// Exemple utilisateur (remplacer par la session réelle)
$user = [
    "name" => "Mohamed",
    "sold" => "250.00",
    "image" => "../assets/user.png"
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Gestion Salle de Sport - Dashboard</title>
</head>

<body class="bg-gray-100 text-gray-900">

<!-- ================= NAVBAR ================= -->
<nav class="w-full bg-white shadow fixed top-0 left-0 z-50">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">

    <!-- LOGO -->
    <a href="#home" class="flex items-center gap-2">
      <span class="text-2xl font-bold text-blue-600">Salle de Sport</span>
    </a>

    <!-- MENU DESKTOP -->
    <ul class="hidden md:flex gap-6 text-gray-700 font-medium">
      <li><a href="#home" class="hover:text-blue-600">Accueil</a></li>
      <li><a href="#dashboard" class="hover:text-blue-600">Dashboard</a></li>
      <li><a href="./cours.php" class="hover:text-blue-600">Cours</a></li>
      <li><a href="./equipements.php" class="hover:text-blue-600">Équipements</a></li>
      <li><a href="#contact" class="hover:text-blue-600">Contact</a></li>
    </ul>

    <!-- PROFIL + NOM + SOLDE -->
    <div class="hidden md:flex items-center gap-3 cursor-pointer"
         onclick="openSidebar()">

      <img src="<?php echo $user['image']; ?>" 
           class="w-10 h-10 rounded-full border-2 border-blue-600" />

      <div>
        <p class="font-semibold"><?php echo $user['name']; ?></p>
        <p class="text-sm text-green-600 font-semibold">
            <?php echo $user['sold']; ?> DH
        </p>
      </div>
    </div>

    <!-- HAMBURGER -->
    <button id="hamburger" class="md:hidden flex flex-col space-y-1">
      <span class="w-6 h-1 bg-gray-700"></span>
      <span class="w-6 h-1 bg-gray-700"></span>
      <span class="w-6 h-1 bg-gray-700"></span>
    </button>

  </div>

  <!-- MENU MOBILE -->
  <ul id="mobileMenu" class="md:hidden hidden flex-col bg-white shadow px-6 py-4 text-gray-700 font-medium">
    <li><a href="#home" class="py-2 block">Accueil</a></li>
    <li><a href="#dashboard" class="py-2 block">Dashboard</a></li>
    <li><a href="./cours.php" class="py-2 block">Cours</a></li>
    <li><a href="./equipements.php" class="py-2 block">Équipements</a></li>
    <li><a href="#contact" class="py-2 block">Contact</a></li>
  </ul>
</nav>

<!-- ================= SIDEBAR PROFIL ================= -->
<div id="sidebar"
     class="fixed top-0 right-0 w-80 h-full bg-white shadow-xl transform translate-x-full transition-transform duration-300 z-[999]">

  <!-- HEADER -->
  <div class="flex justify-between items-center p-4 border-b">
    <h2 class="text-lg font-semibold">Profil Utilisateur</h2>
    <button onclick="closeSidebar()" class="text-red-600 text-xl font-bold">✕</button>
  </div>

  <!-- INFO UTILISATEUR -->
  <div class="p-4 text-center">
    <img src="<?php echo $user['image']; ?>"
         class="w-24 h-24 rounded-full mx-auto border-4 border-blue-500" />

    <h3 class="mt-3 text-xl font-bold"><?php echo $user['name']; ?></h3>

    <p class="text-green-600 text-lg font-semibold">
        Solde : <?php echo $user['sold']; ?> DH
    </p>

    <!-- BOUTON DECONNEXION -->
    <button class="mt-4 w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
        Déconnexion
    </button>
  </div>

  <hr>

  <!-- CARDS DES COURS (2 COLONNES) -->
  <div class="p-4 overflow-y-auto h-[calc(100%-200px)]">
    <h3 class="text-lg font-semibold mb-3">Mes Cours</h3>

    <div class="grid grid-cols-2 gap-3">

      <?php 
      // Exemple : afficher les cours pour cet utilisateur
      $cours = getCoursByEquipement(1); 
      foreach ($cours as $c) {
        echo '
        <div class="bg-gray-100 p-3 rounded shadow-sm hover:shadow-md transition">
            <h4 class="text-sm font-bold">'.$c['cour_nom'].'</h4>
            <p class="text-xs text-gray-600">'.$c['cour_category'].'</p>
            <p class="text-xs">'.$c['cour_date'].'</p>
        </div>';
      }
      ?>

    </div>
  </div>
</div>

<!-- ================= HERO SECTION ================= -->
<section id="home" class="pt-32 pb-20 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-10 px-6">
    <div class="md:w-1/2">
      <h1 class="text-5xl font-bold leading-tight mb-4">Gestion<br />Salle de Sport</h1>
      <p class="mb-6 text-lg">Gérez vos cours, coachs et équipements dans un dashboard moderne.</p>
      <a href="#dashboard" class="bg-white text-blue-700 px-6 py-3 rounded shadow font-semibold hover:bg-gray-200">Voir le Dashboard</a>
    </div>
    <div class="md:w-1/2 flex justify-center">
      <div class="w-64 h-64 bg-white bg-opacity-20 rounded-lg backdrop-blur-lg shadow-lg flex items-center justify-center text-4xl">🏋️‍♂️</div>
    </div>
  </div>
</section>

<!-- ================= DASHBOARD SECTION ================= -->
<section id="dashboard" class="py-20">
  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-3xl font-bold mb-10">Dashboard Général</h2>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

      <!-- CARD 1 -->
      <div onclick="passerAuPageCours()" class="bg-white shadow p-6 rounded-lg cursor-pointer hover:shadow-lg transition">
        <div class="flex items-center justify-between mb-4">
          <span class="text-3xl">📚</span>
          <h3 class="font-semibold">Total des Cours</h3>
        </div>
        <p class="text-4xl font-bold mb-2" id="totalCours"><?php totalCours();?></p>
        <p class="text-sm text-gray-600">Nombre total de cours programmés.</p>
      </div>

      <!-- CARD 2 -->
      <div onclick="passerAuPageEquipements()" class="bg-white shadow p-6 rounded-lg cursor-pointer hover:shadow-lg transition">
        <div class="flex items-center justify-between mb-4">
          <span class="text-3xl">🏋️</span>
          <h3 class="font-semibold">Équipements Disponibles</h3>
        </div>
        <p class="text-4xl font-bold mb-2" id="totalEquipements"><?php totalEquipement();?></p>
        <p class="text-sm text-gray-600">Quantité totale disponible.</p>
      </div>

      <!-- CARD 3 -->
      <div class="bg-white shadow p-6 rounded-lg">
        <div class="flex items-center justify-between mb-4">
          <span class="text-3xl">📊</span>
          <h3 class="font-semibold">Répartition des Cours</h3>
        </div>
        <div class="text-sm text-gray-600 space-y-1">
          <?php $statsCours = getRepartitionCoursParCategorie();
            foreach ($statsCours as $row) {
              echo '<p>'.$row['cour_category'].' : '.$row['total'].' cours</p>';
            }
          ?>
        </div>
      </div>

      <!-- CARD 4 -->
      <div class="bg-white shadow p-6 rounded-lg">
        <div class="flex items-center justify-between mb-4">
          <span class="text-3xl">⚙️</span>
          <h3 class="font-semibold">État des Équipements</h3>
        </div>
        <div class="text-sm text-gray-600 space-y-1">
          <?php $statsEquip = getRepartitionEquipementsParEtat();
            foreach ($statsEquip as $row) {
              echo '<p>'.$row['equipement_etat'].' : '.$row['total'].' équipements</p>';
            }
          ?>
        </div>
      </div>

    </div>
  </div>
</section>

<section id="cours" class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-3xl font-bold mb-6">Cours</h2>


    <div class="flex gap-3 mb-6">
      <button class="filter-btn-cours bg-blue-600 text-white px-4 py-2 rounded" data-category="cardio">cardio</button>
      <button class="filter-btn-cours bg-gray-200 text-gray-700 px-4 py-2 rounded" data-category="Musculation">Musculation</button>
      <button class="filter-btn-cours bg-gray-200 text-gray-700 px-4 py-2 rounded" data-category="Récupération">Récupération</button>
    </div>

    <div id="coursContainerCour" class="grid md:grid-cols-3 gap-6">
        <?php getCoursParCategory("cardio"); ?>
      
    </div>
  </div>
</section>

<section id="equipements" class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-3xl font-bold mb-6">equipements</h2>


    <div class="flex gap-3 mb-6">
      <button class="filter-btn-equipement bg-blue-600 text-white px-4 py-2 rounded" data-etat="bon">bon</button>
      <button class="filter-btn-equipement bg-gray-200 text-gray-700 px-4 py-2 rounded" data-etat="moyenne">moyenne</button>
      <button class="filter-btn-equipement bg-gray-200 text-gray-700 px-4 py-2 rounded" data-etat="faible">faible</button>
    </div>

    <div id="coursContainerEquipement" class="grid md:grid-cols-3 gap-6">
        <?php getEquipementParEtat("bon"); ?>
    </div>
  </div>
</section>


<!-- ================= CONTACT SECTION ================= -->
<section id="contact" class="py-20 bg-gray-50">
  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-3xl font-bold mb-10">Contact</h2>
    <div class="grid md:grid-cols-2 gap-10">

      <!-- FORM -->
      <form class="bg-white shadow-lg p-6 rounded-lg space-y-4">
        <input class="w-full border rounded p-2" placeholder="Nom complet" />
        <input class="w-full border rounded p-2" placeholder="Email" />
        <input class="w-full border rounded p-2" placeholder="Sujet" />
        <textarea class="w-full border rounded p-2 h-32" placeholder="Message..."></textarea>
        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Envoyer</button>
      </form>

      <!-- INFO -->
      <div class="space-y-6">
        <div><h3 class="text-xl font-semibold mb-1">Email</h3><p>contact@salledesport.com</p></div>
        <div><h3 class="text-xl font-semibold mb-1">Téléphone</h3><p>+212 6 00 00 00 00</p></div>
        <div><h3 class="text-xl font-semibold mb-1">Adresse</h3><p>Casablanca, Maroc</p></div>
        <div><h3 class="text-xl font-semibold mb-1">Horaires</h3><p>6h00 - 23h00</p></div>
      </div>
    </div>
  </div>
</section>

<!-- ================= FOOTER ================= -->
<footer class="bg-gray-900 text-white text-center py-6 mt-10">
  © 2026 Salle de Sport
</footer>


<script>
  const btn = document.getElementById("hamburger");
  const menu = document.getElementById("mobileMenu");
  btn.onclick = () => menu.classList.toggle("hidden");

  function openSidebar() {
      document.getElementById("sidebar").classList.remove("translate-x-full");
  }

  function closeSidebar() {
      document.getElementById("sidebar").classList.add("translate-x-full");
  }
  const filterBtnsCours = document.querySelectorAll(".filter-btn-cours");
  const filterBtnsEquipements = document.querySelectorAll(".filter-btn-equipement");
const coursCards = document.querySelectorAll(".cours-card");
const containerCour = document.getElementById("coursContainerCour");
const containerEquipement = document.getElementById("coursContainerEquipement");

filterBtnsCours.forEach(btn => {
    btn.addEventListener("click", () => {
        const category = btn.dataset.category;

        filterBtnsCours.forEach(b => {
            b.classList.remove("bg-blue-600","text-white");
            b.classList.add("bg-gray-200","text-gray-700");
        });
        btn.classList.add("bg-blue-600","text-white");
        btn.classList.remove("bg-gray-200","text-gray-700");

        fetch(`../php/getCoursAjax.php?category=${category}`)
            .then(res => res.text())
            .then(html => {
                containerCour.innerHTML = html;
            })
            .catch(err => console.error(err));
    });
});

filterBtnsEquipements.forEach(btn => {
    btn.addEventListener("click", () => {
        const etat = btn.dataset.etat;
        filterBtnsEquipements.forEach(b => {
            b.classList.remove("bg-blue-600","text-white");
            b.classList.add("bg-gray-200","text-gray-700");
        });
        btn.classList.add("bg-blue-600","text-white");
        btn.classList.remove("bg-gray-200","text-gray-700");

        fetch(`../php/getEquipementsAjax.php?etat=${etat}`)
            .then(res => res.text())
            .then(html => {
                containerEquipement.innerHTML = html;
            })
            .catch(err => console.error(err));
    });
});

</script>

</body>
</html>
