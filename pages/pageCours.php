<?php 
require_once "../config/database.php";
require_once "../php/functionsCour.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les Cours</title>
    <link rel="stylesheet" href="../css/templatemo-graph-page.css">
    <link rel="stylesheet" href="../css/cours.css">
</head>
<body>

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
                <li><a href="./home.php">Accueil</a></li>
                <li><a href="./home.php#dashboard">Dashboard</a></li>
                <li><a href="./cours.php" class="active">Cours</a></li>
                <li><a href="./equipements.php">Équipements</a></li>
                <li><a href="./home.php#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <div class="cours-wrapper">

        <h1 class="cours-title">Tous les Cours</h1>

        <button class="add-cours-btn" onclick="openModal()">➕ Ajouter un cours</button>

        <div class="cours-grid" id="coursGrid">
            <?php afficherCours(); ?>
        </div>
    </div>


    <div class="modal" id="coursModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle">Ajouter un Cours</h3>

            <form  method="POST">
                <input type="text" id="idCour" name="idCour" >
                <input type="text" id="nomCour" name="nomCour" placeholder="Nom du cours" required>
                <select id="categorieCour" name="categorieCour" required>
                    <option value="">-- Choisir une catégorie --</option>
                    <option value="Cardio">Cardio</option>
                    <option value="Musculation">Musculation</option>
                    <option value="CrossFit">CrossFit</option>
                    <option value="Yoga">Yoga</option>
                    <option value="Pilates">Pilates</option>
                    <option value="Danse">Danse</option>
                    <option value="Stretching">Stretching</option>
                </select>
                <input type="date" id="dateCour" name="dateCour" required>
                <input type="time" id="heureCour" name="heureCour" required>
                <input type="number" id="dureeCour" name="dureeCour" placeholder="Durée en minutes" required>
                <input type="number" id="maxCour" name="maxCour" placeholder="Max participants" required>

                <button class="submit-btn" name="ajoutCour" id="btnSubmit" type="submit">Ajouter</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(){
            document.getElementById("coursModal").style.display = "flex";
        }
        function closeModal(){
            document.getElementById("coursModal").style.display = "none";
        };
        function supprimerCour(id){
            if(confirm("vous vouler supprimer ce ",id)){
                location.href = "../php/supprimerCour.php?id=" + id
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
        document.getElementById("btnSubmit").addEventListener("click", ()=>{
            document.getElementById("btnSubmit").name = "ajouter";
            document.getElementById("btnSubmit").textContent = "ajoutCour"
        })

    </script>

</body>
</html>
