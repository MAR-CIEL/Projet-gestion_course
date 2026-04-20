from sense_hat import SenseHat
from time import sleep
import socket

sense = SenseHat()



r = (255, 0, 0)     # red
j = (255, 255, 0) # yellow
b = (0, 0, 255) # Blue
g = (0, 255, 0) # green


rainbow = [r, r, r, r, r, r, r, r]
copie = [g, g, g, g, g, g, g, g]
copie2 = [j, j, j, j, j, j, j, j]



i = 0
while i < 3 :
    for y in range(8):
        colour = rainbow[y]
        for x in range(8):
            sense.set_pixel(x, y, colour)
    sleep(1)
    sense.clear()
    sleep(0.4)
    i = i+1
for b in range(8):
    colour = copie[b]
    for c in range(8):
        sense.set_pixel(c, b, colour)
sleep(5)
sense.clear()    