import threading
import socket
data = 16 #budffer size
port = 5050
device_name = socket.gethostname() 
server_ip = socket.gethostbyname(device_name) 
socket_addr = (server_ip, port)
client_count = 0

server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.bind(socket_addr)

# listen
server.listen()
print("server listening(started).....")
# v count func
def V_counter(msg):
    vowels = "aeiouAEIOU"
    count = 0
    
    for i in msg:
        if i in vowels:
            count += 1
    if count == 0:
        return "Not enough vowels"
    elif count <= 2:
        return "Enough vowels I guess"
    else:
        return "Too many vowels"

def client_handle(server_socket, client_add, client_count):
    connected = True
    while connected:
        client_msg_len = server_socket.recv(data).decode('utf-8')
        
        if client_msg_len:
            client_msg = server_socket.recv(int(client_msg_len)).decode('utf-8')
            print(f"received client{client_count} message:{client_msg}")
            
            if client_msg == "Disconnect":
                print("Server is disconnected with => ", client_add)
                connected = False
            else:
                num_vowels = V_counter(client_msg)
                server_socket.send(num_vowels.encode('utf-8'))
                
    server_socket.close()

while True:
    server_socket, client_add = server.accept()
    print("server is connected to => ", client_add)
    client_count += 1
    # threading
    thread = threading.Thread(target=client_handle, args=(server_socket, client_add, client_count))
    thread.start()