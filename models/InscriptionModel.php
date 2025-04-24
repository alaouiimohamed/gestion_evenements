<?php
class InscriptionModel {
    private $conn;
    private $table_name = "inscriptions";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer une nouvelle inscription
    public function create($event_id, $participant_id) {
        // Vérifier si l'inscription existe déjà
        if($this->inscriptionExists($event_id, $participant_id)) {
            return ["success" => false, "message" => "Ce participant est déjà inscrit à cet événement"];
        }
        
        $query = "INSERT INTO " . $this->table_name . " (event_id, participant_id, date_inscription) VALUES (:event_id, :participant_id, NOW())";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":event_id", $event_id);
        $stmt->bindParam(":participant_id", $participant_id);
        
        if($stmt->execute()) {
            return ["success" => true, "id" => $this->conn->lastInsertId()];
        }
        
        return ["success" => false, "message" => "Erreur lors de la création de l'inscription"];
    }
    
    // Vérifier si une inscription existe déjà
    public function inscriptionExists($event_id, $participant_id) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE event_id = :event_id AND participant_id = :participant_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":event_id", $event_id);
        $stmt->bindParam(":participant_id", $participant_id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    // Lire toutes les inscriptions avec détails
    public function readAllWithDetails() {
        $query = "SELECT i.id, i.date_inscription, e.titre as event_titre, e.date_evenement, p.nom as participant_nom, p.email as participant_email 
                 FROM " . $this->table_name . " i
                 JOIN events e ON i.event_id = e.id
                 JOIN participants p ON i.participant_id = p.id
                 ORDER BY i.date_inscription DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Lire les inscriptions pour un événement spécifique
    public function readByEvent($event_id) {
        $query = "SELECT i.id, i.date_inscription, p.nom as participant_nom, p.email as participant_email 
                 FROM " . $this->table_name . " i
                 JOIN participants p ON i.participant_id = p.id
                 WHERE i.event_id = :event_id
                 ORDER BY i.date_inscription DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":event_id", $event_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Lire les inscriptions pour un participant spécifique
    public function readByParticipant($participant_id) {
        $query = "SELECT i.id, i.date_inscription, e.titre as event_titre, e.date_evenement 
                 FROM " . $this->table_name . " i
                 JOIN events e ON i.event_id = e.id
                 WHERE i.participant_id = :participant_id
                 ORDER BY e.date_evenement DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":participant_id", $participant_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Lire une inscription spécifique
    public function readOne($id) {
        $query = "SELECT i.*, e.titre as event_titre, p.nom as participant_nom, p.email as participant_email 
                 FROM " . $this->table_name . " i
                 JOIN events e ON i.event_id = e.id
                 JOIN participants p ON i.participant_id = p.id
                 WHERE i.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Supprimer une inscription
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
    
    // Supprimer toutes les inscriptions d'un événement
    public function deleteByEvent($event_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE event_id = :event_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":event_id", $event_id);
        
        return $stmt->execute();
    }
    
    // Supprimer toutes les inscriptions d'un participant
    public function deleteByParticipant($participant_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE participant_id = :participant_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":participant_id", $participant_id);
        
        return $stmt->execute();
    }
}
?>