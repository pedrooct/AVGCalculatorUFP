# -*- coding: utf-8 -*-


#Imports necessários !
from lxml import html
import time
import sys
import re
from robobrowser import RoboBrowser
from bs4 import BeautifulSoup
from bs4 import Comment
import json


'''
Esta função permite estrutura o json para um formato amigável para o php intepretar
'''
def prepareJson(jsonData):
    jsonStruct= json.dumps(jsonData,sort_keys=True,indent=4, separators=(',', ': '))
    return jsonStruct

'''
Está função permite obter os creditos e respetivas disciplinas da pággina oficial da UFP
Fazemos isso usando RoboBrowser , este vai à página e retira o HTML inteiro, de seguida, enbelezamos e indexamos a estrutura da pagina ,
para depois efetuar pesquisa pela div com a class row uc, sendo estas div's as que tem as disciplinas e creditos respetivos.
Após isso colocamos a informação num dicionário (key= cadeira , val = credito) e preparamos para ser imprimo da consola , onde será recebido no PHP.
'''
def GetCreditsLicenciatura():
    br = RoboBrowser()
    br.open('http://ingresso.ufp.pt/engenharia-informatica/#study-plan',verify=False)
    src = str(br.parsed())
    soup = BeautifulSoup(src, "html.parser")
    td= soup.find_all('div',class_='row uc')
    credit=0
    cred=dict()
    optcounter=0
    for x in range(0, 31):
        st = td[x].get_text()[:-1].encode('utf-8') # codificação utf-8 devido a caracteres especiais
        if st == "Opção (a)": # if necessário devido à insconsintencia da infomação nas duas páginas, e para distinguir a opção senão é atualizada no dicionário
            if optcounter==0:
                st = "Opcão I"
                optcounter=1
            else:
                st = "Opcão II"
        credit = len(td[x].get_text())-1 #Obtem posição de credito na string
        cred[st] = td[x].get_text()[credit]# Guardar no dicionário
    print(prepareJson(cred)) #Imprime dicionário com estrutura de json

GetCreditsLicenciatura()
