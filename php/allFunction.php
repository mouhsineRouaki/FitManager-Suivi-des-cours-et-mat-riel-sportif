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
    return "<div class=course-card>
                <h2 class=course-title>$row[cour_nom]</h2>

                <div class=course-info>
                    <p><strong>Catégorie :</strong> $row[cour_category]</p>
                    <p><strong>Date :</strong> $row[cour_date]</p>
                    <p><strong>Heure :</strong> $row[cour_heure]</p>
                    <p><strong>Durée :</strong> $row[cour_dure]h</p>
                    <p><strong>Participants :</strong> $row[nb_participants]</p>
                </div>
            </div>
";
}
function afficherCours(){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM cours");
    $stmt->execute();
    $result= $stmt-> fetch(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        cardCour($row);
    }
    
}