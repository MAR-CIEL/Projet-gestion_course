import paho.mqtt.client as mqtt
from sense_hat import SenseHat
from time import sleep


class SenseAnimation:
    def __init__(self):
        self.sense = SenseHat()
        self.sense.clear()
        self.red = (255, 0, 0)
        self.yellow = (255, 255, 0)
        self.green = (0, 255, 0)


    def start_animation(self):
        print("Lancement de l'animation de départ...")
        for _ in range(5):
            self.sense.clear(self.red)
            sleep(1)
            self.sense.clear()
            sleep(0.2)
        self.sense.clear(self.green)
        sleep(5)
        self.sense.clear()

    def stop_animation(self):
        print("Arrêt : Extinction du Sense HAT")
        self.sense.clear(self.red)
        sleep(5)
        self.sense.clear()


class MqttController:
    def __init__(self, broker_ip, topic, display):
        self.broker_ip = broker_ip
        self.topic = topic
        self.display = display 
        self.client = mqtt.Client(mqtt.CallbackAPIVersion.VERSION1, "Raspberry_Piste_Sense")
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

    def run(self):
        try:
            print(f"Connexion au Broker {self.broker_ip}...")
            self.client.connect(self.broker_ip, 1883)
            print("Prêt à recevoir les ordres de l'IHM.")
            self.client.loop_forever()
        except Exception as e:
            print(f"Erreur de connexion : {e}")


if __name__ == "__main__":
    BROKER_ADDR = "172.17.50.149"
    TOPIC_FILTER = "covaciel/#"

    mon_affichage = SenseAnimation()
    mon_controleur = MqttController(BROKER_ADDR, TOPIC_FILTER, mon_affichage)
    mon_controleur.run()

