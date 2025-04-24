<?php
class EventModel {
    private $conn;
    private $table_name = "events";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer un nouvel événement
    public function create($titre, $date, $description) {
        $query = "INSERT INTO " . $this->table_name . " (titre, date_evenement, description) VALUES (:titre, :date, :description)";
        $stmt = $this->conn->prepare($query);
        
        // Nettoyage et binding des paramètres
        $stmt->bindParam(":titre", $titre);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":description", $description);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }
    
    // Lire tous les événements
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY date_evenement DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Lire un événement par son ID
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Mettre à jour un événement
    public function update($id, $titre, $date, $description) {
        $query = "UPDATE " . $this->table_name . " SET titre = :titre, date_evenement = :date, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":titre", $titre);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":description", $description);
        
        return $stmt->execute();
    }
    
    // Supprimer un événement
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
}
?>