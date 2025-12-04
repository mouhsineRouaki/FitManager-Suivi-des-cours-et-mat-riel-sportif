<?php 
require_once "../php/allFunction.php";
require_once "../php/config.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les Équipements</title>
    <link rel="stylesheet" href="../css/templatemo-graph-page.css">

    <style>
        .equip-wrapper {
            padding: 120px 50px;
            max-width: 1300px;
            margin: auto;
        }

        .equip-title {
            font-size: 42px;
            text-align: center;
            margin-bottom: 40px;
            background: linear-gradient(135deg, #ffffff 0%, #00ffcc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .search-filter-bar {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 35px;
        }

        .search-input {
            flex: 1;
            padding: 12px 15px;
            border-radius: 12px;
            font-size: 16px;
            border: 1px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.08);
            color: white;
            backdrop-filter: blur(6px);
            transition: 0.3s ease;
        }

        .equip-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 35px;
        }

        .add-equip-btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #ff6b6b, #ff8e53);
            color: white;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        /* CARDS */
        .equip-card {
            background: rgba(255,255,255,0.08);
            padding: 20px;
            border-radius: 15px;
            color: white;
            border: 1px solid rgba(255,255,255,0.15);
        }
        .equip-card h2 { 
            color: #00ffcc;
        }

        .equip-actions button {
            padding: 8px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            margin-right: 10px;
        }
        .btn-edit { background: orange; }
        .btn-delete { background: red; }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            width: 420px;
            background: #1a1f3a;
            padding: 25px;
            border-radius: 15px;
        }
        .modal input {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
        }
        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg,#00ffcc,#00ccff);
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<nav id="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo">
            <span class="logo-text">Salle de Sport</span>
        </a>
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="cours.php">Cours</a></li>
            <li><a href="equipements.php" class="active">Équipements</a></li>
        </ul>
    </div>
</nav>

<div class="equip-wrapper">

    <h1 class="equip-title">Tous les Équipements</h1>

    <button class="add-equip-btn" onclick="openEquipModal()">➕ Ajouter un équipement</button>
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
            <input type="text" id="equip_etat" name="equip_etat" placeholder="État" required>

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
