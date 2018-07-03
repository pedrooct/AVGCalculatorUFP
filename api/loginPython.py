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
Está função permite obter as cadeiras e notas dos alunos com base na informação de login.
Primeiro utiliza os dados do utilizador para efetuar login na pagina mobile da UFP, se o numero no header for igual
ao numero enviado como parametro , então o login foi efetuado com sucesso, senão retorna false
Após isto , vamos retirar as notas do utilizador , pela qual enviamos em formato json para a consola.
'''
def GetGrades(numberP,password ):
    br = RoboBrowser()
    br.open('https://m.portal.ufp.pt/',verify=False)
    form = br.get_form()
    form['utilizador'] = numberP
    form['password'] = password
    br.submit_form(form)

    src = str(br.parsed())
    start = '<div id="headertitle">UFP - '
    end = '</div>'

    resultN = re.search("%s(.*)%s" % (start, end), src).group(1)
    number=""
    for x in range(0, 5): # retiramos só numero do utilizador
        number+=resultN[x]

    if number == numberP: # se for igual vamos buscar as notas
        br.open('https://m.portal.ufp.pt/notas.php',verify=False)
        src = str(br.parsed())
        soup = BeautifulSoup(src, "html.parser") # enbelezamos e indexamos para formato HTML
        comments=soup.find_all(string=lambda text:isinstance(text,Comment))# na página da UFP as notas encontra-se num comentário na página , o que torna o processo mais simples
        return json.loads(comments[0].encode('utf-8')) # colocamos o resultado em formato JSON
    else:
        return  false


result = GetGrades(sys.argv[1],sys.argv[2])#Recebe como parametro o numero e palavra-passe do utilizador
if not result: # se for igual a falso , retorna falso
    print false
else:
    value = prepareJson(result) ## se não for igual a falso , prepara o json e imprime para a consola.
    print value
