<?php
require_once '../../config/Database.php';
require_once '../../controllers/ParticipantController.php';
require_once '../../controllers/EventController.php';
require_once '../../controllers/InscriptionController.php';

$database = new Database();
$db = $database->getConnection();
$participantController = new ParticipantController($db);
$eventController = new EventController($db);
$inscriptionController = new InscriptionController($db);

// Récupération de tous les événements pour la liste déroulante
$eventsResult = $eventController->getAllEvents();
$events = $eventsResult['success'] ? $eventsResult['events'] : [];

$message = '';
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Créer ou récupérer le participant
    $participantId = null;
    $participantResult = $participantController->getParticipantByEmail($_POST['email']);
    
    if($participantResult['success']) {
        // Participant existe déjà
        $participantId = $participantResult['participant']['id'];
    } else {
        // Créer un nouveau participant
        $createResult = $participantController->createParticipant($_POST['nom'], $_POST['email']);
        if($createResult['success']) {
            $participantId = $createResult['id'];
        } else {
            $errors = $createResult['errors'];
        }
    }
    
    // Si nous avons un participant, procéder à l'inscription
    if($participantId && isset($_POST['event_id']) && !empty($_POST['event_id'])) {
        $inscriptionResult = $inscriptionController->createInscription($_POST['event_id'], $participantId);
        
        if($inscriptionResult['success']) {
            $message = $inscriptionResult['message'];
        } else {
            $errors = array_merge($errors, $inscriptionResult['errors']);
        }
    }
}

require_once '../layout/header.php';
?>

<section class="form-container">
    <h2>Inscrire un participant à un événement</h2>
    
    <?php if(!empty($message)): ?>
        <div class="success-message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if(!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="event_id">Événement *</label>
            <select id="event_id" name="event_id" required>
                <option value="">-- Sélectionnez un événement --</option>
                <?php foreach($events as $event): ?>
                    <option value="<?php echo $event['id']; ?>">
                        <?php echo htmlspecialchars($event['titre']) . ' (' . date('d/m/Y', strtotime($event['date_evenement'])) . ')'; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <button type="submit">Inscrire</button>
        </div>
    </form>
</section>

<?php
require_once '../layout/footer.php';
?>