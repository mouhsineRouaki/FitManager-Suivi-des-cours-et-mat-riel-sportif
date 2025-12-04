<?php 
require_once "../php/allFunction.php";
require_once "../php/config.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les Cours</title>
    <link rel="stylesheet" href="../css/templatemo-graph-page.css">

    <style>
        .cours-wrapper {
            padding: 120px 50px;
            max-width: 1300px;
            margin: auto;
        }

        .cours-title {
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

        .search-input, .category-select {
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

        .search-input:focus,
        .category-select:focus {
            outline: none;
            border-color: #00ffcc;
            box-shadow: 0 0 8px rgba(0,255,204,0.4);
        }

        .cours-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 35px;
        }

        .add-cours-btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #ff6b6b, #ff8e53);
            color: white;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(255,107,107,0.3);
            transition: 0.3s ease;
        }

        .add-cours-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 20px rgba(255,107,107,0.5);
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(3px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            width: 420px;
            background: #1a1f3a;
            padding: 25px 30px;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.15);
        }

        .modal-content h3 {
            margin-bottom: 20px;
            background: linear-gradient(135deg,#fff,#00ffcc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .close-modal {
            float: right;
            font-size: 26px;
            cursor: pointer;
            margin-top: -10px;
            color: #ff7171;
        }

        .modal input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.06);
            color: white;
        }

        .modal input:focus {
            outline: none;
            border-color: #00ffcc;
        }
        #idCour{
            display: none;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            background: linear-gradient(135deg,#00ffcc,#00ccff);
            border: none;
            color: black;
            font-weight: bold;
            cursor: pointer;
        }

    </style>
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
                <li><a href="index.php">Accueil</a></li>
                <li><a href="index.php#dashboard">Dashboard</a></li>
                <li><a href="cours.php" class="active">Cours</a></li>
                <li><a href="index.php#equipements">Équipements</a></li>
                <li><a href="index.php#contact">Contact</a></li>
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
                <input type="text" id="categorieCour" name="categorieCour" placeholder="Catégorie" required>
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

    </script>

</body>
</html>
