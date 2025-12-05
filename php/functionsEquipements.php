<?php 
require_once "config.php";


function totalEquipement(){
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM equipements");
    $stmt->execute();
    $result= $stmt-> fetch(PDO::FETCH_ASSOC);
    echo "$result[total]";
}
function getEquipementParEtat($Etat){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM equipements where cour_category=? limit 3; ");
    $stmt->execute([$Etat]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row){
       echo cardCour($row);
    }
}

function cardEquipement($row) {
    echo '
    <div class="course-card">
        <h2 class="course-title">'.$row['equipement_nom'].'</h2>

        <div class="course-info">
            <p><strong>Type :</strong> '.$row['equipement_type'].'</p>
            <p><strong>Quantité :</strong> '.$row['equipement_qt'].'</p>
            <p><strong>État :</strong> '.$row['equipement_etat'].'</p>
        </div>

        <div class="course-actions">
            <button class="course-btn btn-edit" onclick=\'editEquipement('.json_encode($row).')\'>
                Modifier
            </button>

            <button class="course-btn btn-delete" onclick="supprimerEquipement('.$row['equipement_id'].')">
                Supprimer
            </button>

            <a class="course-btn btn-edit" href="equipement_detail.php?id='.$row['equipement_id'].'">
                Voir les détails
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

function ajouterEquipement($nom, $type, $qt, $etat){
    global $conn;
    $stmt = $conn->prepare("
        INSERT INTO equipements (equipement_nom, equipement_type, equipement_qt, equipement_etat)
        VALUES (?, ?, ?, ?)
    ");
    return $stmt->execute([$nom, $type, $qt, $etat]);
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

function rechercherEquipement($keyword){
    global $conn;
    $stmt = $conn->prepare("
        SELECT * FROM equipements 
        WHERE equipement_nom LIKE ? OR equipement_type LIKE ? OR equipement_etat LIKE ?
    ");
    $search = "%".$keyword."%";
    $stmt->execute([$search, $search, $search]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getEquipementById($id){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM equipements WHERE equipement_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getRepartitionEquipementsParEtat() {
    global $conn;

    $sql = "SELECT equipement_etat, COUNT(*) AS total
            FROM equipements
            GROUP BY equipement_etat";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST["ajoutEquipement"])) {
    $nom = $_POST["equip_nom"];
    $type = $_POST["equip_type"];
    $qt = $_POST["equip_qt"];
    $etat = $_POST["equip_etat"];

    if (ajouterEquipement($nom, $type, $qt, $etat)) {
        header("Location: ../pages/equipements.php");
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
        header("Location: ../pages/equipements.php");
        exit;
    } else {
        echo "Erreur lors de la modification.";
    }
}

if (isset($_POST["inscriotCour"])) {
    if (modifierEquipement($id, $nom, $type, $qt, $etat)) {
        header("Location: ../pages/equipements.php");
        exit;
    } else {
        echo "Erreur lors de la modification.";
    }
}
