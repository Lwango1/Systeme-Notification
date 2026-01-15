<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Système d'Alerte Interne</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding: 50px; background: #f4f4f9; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 400px; }
        h2 { color: #333; margin-top: 0; }
        select, textarea, button { width: 100%; margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; cursor: pointer; font-weight: bold; }
        button:hover { background: #0056b3; }
        .success { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h2>📢 Envoyer une Alerte</h2>
        <form action="api_master.php" method="GET">
            <input type="hidden" name="action" value="notify">
            
            <label>Service destinataire :</label>
            <select name="service">
                <option value="dg">Direction Générale (DG)</option>
                <option value="finance">Finance</option>
                <option value="logistique">Logistique</option>
            </select>

            <label>Message :</label>
            <textarea name="message" rows="3" placeholder="Tapez votre message ici..." required></textarea>

            <button type="submit">Envoyer la notification</button>
        </form>
    </div>
</body>
</html>