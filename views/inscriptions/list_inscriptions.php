<?php
require_once '../../config/Database.php';
require_once '../../controllers/InscriptionController.php';

$database = new Database();
$db = $database->getConnection();
$inscriptionController = new InscriptionController($db);

$message = '';

// Traitement de la suppression si demandée
if(isset($_GET['delete']) && !empty($_GET['delete'])) {
    $result = $inscriptionController->deleteInscription($_GET['delete']);
    if($result['success']) {
        $message = $result['message'];
    } else {
        $message = "Erreur lors de la suppression de l'inscription.";
    }
}

// Récupération de toutes les inscriptions
$inscriptionsResult = $inscriptionController->getAllInscriptions();
$inscriptions = $inscriptionsResult['success'] ? $inscriptionsResult['inscriptions'] : [];

require_once '../layout/header.php';
?>

<section>
    <h2>Liste des inscriptions</h2>
    
    <?php if(!empty($message)): ?>
        <div class="success-message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <div class="actions">
        <a href="../participants/register_participant.php" class="btn">Créer une nouvelle inscription</a>
    </div>
    
    <?php if(empty($inscriptions)): ?>
        <p>Aucune inscription n'a été trouvée.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Événement</th>
                    <th>Date de l'événement</th>
                    <th>Participant</th>
                    <th>Email du participant</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($inscriptions as $inscription): ?>
                    <tr>
                        <td><?php echo $inscription['id']; ?></td>
                        <td><?php echo htmlspecialchars($inscription['event_titre']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($inscription['date_evenement'])); ?></td>
                        <td><?php echo htmlspecialchars($inscription['participant_nom']); ?></td>
                        <td><?php echo htmlspecialchars($inscription['participant_email']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($inscription['date_inscription'])); ?></td>
                        <td>
                            <a href="list_inscriptions.php?
                            <a href="list_inscriptions.php?delete=<?php echo $inscription['id']; ?>" class="btn btn-danger delete-btn">Annuler l'inscription</a>
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