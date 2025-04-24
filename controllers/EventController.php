<?php
require_once 'C:\xampp\htdocs\gestion_evenements\models/EventModel.php';

class EventController {
    private $eventModel;
    
    public function __construct($db) {
        $this->eventModel = new EventModel($db);
    }
    
    public function createEvent($titre, $date, $description) {
        // Validation des données
        $errors = [];
        
        if(empty($titre)) {
            $errors[] = "Le titre est obligatoire";
        }
        
        if(empty($date)) {
            $errors[] = "La date est obligatoire";
        } else {
            // Vérifier le format de date
            $date_obj = DateTime::createFromFormat('Y-m-d', $date);
            if(!$date_obj || $date_obj->format('Y-m-d') !== $date) {
                $errors[] = "Format de date invalide (YYYY-MM-DD)";
            }
        }
        
        if(empty($description)) {
            $errors[] = "La description est obligatoire";
        }
        
        // Si erreurs, retourner les erreurs
        if(!empty($errors)) {
            return ["success" => false, "errors" => $errors];
        }
        
        // Sinon, créer l'événement
        try {
            $result = $this->eventModel->create($titre, $date, $description);
            if($result) {
                return ["success" => true, "id" => $result, "message" => "Événement créé avec succès"];
            } else {
                return ["success" => false, "errors" => ["Erreur lors de la création de l'événement"]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function getAllEvents() {
        try {
            $stmt = $this->eventModel->readAll();
            return ["success" => true, "events" => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function getEvent($id) {
        try {
            $event = $this->eventModel->readOne($id);
            if($event) {
                return ["success" => true, "event" => $event];
            } else {
                return ["success" => false, "errors" => ["Événement non trouvé"]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function updateEvent($id, $titre, $date, $description) {
        // Mêmes validations que pour la création
        $errors = [];
        
        if(empty($titre)) {
            $errors[] = "Le titre est obligatoire";
        }
        
        if(empty($date)) {
            $errors[] = "La date est obligatoire";
        } else {
            $date_obj = DateTime::createFromFormat('Y-m-d', $date);
            if(!$date_obj || $date_obj->format('Y-m-d') !== $date) {
                $errors[] = "Format de date invalide (YYYY-MM-DD)";
            }
        }
        
        if(empty($description)) {
            $errors[] = "La description est obligatoire";
        }
        
        if(!empty($errors)) {
            return ["success" => false, "errors" => $errors];
        }
        
        try {
            $result = $this->eventModel->update($id, $titre, $date, $description);
            if($result) {
                return ["success" => true, "message" => "Événement mis à jour avec succès"];
            } else {
                return ["success" => false, "errors" => ["Erreur lors de la mise à jour de l'événement"]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
    
    public function deleteEvent($id) {
        try {
            $result = $this->eventModel->delete($id);
            if($result) {
                return ["success" => true, "message" => "Événement supprimé avec succès"];
            } else {
                return ["success" => false, "errors" => ["Erreur lors de la suppression de l'événement"]];
            }
        } catch(Exception $e) {
            return ["success" => false, "errors" => ["Erreur système: " . $e->getMessage()]];
        }
    }
}
?>