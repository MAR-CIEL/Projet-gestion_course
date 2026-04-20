import socket, threading

def client_handler(conn, addr):
    while True:
        data = conn.recv(1024)
        if not data:
            break
        print(addr, data.decode())
        conn.send(b"ok")
        conn.close()
        break 

s = socket.socket()
s.bind(("", 5000))
s.listen(10)
print("Serveur pret")

while True:
    conn, addr = s.accept()
    threading.Thread(target=client_handler, args=(conn, addr)).start()