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
            <button class="course-btn btn-view" onclick="viewCours('.$row['cour_id'].')">Voir</button>
            <button class="course-btn btn-edit" onclick="editCours('.$row['cour_id'].')">Modifier</button>
            <button class="course-btn btn-delete" onclick="deleteCours('.$row['cour_id'].')">Supprimer</button>
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
    $stmt = $conn-> prepare("delete from cours where cour_id = $id");
    $stmt-> execute();
}
function modifierCour($id){
    global $conn;
    $stmt = $conn-> prepare("SELECT DISTINCT cour_category from cours");
    $stmt-> execute();
}