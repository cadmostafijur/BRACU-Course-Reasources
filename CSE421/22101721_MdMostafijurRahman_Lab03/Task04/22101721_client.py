import socket
data = 16 #budffer size
port = 5050
device_name = socket.gethostname()
client_ip = socket.gethostbyname(device_name)

format = "utf-8"
socket_addr = (client_ip, port)
disconnected = "End"
client = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
client.connect(socket_addr)

def msg_to_send(msg) :
    message = msg.encode(format)
    msg_len = len(message)
    msg_len = str(msg_len).encode(format)
    msg_len += b" "*(data - len(msg_len))
    client.send(msg_len)
    client.send(message)
    

    print(client.recv(2048). decode(format))

while True:
    prompt = input("Enter hours(that person work):")
    
    if prompt == disconnected :
        msg_to_send(disconnected)
        break
    
    
    else:
        msg_to_send(prompt)

client.close()
        
        