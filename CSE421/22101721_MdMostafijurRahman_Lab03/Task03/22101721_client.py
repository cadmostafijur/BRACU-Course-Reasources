import socket
data = 16 #budffer size
format = "utf-8"
port = 5050
device_name = socket.gethostname()
client_ip = socket.gethostbyname(device_name)
socket_addr = (client_ip, port)

client = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
client.connect(socket_addr)

def send_msg(msg):
    msg_body = msg.encode(format)
    msg_len = len(msg_body)
    msg_len_up = str(msg_len).encode(format)
    msg_len_up = msg_len_up + b" " * (data-len(msg_len_up))
    
    client.send(msg_len_up)
    client.send(msg_body)
    
    server_response = client.recv(128).decode(format)
    if server_response != '':
        print("server respond msg:", server_response)
    
msg = ""  
while True:
    msg = input("Enter message: ")  
    if(msg != "Disconnect"):
        send_msg(msg)
    else:
        send_msg("Disconnect")
        break