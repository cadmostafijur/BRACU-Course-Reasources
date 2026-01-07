import socket
data = 16 #buffer size
port = 5050
device_name=socket.gethostname()
server_ip = socket.gethostbyname(device_name)
socket_addr = (server_ip, port)
format= "utf-8"

server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.bind(socket_addr)
disconnected = "End"

# listen
server.listen()
print("server listening(started).....")


while True:
    con_st, addr = server.accept() 
    
    print("Connect to: ", addr)
    connected = True
    
    while connected :
        message_lenght = con_st.recv(data).decode(format)
        print("Lenght of the given message: " , message_lenght)
        if message_lenght :
            message_lenght= int(message_lenght)
            msg = con_st.recv(message_lenght).decode(format)
            
            if msg == disconnected :
                con_st.send("Thank you, Sir". encode(format))
                
                print("connection close with: ", addr)
                connected = False
            
            else:
                hour = int(msg)
                
                if hour <= 40:
                    salary=hour*200
                
                else:
                    salary = 8000
                    extra_hour = hour-40
                    salary += extra_hour*300
            con_st.send(("Salary: " + str(salary)).encode(format))
                    
                        
    con_st.close()
                
            
        
        
    
