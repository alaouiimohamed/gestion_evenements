<?php
require_once '../../config/Database.php';
require_once '../../controllers/EventController.php';

$database = new Database();
$db = $database->getConnection();
$eventController = new EventController($db);

$event = null;
$message = '';
$errors = [];

// Vérifier si un ID d'événement a été passé
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list_events.php");
    exit;
}

$id = $_GET['id'];

// Récupérer les informations de l'événement
$eventResult = $eventController->getEvent($id);
if(!$eventResult['success']) {
    header("Location: list_events.php");
    exit;
}

$event = $eventResult['event'];

// Traitement du formulaire de mise à jour
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $eventController->updateEvent(
        $id,
        $_POST['titre'],
        $_POST['date'],
        $_POST['description']
    );
    
    if($result['success']) {
        $message = $result['message'];
        // Mettre à jour les données de l'événement après modification
        $eventResult = $eventController->getEvent($id);
        $event = $eventResult['event'];
    } else {
        $errors = $result['errors'];
    }
}

require_once '../layout/header.php';
?>

<section class="form-container">
    <h2>Modifier un événement</h2>
    
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
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($event['titre']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="date">Date *</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($event['date_evenement']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit">Mettre à jour l'événement</button>
            <a href="list_events.php" class="btn">Annuler</a>
        </div>
    </form>
</section>

<?php
require_once '../layout/footer.php';
?>