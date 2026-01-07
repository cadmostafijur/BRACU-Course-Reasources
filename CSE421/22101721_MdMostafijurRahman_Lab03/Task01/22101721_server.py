import socket
data = 16 #budffer size
port = 5050
device_name = socket.gethostname()
server_ip = socket.gethostbyname(device_name) 
socket_add = (server_ip, port)

server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.bind(socket_add)
# listen
server.listen()
print("server listening(started).....")

while True:
    server_socket, client_add = server.accept()
    print("server is connected to --> ", client_add)
    
    connected = True
    while connected:
        client_msg_len = server_socket.recv(data).decode('utf-8')
    
        if client_msg_len:
            client_msg = server_socket.recv(int(client_msg_len)).decode('utf-8')
            if client_msg == "Disconnect":
                print("server is disconnected with --> ", client_add)
                connected = False
            else:
                print("server client message: ", client_msg)
            server_socket.send("message received".encode('utf-8'))
                
    server_socket.close()