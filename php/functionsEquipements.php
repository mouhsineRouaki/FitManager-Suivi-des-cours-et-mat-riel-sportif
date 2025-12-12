<?php 
require_once "../config/database.php";


function totalEquipement(){
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM equipements");
    $stmt->execute();
    $result= $stmt-> fetch(PDO::FETCH_ASSOC);
    echo "$result[total]";
}
function getEquipementParEtat($Etat){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM equipements where equipement_etat=?; ");
    $stmt->execute([$Etat]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row){
       echo cardEquipement($row);
    }
}
function getEquipementParEtatHome($Etat){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM equipements where equipement_etat=?; ");
    $stmt->execute([$Etat]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row){
       echo cardEquipementHome($row);
    }
}
function rechercheEquipement($query){
    global $conn;
    $search = "%".$query."%";
    $stmt = $conn->prepare("SELECT * FROM equipements WHERE equipement_nom LIKE ? OR equipement_type LIKE ? OR equipement_etat LIKE ?");
    $stmt->execute([$search, $search, $search]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        echo "<p class='text-center text-gray-500 py-4'>Aucun équipement trouvé</p>";
        return;
    }

    foreach ($result as $row){
        echo cardEquipement($row);
    }
}


function cardEquipement($row) {
    echo '
    <div class="bg-white shadow-lg rounded-xl p-6 hover:shadow-2xl transition-all duration-300">

        <!-- TITRE -->
        <h2 class="text-2xl font-semibold text-blue-600 mb-4">
            '.$row['equipement_nom'].'
        </h2>

        <!-- INFORMATIONS -->
        <div class="space-y-2 text-gray-700 mb-5">
            <p><strong>Type :</strong> '.$row['equipement_type'].'</p>
            <p><strong>Quantité :</strong> '.$row['equipement_qt'].'</p>
            <p><strong>État :</strong> '.$row['equipement_etat'].'</p>
        </div>

        <!-- ACTIONS -->
        <div class="flex items-center justify-between pt-4 border-t">

            <!-- Modifier -->
            <button 
                class="px-4 py-2 rounded-lg bg-blue-500 text-white font-medium hover:bg-blue-600 transition"
                onclick=\'editEquipement('.json_encode($row).')\'>
                Modifier
            </button>

            <!-- Supprimer -->
            <button 
                class="px-4 py-2 rounded-lg bg-red-500 text-white font-medium hover:bg-red-600 transition"
                onclick="supprimerEquipement('.$row['equipement_id'].')">
                Supprimer
            </button>

            <!-- Voir détails -->
            <a href="./pageDetailsEquipements.php?id='.$row['equipement_id'].'"
               class="px-4 py-2 rounded-lg bg-gray-700 text-white font-medium hover:bg-gray-800 transition">
                Voir
            </a>
        </div>

    </div>';
}

function cardEquipementHome($row) {
    echo '
    <div class="bg-white shadow-lg rounded-xl p-6 hover:shadow-2xl transition-all duration-300">

        <!-- TITRE -->
        <h2 class="text-2xl font-semibold text-blue-600 mb-4">
            '.$row['equipement_nom'].'
        </h2>

        <!-- INFORMATIONS -->
        <div class="space-y-2 text-gray-700 mb-5">
            <p><strong>Type :</strong> '.$row['equipement_type'].'</p>
            <p><strong>Quantité :</strong> '.$row['equipement_qt'].'</p>
            <p><strong>État :</strong> '.$row['equipement_etat'].'</p>
        </div>

        <!-- ACTIONS -->
        <div class="flex items-center justify-between pt-4 border-t">

            <!-- Voir détails -->
            <a href="../pages/pageDetailsEquipements.php?id='.$row['equipement_id'].'"
               class="px-4 py-2 rounded-lg bg-gray-700 text-white font-medium hover:bg-gray-800 transition">
                Voir
            </a>
        </div>

    </div>';
}


function afficherEquipements() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM equipements ORDER BY equipement_id DESC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as $row) {
        cardEquipement($row);
    }
}


function ajouterEquipement($nom, $type, $qt, $etat,$url){
    global $conn;
    $stmt = $conn->prepare("
        INSERT INTO equipements (equipement_nom, equipement_type, equipement_qt, equipement_etat,equipement_image)
        VALUES (?, ?, ?, ?,?)
    ");
    return $stmt->execute([$nom, $type, $qt, $etat,$url]);
}

function modifierEquipement($id, $nom, $type, $qt, $etat){
    global $conn;
    $stmt = $conn->prepare("
        UPDATE equipements 
        SET equipement_nom = ?, equipement_type = ?, 
            equipement_qt = ?, equipement_etat = ?
        WHERE equipement_id = ?
    ");
    return $stmt->execute([$nom, $type, $qt, $etat, $id]);
}

function supprimerEquipement($id){
    global $conn;
    $stmt = $conn->prepare("DELETE FROM equipements WHERE equipement_id = ?");
    return $stmt->execute([$id]);
}

function getEquipementById($id){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM equipements WHERE equipement_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getRepartitionEquipementsParEtat() {
    global $conn;

    $sql = "SELECT equipement_etat, COUNT(*) AS total FROM equipements GROUP BY equipement_etat ORDER BY total DESC LIMIT 3;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST["ajoutEquipement"])) {
    $nom = $_POST["equip_nom"];
    $type = $_POST["equip_type"];
    $qt = $_POST["equip_qt"];
    $etat = $_POST["equip_etat"];
    $url = $_POST["equip_url"];

    if (ajouterEquipement($nom, $type, $qt, $etat,$url)) {
        header("Location: ../pages/pageEquipements.php");
        exit;
    } else {
        echo "Erreur lors de l'ajout de l'équipement.";
    }
}

if (isset($_POST["modifierEquipement"])) {
    $id = $_POST["equip_id"];
    $nom = $_POST["equip_nom"];
    $type = $_POST["equip_type"];
    $qt = $_POST["equip_qt"];
    $etat = $_POST["equip_etat"];

    if (modifierEquipement($id, $nom, $type, $qt, $etat)) {
        header("Location: ../pages/pageEquipements.php");
        exit;
    } else {
        echo "Erreur lors de la modification.";
    }
}
