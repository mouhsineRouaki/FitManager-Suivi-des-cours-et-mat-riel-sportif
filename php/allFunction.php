<?php 
require_once "config.php";

function totalCours(){
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM cours");
    $stmt->execute();
    $result= $stmt-> fetch(PDO::FETCH_ASSOC);
    echo "$result[total]";
}
function totalEquipement(){
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM equipements");
    $stmt->execute();
    $result= $stmt-> fetch(PDO::FETCH_ASSOC);
    echo "$result[total]";
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


function cardCour($row){
    echo '
    <div class="course-card">
        <h2 class="course-title">'.$row['cour_nom'].'</h2>

        <div class="course-info">
            <p><strong>Catégorie :</strong> '.$row['cour_category'].'</p>
            <p><strong>Date :</strong> '.$row['cour_date'].'</p>
            <p><strong>Heure :</strong> '.$row['cour_heure'].'</p>
            <p><strong>Durée :</strong> '.$row['cour_dure'].'h</p>
            <p><strong>Participants :</strong> '.$row['nb_participants'].'</p>
        </div>

        <div class="course-actions">
            <button class="course-btn btn-edit" onclick=\'editCours('.json_encode($row).')\'>Modifier</button>
            <button class="course-btn btn-delete" name="suppresion" onclick="supprimerCour('.$row['cour_id'].')">Supprimer</button>

            <!-- NOUVEAU : bouton détail -->
            <a class="course-btn btn-edit" href="cours_detail.php?id='.$row['cour_id'].'">
                Voir les détails
            </a>
        </div>
    </div>';
}



function afficherCours(){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM cours");
    $stmt->execute();
    $result= $stmt-> fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        cardCour($row);
    }
}
function getCourById($id){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM cours WHERE cour_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function ajouterEquipementAuCour($idCour, $idEquipement){
    global $conn;
    $stmt = $conn->prepare("
        INSERT INTO cours_equipements (cour_id, equipement_id)
        VALUES (?, ?)
    ");
    return $stmt->execute([$idCour, $idEquipement]);
}

function getEquipementsNonLiesAuCour($idCour){
    global $conn;
    $stmt = $conn->prepare("
        SELECT *
        FROM equipements
        WHERE equipement_id NOT IN (
            SELECT equipement_id FROM cours_equipements WHERE cour_id = ?
        )
        ORDER BY equipement_nom
    ");
    $stmt->execute([$idCour]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getEquipementsByCour($idCour){
    global $conn;
    $stmt = $conn->prepare("
        SELECT e.*
        FROM equipements e
        INNER JOIN cours_equipements ce ON ce.equipement_id = e.equipement_id
        WHERE ce.cour_id = ?
        ORDER BY e.equipement_nom
    ");
    $stmt->execute([$idCour]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function afficherEquipements() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM equipements ORDER BY equipement_id DESC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as $row) {
        cardEquipement($row);
    }
}
function afficherCoursParAray(array $tab){
    foreach($tab as $t){
        echo cardCour($t);
    }
}

function showCategory(){
    global $conn;
    $stmt = $conn-> prepare("SELECT DISTINCT cour_category from cours");
    $stmt-> execute();
    $result = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row){
        echo "<option value=$row[cour_category]>$row[cour_category]</option>";
    }
}
function supprimerCour($id){
    global $conn;
    $stmt = $conn->prepare("DELETE FROM cours WHERE cour_id = ?");
    $stmt2 = $conn->prepare("DELETE FROM cours_utilisateurs WHERE cour_id = ?");
    return $stmt2->execute([$id]) && $stmt->execute([$id]);
}

function modifierCour($id, $nom, $categorie, $date, $heure, $duree, $participants){
    global $conn;
    $stmt = $conn->prepare("
        UPDATE cours 
        SET cour_nom = ?, cour_category = ?, cour_date = ?, 
            cour_heure = ?, cour_dure = ?, nb_participants = ?
        WHERE cour_id = ?;
    ");
    return $stmt->execute([$nom, $categorie, $date, $heure, $duree, $participants, $id]);
}

function rechercherCours($keyword){
    global $conn;
    $stmt = $conn->prepare("
        SELECT * FROM cours 
        WHERE cour_nom LIKE ? OR cour_category LIKE ?
    ");
    $search = "%".$keyword."%";
    $stmt->execute([$search, $search]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function ajouterCour($nom, $categorie, $date, $heure, $duree, $participants){
    global $conn;
    $stmt = $conn->prepare("
        INSERT INTO cours (cour_nom, cour_category, cour_date, cour_heure, cour_dure, nb_participants)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    return $stmt->execute([$nom, $categorie, $date, $heure, $duree, $participants]);
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
// Récupérer un équipement par ID
function getEquipementById($id){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM equipements WHERE equipement_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupérer les cours qui utilisent un équipement donné
function getCoursByEquipement($idEquipement){
    global $conn;
    $stmt = $conn->prepare("
        SELECT c.*
        FROM cours c
        INNER JOIN cours_equipements ce ON ce.cour_id = c.cour_id
        WHERE ce.equipement_id = ?
        ORDER BY c.cour_date, c.cour_heure
    ");
    $stmt->execute([$idEquipement]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les cours qui ne sont PAS encore liés à cet équipement
function getCoursNonLiesEquipement($idEquipement){
    global $conn;
    $stmt = $conn->prepare("
        SELECT *
        FROM cours
        WHERE cour_id NOT IN (
            SELECT cour_id FROM cours_equipements WHERE equipement_id = ?
        )
        ORDER BY cour_date, cour_heure
    ");
    $stmt->execute([$idEquipement]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




if(isset($_POST["ajoutCour"])){
    $nom = $_POST["nomCour"];
    $categorieCour = $_POST["categorieCour"];
    $dateCour = $_POST["dateCour"];
    $heureCour = $_POST["heureCour"];
    $dureeCour = $_POST["dureeCour"];
    $maxCour = $_POST["maxCour"];
    if(ajouterCour($nom , $categorieCour , $dateCour, $heureCour ,$dureeCour,$maxCour )){
        header("Location: ../pages/cours.php");
    }else{
        echo "erreur lor lajout ";
    }
}

if(isset($_POST["modifierCour"])){
    $id = $_POST["idCour"];
    $nom = $_POST["nomCour"];
    $categorieCour = $_POST["categorieCour"];
    $dateCour = $_POST["dateCour"];
    $heureCour = $_POST["heureCour"];
    $dureeCour = $_POST["dureeCour"];
    $maxCour = $_POST["maxCour"];
    if(modifierCour($id,$nom , $categorieCour , $dateCour, $heureCour ,$dureeCour,$maxCour )){
        header("Location: ../pages/cours.php");
    }else{
        echo "erreur lor lajout ";
    }
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
    };
};