<?php
require_once 'layout/header.php';
?>

<section class="welcome">
    <h2>Bienvenue sur le Système de Gestion des Événements</h2>
    <p>Cette application permet de gérer vos événements et les inscriptions des participants.</p>
    
    <div class="actions">
        <a href="/gestion_evenements/views/events/create_event.php" class="btn">Créer un événement</a>
        <a href="/gestion_evenements/views/participants/register_participant.php" class="btn">Inscrire un participant</a>
        <a href="/gestion_evenements/views/events/list_events.php" class="btn">Voir les événements</a>
    </div>
</section>

<?php
require_once 'layout/footer.php';
?>