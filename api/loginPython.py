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
    for x in range(0, 5):
        number+=resultN[x]

    if number == numberP:
        br.open('https://m.portal.ufp.pt/notas.php',verify=False)
        src = str(br.parsed())
        soup = BeautifulSoup(src, "html.parser")
        comments=soup.find_all(string=lambda text:isinstance(text,Comment))
        return json.loads(comments[0])
    else:
        return  false


result = GetGrades(sys.argv[1],sys.argv[2])
if not result:
    print false
else:
    value = prepareJson(result)
    print value
