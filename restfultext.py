import requests
import json
import mysql.connector
import time
cnx = mysql.connector.connect(user='root', password='',
                              host='127.0.0.1',
                              database='openwrt')
cursor = cnx.cursor()
query = ("SELECT iprouter,tenrouter,cputhreshold,groupname FROM routers")
cursor.execute(query)

i = 0
ip = []
ten = []
nguong = []
group = []
for (iprouter,tenrouter,cputhreshold,groupname) in cursor:
	ip.insert(i,iprouter)
	ten.insert(i,tenrouter)
	nguong.insert(i,cputhreshold)
	group.insert(i,groupname)
	i+=1



print (ip)
print (ten)
print (nguong)
print (group)
#print query


# Duyet qua tat ca router


	#lay thong tin mac, tin hieu, token, cpu

	#token.insert(j,) 
	

#ham lay token tu router
def gettoken(ip):
	url = 'http://%s/cgi-bin/luci/rpc/auth'%ip
	data = '''{
	  "id": 1,
	  "method": "login",
	  "params": [
	    "root",
	    "root"
	  ]
	}'''
	response = requests.get(url, data=data)
	json_data = response.json()
	token = json_data['result']
	return token

#ham lay thong tin cpu
def getcpu(ip,token):
	urlt= "http://%s/cgi-bin/luci/rpc/sys?auth="%ip
	url = urlt+token
	data = '''{
		"params": ["sh cpu.sh"],
		"jsonrpc" : "2.0",
		"id": 1,
		"method": "exec"
	}'''
	response = requests.get(url, data=data)
	json_data = response.json()
	cpu = json_data['result']
	return cpu.strip('\n')
#lay cac dia chi client
def getmac(ip,token):
	urlt= "http://%s/cgi-bin/luci/rpc/sys?auth="%ip
	url = urlt+token
	data = '''{
		"params": ["iwinfo wlan0 assoclist  | grep 'SNR' | awk '{print $1}'"],
		"jsonrpc": "2.0",
		"id": 1,
		"method": "exec"
	}'''
	response = requests.post(url, data=data)
	json_data = response.json()
	mac = json_data['result']
	mac = mac.strip()
	return mac.strip('\n')
#lay tin hieu
def getsignal(ip,token):
	urlt= "http://%s/cgi-bin/luci/rpc/sys?auth="%ip
	url = urlt+token
	data = '''{
		"params": ["iwinfo wlan0 assoclist  | grep 'SNR' | awk '{print $2}'"],
		"jsonrpc": "2.0",
		"id": 1,
		"method": "exec"
	}'''
	response = requests.post(url, data=data)
	json_data = response.json()
	sign = json_data['result']
	return sign.strip('\n')

def kickclient(ip,mac,token):
	urlt= "http://%s/cgi-bin/luci/rpc/sys?auth="%ip
	url = urlt+token
	data = '''{
		"params": ["ubus call hostapd.wlan0 del_client \\\" {'addr': '%s','reason': 5,'deauth': false,'ban_time': 10000}\\\""],
		"jsonrpc": "2.0",
		"id": 1,
		"method": "exec"
	}'''%mac
	response = requests.post(url, data=data)
	json_data = response.json()

def reboot(ip,token):
	urlt= "http://%s/cgi-bin/luci/rpc/sys?auth="%ip
	url = urlt+token
	data = '''{
		 "params": [""],
 		"jsonrpc": "2.0",
		 "id": 1,
		 "method": "reboot"
	}'''
	response = requests.post(url, data=data)
	json_data = response.json()

j = 0
while True:
	for j in range(i):
		token = []
		signal = []
		mac = []
		cpu = []
		
		token.insert(j,gettoken(ip[j]))
		signal.insert(j,getsignal(ip[j],token[j]))
		mac.insert(j,getmac(ip[j],token[j]))
		cpu.insert(j,getcpu(ip[j],token[j]))
	
#reboot(token)
#define nguong Warning
	canhbao = 2
	# Kiem tra CPU
	for j in range(i):
		# Neu > nguong
		print("Kiem tra CPU %s"%ip[j])
		if float(cpu[j]) > nguong[j] :
			print ("CPU %s qua tai:%s"%(ip[j],cpu[j]))
			#Kiem tra CPU cac con cung group
			print("Kiem tra CPU router he thong")
			for k in range(i) :
				if float(cpu[k]) > canhbao and group[j]==group[k] :
					kickclient(ip[k],mac[k][:17],token[k])
					print ("Kick %s"%mac[k][:17])
					print("\n")
	time.sleep(10)
		
			
				 #Neu CPU con nao tren nguong Warning
				 	#Kick 1 client tren router moi
				 #Neu CPU khong tren nguong Warning
				 	#Khong kick
			#Kick con dau tien
