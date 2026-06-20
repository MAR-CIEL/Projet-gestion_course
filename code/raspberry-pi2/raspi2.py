# @author MARCHANT Alexandre
# @date 06/04/2026
# @file raspi2.py
# @brief Code qui permet de gérer le senseHAT, integrer dans les Raspberry pi
# @details Ce programme sert à gérer les SenseHAT et le broker MQTT (donc récuperer les topcis envoyer les messages envoyer sur les topics), et gérer les drapeaux également, il va aussi etre coder pour communiquer en MQTT
# @brief Différence avec l'autre Raspberry pi, c'est qu'il n'ont pas le même ID
import paho.mqtt.client as mqtt
from sense_hat import SenseHat
from time import sleep


# @brief Classe qui va servir a définir les différente fonction pour les drapeaux
class CSenseAnimation:
    # @brief Fonction qui va servir à définir les couleur des différents drapeaux 
    # @param self Instance de la classe
    def __init__(self):
        self.sense = SenseHat()
        self.rouge = (255, 0, 0)
        self.jaune = (255, 255, 0)
        self.vert = (0, 255, 0)
        self.bleu = (0, 0, 255)
        self.noir = (0, 0, 0)
        self.blanc = (255, 255, 255)

    # @brief Fonction qui va servir à définir le drapeau vert et son dérouler 
    # @details Le dérouler se passe en 2 étapes, 5 fois le draperau rouge et 1 foisn le drapeau vert (comme une vrai course de formule 1)
    # @param self Instance de la classe
    def Start(self):
        print("Lancement de l'animation de départ...")
        for _ in range(5):
            self.sense.clear(self.rouge)
            sleep(1)
            self.sense.clear()
            sleep(0.2)
        self.sense.clear(self.vert)
        sleep(7)
        self.sense.clear()

    # @brief Fonction qui va servir à définir le drapeau a damier, le drapeau de fin de course 
    # @param self Instance de la classe
    def Stop(self):
        print("Arrêt de la course")
        for ligne in range(8):
            for colonne in range(8):
                if (ligne+colonne) % 2==0:
                    self.sense.set_pixel(ligne, colonne, self.blanc)
                else:
                    self.sense.set_pixel(ligne, colonne, self.noir)
        sleep(7)
        self.sense.clear()

    # @brief Fonction qui va servir à définir le drapeau de danger de piste (drapeau rouge)
    # @param self Instance de la classe
    def DangerPiste(self):
        print("danger : voiture ralenti, sortie de la Safety car")
        self.sense.clear(self.jaune)
        sleep(7)
        self.sense.clear()

    # @brief Fonction qui va servir à définir le drapeau de depassement (drapeau bleu)
    # @param self Instance de la classe
    def Depassement(self):
        print("Depassement en cours")
        self.sense.clear(self.bleu)
        sleep(7)
        self.sense.clear()

    # @brief Fonction qui va servir à définir le drapeau de disqualification 
    # @param self Instance de la classe
    def Disqualification(self, text):
        self.sense.show_letter(text)
        sleep(7)
        self.sense.clear()

    # @brief Fonction qui va servir à définir le drapeau de danger de piste (drapeau rouge)
    # @details Le fonctionnement de ce drapeau est tout autre que les autres car il affiche le numero du concurent éliminer
    # @param self Instance de la classe
    def PisteDangereuse(self):
        print("Piste dangereuse : Arret de la course")
        self.sense.clear(self.rouge)
        sleep(7)
        self.sense.clear()
        

# @brief Classe qui va servir a gérer le MQTT 
# @details Cette classe va pouvoir gérer le broker, les topics, les messages et afficher les messages
class CMqttController:
    # @brief Fonction qui va servir à définir le MQTT
    # @details Cette fonction va servir à récuperer l'ip du broker, les différents topics, les messages et de définir le client (Raspberry_Piste_2)
    # @param self Instance de la classe
    # @param broker_ip adresse ip du broker MQTT
    # @param topics Les différents topics auxquelles on est abonnés
    # @param display Objet ou référence utilisé pour l'affichage
    def __init__(self, broker_ip, topic, display):
        self.broker_ip = broker_ip
        self.topic = topic
        self.display = display 
        self.client = mqtt.Client(mqtt.CallbackAPIVersion.VERSION1, "Raspberry_Piste_2")
        self.client.on_connect = self.on_connect
        self.client.on_message = self.on_message

    # @brief Fonction appelé lors de la connexion au broker MQTT
    # @details Cette Fonction est automatiquement appelée lorsque la connexion au broker est établie (ou échoue).
    # @param self Instance de la classe
    # @param client Instance du client MQTT
    # @param userdata Données utilisateur définies lors de la création du client
    # @param flags Drapeaux de réponse envoyés par le broker
    # @param rc Code de retour de connexion (0 = succès, autre = échec)
    # @return Aucun retour
    def on_connect(self, client, userdata, flags, rc):
        if rc == 0:
            print(f"Connecté au Broker {self.broker_ip} !")
            self.client.subscribe(self.topic)
        else:
            print(f"Échec de connexion, code : {rc}")

    # @brief Fonction appelé à la réception d'un message MQTT
    # @details Analyse le topic reçu et déclenche l'action correspondante sur l'objet d'affichage (Start, Stop, Danger, etc.)
    # @param self Instance de la classe
    # @param client Instance du client MQTT
    # @param userdata Données utilisateur définies lors de la création du client
    # @param message Objet contenant le topic et le payload reçus (message)
    # @return Aucun retour
    def on_message(self, client, userdata, message):
        topic = message.topic
        payload = message.payload.decode("utf-8")
        print(f"Ordre reçu sur {topic} : {payload}")

        if topic == "covaciel/START":
            self.display.Start()
        elif topic == "covaciel/STOP":
            self.display.Stop()
        elif topic == "covaciel/DANGER":
            self.display.danger_piste()
        elif topic == "covaciel/DEPASSEMENT":
            self.display.depassement()
        elif topic == "covaciel/DISQUALIFICATION":
            parts = payload.split('|')
            ecurie = parts[1] if len(parts) > 1 else""
            print("voiture ", ecurie, " disqualifier")
            self.display.Disqualification(ecurie)
        elif topic == "covaciel/PISTEDA":
            self.display.piste_dangereuse()

    # @brief Démarre la connexion au broker et la boucle d'écoute MQTT
    # @details Tente de se connecter au broker sur le port 1883, puis lance une boucle infinie pour recevoir les messages entrants.
    # @return Aucun retour
    # @throws Exception en cas d'échec de connexion au broker
    def run(self):
        try:
            print(f"Connexion au Broker {self.broker_ip}...")
            self.client.connect(self.broker_ip, 1883)
            print("Prêt à recevoir les ordres de l'IHM.")
            self.client.loop_forever()
        except Exception as e:
            print(f"Erreur de connexion : {e}")


# @brief Point d'entrée principal du programme
# @details Crée l'objet d'affichage et le contrôleur MQTT, puis démarre l'écoute des messages.
if __name__ == "__main__":
    BROKER_ADDR = "10.51.27.2"
    TOPIC_FILTER = "covaciel/#"

    Mon_affichage = CSenseAnimation()
    Mon_controleur = CMqttController(BROKER_ADDR, TOPIC_FILTER, Mon_affichage)
    Mon_controleur.run()

