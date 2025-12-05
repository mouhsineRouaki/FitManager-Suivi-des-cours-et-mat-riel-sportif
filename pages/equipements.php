<?php 
require_once "../php/config.php";
require_once "../php/functionsEquipements.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les Équipements</title>
    <link rel="stylesheet" href="../css/templatemo-graph-page.css">
    <link rel="stylesheet" href="../css/equipements.css">
</head>
<style>
    
</style>

<body>

<nav id="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo">
            <span class="logo-text">Salle de Sport</span>
        </a>
        <ul class="nav-links">
            <<li><a href="./home.php">Accueil</a></li>
            <li><a href="./home.php#dashboard">Dashboard</a></li>
            <li><a href="./cours.php">Cours</a></li>
            <li><a href="./equipements.php" class="active">Équipements</a></li>
            <li><a href="./home.php#contact">Contact</a></li>
        </ul>
    </div>
</nav>

<div class="equip-wrapper">

    <h1 class="equip-title">Tous les Équipements</h1>

    <button class="add-equip-btn" onclick="openEquipModal()"> Ajouter un équipement</button>
    <div class="equip-grid" id="equipGrid">
        <?php afficherEquipements(); ?>
    </div>
</div>


<!-- MODAL AJOUT/MODIFICATION -->
<div class="modal" id="equipModal">
    <div class="modal-content">
        <h3 id="modalTitle">Ajouter un Équipement</h3>

        <form method="POST">
            <input type="hidden" id="equip_id" name="equip_id">

            <input type="text" id="equip_nom" name="equip_nom" placeholder="Nom" required>
            <input type="text" id="equip_type" name="equip_type" placeholder="Type" required>
            <input type="number" id="equip_qt" name="equip_qt" placeholder="Quantité" required>
            <select type="text" id="equip_etat" name="equip_etat" placeholder="État" required>
                <option value="">etat de equipement</option>
                <option value="bon">bon</option>
                <option value="moyenne">moyenne</option>
                <option value="faible">faible</option>
            </select>

            <button type="submit" id="equipBtn" name="ajoutEquipement" class="submit-btn">Ajouter</button>
        </form>

        <button onclick="closeEquipModal()" style="margin-top:10px; background:red; padding:10px; border:none; color:white; border-radius:10px;">
            Fermer
        </button>
    </div>
</div>

<script>

function openEquipModal() {
    document.getElementById("equipModal").style.display = "flex";
}

function closeEquipModal() {
    document.getElementById("equipModal").style.display = "none";
}

function supprimerEquipement(id){
    if(confirm("Voulez-vous supprimer cet équipement ?")){
        location.href = "../php/supprimerEquipement.php?id=" + id;
    }
}

function editEquipement(e){
    openEquipModal();

    document.getElementById("modalTitle").innerText = "Modifier Équipement";

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
