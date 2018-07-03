# -*- coding: utf-8 -*-
from lxml import html
import time
import sys
import re
from robobrowser import RoboBrowser
from bs4 import BeautifulSoup
from bs4 import Comment
import json

def prepareJson(jsonData):
    jsonStruct= json.dumps(jsonData,sort_keys=True,indent=4, separators=(',', ': '))
    return jsonStruct

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
        st = td[x].get_text()[:-1].encode('utf-8')
        if st == "Opção (a)":
            if optcounter==0:
                st = "Opcão I"
                optcounter=1
            else:
                st = "Opcão II"
        credit = len(td[x].get_text())-1
        cred[st] = td[x].get_text()[credit]
    print(prepareJson(cred))

GetCreditsLicenciatura()
