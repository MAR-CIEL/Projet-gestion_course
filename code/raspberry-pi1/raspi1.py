import paho.mqtt.client as mqtt
from sense_hat import SenseHat
from time import sleep


IP_du_docker = "172.17.50.149" 


sense = SenseHat()
sense.clear()


R = (255, 0, 0)
G = (0, 255, 0)


def animation_start():
    i = 0
    while i < 3 :
        sense.clear(R)
        sleep(1)
        sense.clear()
        sleep(0.4)
        i = i+1
    
    sense.clear(G)
    sleep(5)
    sense.clear()

def on_message(client, userdata, message):
    topic = message.topic
    payload = str(message.payload.decode("utf-8"))
    
    print(f"Ordre reçu sur {topic} : {payload}")

    if topic == "covaciel/START":
        print("Lancement de l'animation de départ...")
        animation_start()
        
    elif topic == "covaciel/STOP":
        print("Arrêt : Extinction du Sense HAT")
        sense.clear(R) 
        sleep(5)
        sense.clear()


client = mqtt.Client(mqtt.CallbackAPIVersion.VERSION1, "Raspberry_Piste_Sense")
client.on_message = on_message

try:
    print(f"Connexion au Broker {IP_du_docker}...")
    client.connect(IP_du_docker, 1883)
    client.subscribe("covaciel/#")
    print("Prêt à recevoir les ordres de l'IHM.")
    client.loop_forever()
except Exception as e:
    print(f"Erreur : {e}")