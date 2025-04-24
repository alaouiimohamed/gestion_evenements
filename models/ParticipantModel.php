<?php
class ParticipantModel {
    private $conn;
    private $table_name = "participants";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer un nouveau participant
    public function create($nom, $email) {
        // Vérifier si l'email existe déjà
        if($this->emailExists($email)) {
            return ["success" => false, "message" => "Cet email est déjà utilisé"];
        }
        
        $query = "INSERT INTO " . $this->table_name . " (nom, email) VALUES (:nom, :email)";
        $stmt = $this->conn->prepare($query);
        
        // Nettoyage et binding des paramètres
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":email", $email);
        
        if($stmt->execute()) {
            return ["success" => true, "id" => $this->conn->lastInsertId()];
        }
        
        return ["success" => false, "message" => "Erreur lors de la création du participant"];
    }
    
    // Vérifier si un email existe déjà
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    // Lire un participant par son email
    public function readByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Lire tous les participants
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nom ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Lire un participant par son ID
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Mettre à jour un participant
    public function update($id, $nom, $email) {
        // Vérifier si l'email existe déjà pour un autre participant
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email AND id != :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return ["success" => false, "message" => "Cet email est déjà utilisé par un autre participant"];
        }
        
        $query = "UPDATE " . $this->table_name . " SET nom = :nom, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":email", $email);
        
        if($stmt->execute()) {
            return ["success" => true];
        }
        
        return ["success" => false, "message" => "Erreur lors de la mise à jour du participant"];
    }
    
    // Supprimer un participant
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
}
?>