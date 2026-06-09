import paho.mqtt.client as mqtt
from sense_hat import SenseHat
from time import sleep


class SenseAnimation:
    def __init__(Self):
        Self.sense = SenseHat()
        Self.rouge = (255, 0, 0)
        Self.jaune = (255, 255, 0)
        Self.vert = (0, 255, 0)
        Self.bleu = (0, 0, 255)
        Self.noir = (0, 0, 0)
        Self.blanc = (255, 255, 255)

    def start_animation(self):
        print("Lancement de l'animation de départ...")
        for _ in range(5):
            self.sense.clear(self.rouge)
            sleep(1)
            self.sense.clear()
            sleep(0.2)
        self.sense.clear(self.vert)
        sleep(7)
        self.sense.clear()

    def stop_animation(self):
        print("Arrêt de la course")
        for ligne in range(8):
            for colonne in range(8):
                if (ligne+colonne) % 2==0:
                    self.sense.set_pixel(ligne, colonne, self.blanc)
                else:
                    self.sense.set_pixel(ligne, colonne, self.noir)
        sleep(7)
        self.sense.clear()

    def danger_piste(self):
        print("danger : voiture ralenti, sortie de la Safety car")
        self.sense.clear(self.jaune)
        sleep(7)
        self.sense.clear()

    def depassement(self):
        print("Depassement en cours")
        self.sense.clear(self.bleu)
        sleep(7)
        self.sense.clear()

    def Disqualification(self, text):
        self.sense.show_letter(text)
        sleep(7)
        self.sense.clear()

    def piste_dangereuse(self):
        print("Piste dangereuse : Arret de la course")
        self.sense.clear(self.rouge)
        sleep(7)
        self.sense.clear()
        


class MqttController:
    def __init__(self, broker_ip, topic, display):
        self.broker_ip = broker_ip
        self.topic = topic
        self.display = display 
        self.client = mqtt.Client(mqtt.CallbackAPIVersion.VERSION1, "Raspberry_Piste_2")
        self.client.on_connect = self.on_connect
        self.client.on_message = self.on_message

    def on_connect(self, client, userdata, flags, rc):
        if rc == 0:
            print(f"Connecté au Broker {self.broker_ip} !")
            self.client.subscribe(self.topic)
        else:
            print(f"Échec de connexion, code : {rc}")

    def on_message(self, client, userdata, message):
        topic = message.topic
        payload = message.payload.decode("utf-8")
        print(f"Ordre reçu sur {topic} : {payload}")

        if topic == "covaciel/START":
            self.display.start_animation()
        elif topic == "covaciel/STOP":
            self.display.stop_animation()
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

    def run(self):
        try:
            print(f"Connexion au Broker {self.broker_ip}...")
            self.client.connect(self.broker_ip, 1883)
            print("Prêt à recevoir les ordres de l'IHM.")
            self.client.loop_forever()
        except Exception as e:
            print(f"Erreur de connexion : {e}")


if __name__ == "__main__":
    BROKER_ADDR = "10.51.27.2"
    TOPIC_FILTER = "covaciel/#"

    Mon_affichage = SenseAnimation()
    Mon_controleur = MqttController(BROKER_ADDR, TOPIC_FILTER, Mon_affichage)
    Mon_controleur.run()

