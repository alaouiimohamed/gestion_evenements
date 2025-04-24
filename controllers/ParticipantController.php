<?php
require_once 'C:/xampp/htdocs/gestion_evenements/models/ParticipantModel.php';

class ParticipantController {
    private $participantModel;
    
    public function __construct($db) {
        $this->participantModel = new ParticipantModel($db);
    }
    
    public function createParticipant($nom, $email) {
        // Validation des données
        $errors = [];
        
        if(empty($nom)) {
            $errors[] = "Le nom est obligatoire";
        }
        
        if(empty($email)) {
            $errors[] = "L'email est obligatoire";
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format d'email invalide";
        }
        
        // Si erreurs, retourner les erreurs
        if(!empty($errors)) {
            return ["success" => false, "errors" => $errors];
        }
        
        // Sinon, créer le participant
        try {
            $result = $this->participantModel->create($nom, $email);
            if($result["success"]) {
                return ["success" => true, "id" => $result["id"], "message" => "Participant créé avec succès"];
            } else {
                return ["success" => false, "errors" => [$result["message"]]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function getParticipantByEmail($email) {
        try {
            $participant = $this->participantModel->readByEmail($email);
            if($participant) {
                return ["success" => true, "participant" => $participant];
            } else {
                return ["success" => false, "errors" => ["Participant non trouvé"]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function getAllParticipants() {
        try {
            $stmt = $this->participantModel->readAll();
            return ["success" => true, "participants" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function getParticipant($id) {
        try {
            $participant = $this->participantModel->readOne($id);
            if($participant) {
                return ["success" => true, "participant" => $participant];
            } else {
                return ["success" => false, "errors" => ["Participant non trouvé"]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function updateParticipant($id, $nom, $email) {
        // Validation des données
        $errors = [];
        
        if(empty($nom)) {
            $errors[] = "Le nom est obligatoire";
        }
        
        if(empty($email)) {
            $errors[] = "L'email est obligatoire";
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format d'email invalide";
        }
        
        if(!empty($errors)) {
            return ["success" => false, "errors" => $errors];
        }
        
        try {
            $result = $this->participantModel->update($id, $nom, $email);
            if($result["success"]) {
                return ["success" => true, "message" => "Participant mis à jour avec succès"];
            } else {
                return ["success" => false, "errors" => [$result["message"]]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function deleteParticipant($id) {
        try {
            $result = $this->participantModel->delete($id);
            if($result) {
                return ["success" => true, "message" => "Participant supprimé avec succès"];
            } else {
                return ["success" => false, "errors" => ["Erreur lors de la suppression du participant"]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
}
?>