<?php 
require_once "../config/database.php";

function totalCours(){
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM cours");
    $stmt->execute();
    $result= $stmt->fetch(PDO::FETCH_ASSOC);
    echo "$result[total]";
}


function cardCour($row){
    echo '
    <div class="bg-white shadow-lg rounded-xl p-6 hover:shadow-2xl transition-all duration-300 cursor-pointer flex flex-col items-center space-y-4" 
         onclick="toggleDetails(this)">

        <!-- IMAGE DU COURS -->
        <img src="'.($row['cour_image'] ?? 'https://via.placeholder.com/100').'" 
             alt="'.$row['cour_nom'].'" 
             class="w-24 h-24 rounded-full border-4 border-blue-500 object-cover" />

        <!-- NOM + CATÉGORIE -->
        <h2 class="text-xl font-semibold text-blue-600">'.$row['cour_nom'].'</h2>
        <p class="text-gray-500 font-medium">'.$row['cour_category'].'</p>

        <!-- DESCRIPTION -->
        <p class="text-center text-gray-600 text-sm line-clamp-2">'.$row['cour_description'].'</p>

        <!-- DÉTAILS SUPPLÉMENTAIRES (cachés par défaut) -->
        <div class="details hidden w-full mt-3 text-gray-700 text-sm space-y-1">
            <p><strong>Date :</strong> '.$row['cour_date'].'</p>
            <p><strong>Heure :</strong> '.$row['cour_heure'].'</p>
            <p><strong>Durée :</strong> '.$row['cour_dure'].'h</p>
            <p><strong>Participants :</strong> '.$row['nb_participants'].'</p>
            <p><strong>Prix :</strong> '.$row['cour_prix'].' DH</p>
        </div>
    </div>';
}

function cardCourHome($row){
    // Même design que cardCour, mais sans boutons d'administration
    cardCour($row);
}




function getCoursParCategory($category){
    global $conn;
    if($category === ""){
        $stmt = $conn->prepare("SELECT * FROM cours LIMIT 6;");
        $stmt->execute();
    }else{
        $stmt = $conn->prepare("SELECT * FROM cours WHERE cour_category=? LIMIT 6;");
        $stmt->execute([$category]);
    }
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row){
        cardCourHome($row);
    }
}

function afficherCours(){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM cours");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

function afficherCoursParAray(array $tab){
    foreach($tab as $t){
        cardCour($t);
    }
}

function showCategory(){
    global $conn;
    $stmt = $conn->prepare("SELECT DISTINCT cour_category FROM cours");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row){
        echo "<option value='$row[cour_category]'>$row[cour_category]</option>";
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

function getRepartitionCoursParCategorie() {
    global $conn;

    $sql = "SELECT cour_category, COUNT(*) AS total
            FROM cours
            GROUP BY cour_category
            ORDER BY total DESC 
            LIMIT 3";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
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

/* === FORMULAIRES === */

if(isset($_POST["ajoutCour"])){
    $nom = $_POST["nomCour"];
    $categorieCour = $_POST["categorieCour"];
    $dateCour = $_POST["dateCour"];
    $heureCour = $_POST["heureCour"];
    $dureeCour = $_POST["dureeCour"];
    $maxCour = $_POST["maxCour"];

    if(ajouterCour($nom , $categorieCour , $dateCour, $heureCour ,$dureeCour,$maxCour )){
        header("Location: ../pages/cours.php");
    } else {
        echo "Erreur lors de l'ajout";
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
    } else {
        echo "Erreur lors de la modification";
    }
}
?>
