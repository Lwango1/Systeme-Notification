import time
import requests
import socket
import xml.etree.ElementTree as ET
from plyer import notification

# --- CONFIGURATION MISE À JOUR ---
MON_SERVICE = "dg"  # À changer pour chaque version (dg, finance, logistique)
IP_SERVEUR = "192.168.1.0"  # Votre nouvelle IP serveur


# FONCTION POUR OBTENIR L'IP DU POSTE CLIENT
def get_ip_client():
    try:
        hostname = socket.gethostname()
        return socket.gethostbyname(hostname)
    except:
        return "0.0.0.0"


# TEST AU DÉMARRAGE (Local)
try:
    notification.notify(
        title="Système de surveillance moto",
        message=f"Surveillance active sur {IP_SERVEUR} pour : {MON_SERVICE.upper()}",
        timeout=5
    )
except:
    pass

while True:
    try:
        ip_actuelle = get_ip_client()

        # URL avec la nouvelle IP et l'IP du client pour le serveur
        URL_CHECK = f"http://{IP_SERVEUR}/api/api_master.php?action=check&service={MON_SERVICE}&ip={ip_actuelle}"

        response = requests.get(URL_CHECK, timeout=10)

        if response.status_code == 200:
            content = response.text.strip()

            # Recherche du début du XML
            start_tag = content.find('<?xml')
            if start_tag != -1:
                xml_data = content[start_tag:]
                root = ET.fromstring(xml_data)

                status_node = root.find('status')

                # Si le serveur confirme un nouveau message
                if status_node is not None and status_node.text == "success":
                    msg_node = root.find('message')
                    if msg_node is not None:
                        msg = msg_node.text

                        # Affichage de l'alerte réelle
                        notification.notify(
                            title=f"ALERTE {MON_SERVICE.upper()}",
                            message=msg,
                            app_name="Moto Surveillance System",
                            timeout=15  # Un peu plus long pour laisser le temps de lire
                        )

    except Exception:
        # En cas de coupure réseau, le script attend sans planter
        pass

    # Vérification toutes les 5 secondes
    time.sleep(5)