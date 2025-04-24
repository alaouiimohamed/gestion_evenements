<?php
require_once '../../config/Database.php';
require_once '../../controllers/EventController.php';

$database = new Database();
$db = $database->getConnection();
$eventController = new EventController($db);

$message = '';
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $eventController->createEvent(
        $_POST['titre'],
        $_POST['date'],
        $_POST['description']
    );
    
    if($result['success']) {
        $message = $result['message'];
    } else {
        $errors = $result['errors'];
    }
}

require_once '../layout/header.php';
?>

<section class="form-container">
    <h2>Créer un nouvel événement</h2>
    
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
            <input type="text" id="titre" name="titre" required>
        </div>
        
        <div class="form-group">
            <label for="date">Date *</label>
            <input type="date" id="date" name="date" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" rows="5" required></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit">Créer l'événement</button>
        </div>
    </form>
</section>

<?php
require_once '../layout/footer.php';
?>