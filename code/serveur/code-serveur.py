# @author MARCHANT Alexandre
# @date 27/01/2026
# @file code-serveur.py
# @brief Code qui permet une connectivité en socket avec un client (raspberry pi)
# @details Ce programme sert a connecter un serveur avec un client (ici un raspberry pi) en socket 

import socket, threading

# @brief Fonction qui gère la communication avec un client connecté
# @details Reçoit les données envoyées par le client, les affiche, puis renvoie un accusé de réception "ok" avant de fermer la connexion.
# @param conn Socket de connexion avec le client
# @param addr Tuple (adresse IP, port) du client connecté
# @return Aucun retour
def client_handler(conn, addr):
    while True:
        data = conn.recv(1024)
        if not data:
            break
        print(addr, data.decode())
        conn.send(b"ok")
        conn.close()
        break 

# @brief Création du socket serveur (TCP par défaut)
s = socket.socket()
# @brief Liaison du socket à toutes les interfaces réseau sur le port 5000
s.bind(("", 5000))
# @brief Mise en écoute du serveur (jusqu'à 10 connexions en attente)
s.listen(10)
print("Serveur pret")

# @brief Boucle principale du serveur
# @details Accepte les connexions entrantes en continu et délègue chaque client à un thread dédié pour permettre un traitement parallèle.
while True:
    conn, addr = s.accept()
    threading.Thread(target=client_handler, args=(conn, addr)).start()