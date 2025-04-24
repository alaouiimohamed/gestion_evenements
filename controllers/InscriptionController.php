<?php
require_once 'C:/xampp/htdocs/gestion_evenements/models/InscriptionModel.php';
require_once 'C:/xampp/htdocs/gestion_evenements/models/EventModel.php';
require_once 'C:/xampp/htdocs/gestion_evenements/models/ParticipantModel.php';

class InscriptionController {
    private $inscriptionModel;
    private $eventModel;
    private $participantModel;
    
    public function __construct($db) {
        $this->inscriptionModel = new InscriptionModel($db);
        $this->eventModel = new EventModel($db);
        $this->participantModel = new ParticipantModel($db);
    }
    
    public function createInscription($event_id, $participant_id) {
        // Validation des données
        $errors = [];
        
        // Vérifier que l'événement existe
        $event = $this->eventModel->readOne($event_id);
        if(!$event) {
            $errors[] = "L'événement spécifié n'existe pas";
        }
        
        // Vérifier que le participant existe
        $participant = $this->participantModel->readOne($participant_id);
        if(!$participant) {
            $errors[] = "Le participant spécifié n'existe pas";
        }
        
        // Si erreurs, retourner les erreurs
        if(!empty($errors)) {
            return ["success" => false, "errors" => $errors];
        }
        
        // Sinon, créer l'inscription
        try {
            $result = $this->inscriptionModel->create($event_id, $participant_id);
            if($result["success"]) {
                return ["success" => true, "id" => $result["id"], "message" => "Inscription réalisée avec succès"];
            } else {
                return ["success" => false, "errors" => [$result["message"]]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function getAllInscriptions() {
        try {
            $stmt = $this->inscriptionModel->readAllWithDetails();
            return ["success" => true, "inscriptions" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function getInscriptionsByEvent($event_id) {
        try {
            $stmt = $this->inscriptionModel->readByEvent($event_id);
            return ["success" => true, "inscriptions" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function getInscriptionsByParticipant($participant_id) {
        try {
            $stmt = $this->inscriptionModel->readByParticipant($participant_id);
            return ["success" => true, "inscriptions" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function getInscription($id) {
        try {
            $inscription = $this->inscriptionModel->readOne($id);
            if($inscription) {
                return ["success" => true, "inscription" => $inscription];
            } else {
                return ["success" => false, "errors" => ["Inscription non trouvée"]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function deleteInscription($id) {
        try {
            $result = $this->inscriptionModel->delete($id);
            if($result) {
                return ["success" => true, "message" => "Inscription supprimée avec succès"];
            } else {
                return ["success" => false, "errors" => ["Erreur lors de la suppression de l'inscription"]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
}
?>