# @author MARCHANT Alexandre
# @date 06/04/2026
# @file Xbee.py
# @brief Envoie une trame au vehicule autonome via le XBee emetteur (COM7) grace à l'IHM
# @details Récuperation du message envoyer par l'IHM, tranformation en trame correspondante et envoie la bonne trame au module XBEE
# @brief Usage : python Xbee.py START
# @brief Usage : python Xbee.py STOP

import serial
import sys
import time

# @brief Correspondance action -> trame
TRAMES = {
    'START': '$GO;',
    'STOP' : '$STOP;'
}

# @brief Valeur du port ainsi que le baud attribuer ainsi que du COM
PORT_XBEE = "\\\\.\\COM7"   
BAUDRATE  = 9600

# @brief Recupération de la données et renvoie la trame correspondante 
action = sys.argv[1] if len(sys.argv) > 1 else 'START'
trame  = TRAMES.get(action, '$GO;')

# @brief Delai uniquement pour START : attente du feu vert durée de 6 seconde
if action == 'START':
    print("Attente feu vert...")
    time.sleep(6)
    print("Feu vert !")

# @brief Envoie une trame de données via le module de communication XBee. 
# @brief Cette fonction tente d'ouvrir le port série configuré, d'y écrire la trame
# @brief Après l'avoir encodée, puis referme proprement le port. Si une erreur survient 
# @brief (port occupé, déconnexion), elle est capturée et affichée dans la console.
try:
    ser = serial.Serial(PORT_XBEE, BAUDRATE, timeout=1)
    ser.write(trame.encode())
    ser.close()
    print(f"OK - Trame envoyee : {trame}")
except Exception as e:
    print(f"Erreur : {e}")
