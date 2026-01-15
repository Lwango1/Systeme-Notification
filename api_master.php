<?php
// On désactive l'affichage des erreurs texte pour ne pas casser le format XML
error_reporting(0);
ini_set('display_errors', 0);

// Utilisation du tampon de sortie pour nettoyer tout texte parasite
ob_start();

// Inclusion de la connexion à la base de données
include 'db_connect.php';

// Nettoyage automatique (supprime les éventuels "Connexion réussie" de db_connect.php)
ob_clean();

// Définition de l'en-tête XML
header('Content-Type: text/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<root>';

$action = isset($_GET['action']) ? $_GET['action'] : 'check';
$service = isset($_GET['service']) ? $_GET['service'] : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';

// --- LOGIQUE DE L'API ---

if ($conn->connect_error) {
    echo '<status>error</status>';
    echo '<message>Connexion DB échouée</message>';
} else {
    if ($action == 'notify' && !empty($service) && !empty($message)) {
        // AJOUTER UNE NOTIFICATION (Action : notify)
        $stmt = $conn->prepare("INSERT INTO notifications (service, message, lu) VALUES (?, ?, 0)");
        $stmt->bind_param("ss", $service, $message);
        if ($stmt->execute()) {
            echo '<status>success</status>';
            echo '<info>Message enregistré</info>';
        } else {
            echo '<status>error</status>';
        }
    } else {
        // VÉRIFIER LES NOTIFICATIONS (Action : check par défaut)
        $safe_service = $conn->real_escape_string($service);
        
        // La Direction Générale (dg) voit tout, les autres voient leur service respectif
        $sql = ($service == 'dg') 
            ? "SELECT * FROM notifications WHERE lu = 0 ORDER BY id DESC LIMIT 1"
            : "SELECT * FROM notifications WHERE service = '$safe_service' AND lu = 0 ORDER BY id DESC LIMIT 1";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo '<status>success</status>';
            echo '<message>' . htmlspecialchars($row['message']) . '</message>';
            
            // On marque le message comme lu pour qu'il ne s'affiche qu'une seule fois
            $id_msg = $row['id'];
            $conn->query("UPDATE notifications SET lu = 1 WHERE id = $id_msg");
        } else {
            echo '<status>empty</status>';
        }
    }
}

echo '</root>';

// On vide le tampon et on ferme le script proprement
ob_end_flush();
exit();