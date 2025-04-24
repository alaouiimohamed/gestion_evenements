<?php
require_once '../../config/Database.php';
require_once '../../controllers/EventController.php';

$database = new Database();
$db = $database->getConnection();
$eventController = new EventController($db);

$message = '';

// Traitement de la suppression si demandée
if(isset($_GET['delete']) && !empty($_GET['delete'])) {
    $result = $eventController->deleteEvent($_GET['delete']);
    if($result['success']) {
        $message = $result['message'];
    } else {
        $message = "Erreur lors de la suppression de l'événement.";
    }
}

// Récupération de tous les événements
$eventsResult = $eventController->getAllEvents();
$events = $eventsResult['success'] ? $eventsResult['events'] : [];

require_once '../layout/header.php';
?>

<section>
    <h2>Liste des événements</h2>
    
    <?php if(!empty($message)): ?>
        <div class="success-message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <div class="actions">
        <a href="create_event.php" class="btn">Créer un nouvel événement</a>
    </div>
    
    <?php if(empty($events)): ?>
        <p>Aucun événement n'a été trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($events as $event): ?>
                    <tr>
                        <td><?php echo $event['id']; ?></td>
                        <td><?php echo htmlspecialchars($event['titre']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($event['date_evenement'])); ?></td>
                        <td>
                            <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn">Modifier</a>
                            <a href="list_events.php?delete=<?php echo $event['id']; ?>" class="btn btn-danger delete-btn">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php
require_once '../layout/footer.php';
?>