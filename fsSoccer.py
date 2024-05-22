#-*- coding: utf-8 -*-
import requests
from bs4 import BeautifulSoup as bs
import pymysql
from datetime import datetime
import sys
import json
import hashlib
conn = pymysql.connect(host='127.0.0.1', user='propick', password='soon06051007?!', db='propick', charset='utf8')
curs = conn.cursor()

dtime=datetime.today().strftime("%Y%m%d")
urladdr="http://propick.kr/score/fs_soccer"+dtime+".html"
post_one = requests.get(urladdr)
post_one.raise_for_status()
post_one.encoding=None   # None 으로 설정
post_one.encoding='utf-8'  # 한글 인코딩
soup = bs(post_one.text, 'html.parser') # Soup으로 만들어 줍시다.

def addslashes(s):
    d = {'"':'\\"', "'":"\\'", "\0":"\\\0", "\\":"\\\\"}
    return ''.join(d.get(c, c) for c in s)

datas={}
ct={}
para1=[]
para2=[]
para3=[]
para4=[]
para5=[]
para6=[]
para7=[]
para8=[]
game=[]


#dtime = soup.select('#ifmenu-calendar > span.day.today > span > a')
#print(dtime);
#sys.exit()

allGames = soup.find_all('div', {'id': 'score-data'})
AG=str(allGames[0])
AG=addslashes(AG)

#print(AG)
#sys.exit()
uniqueData="soccer"+dtime

que="('soccer','"+dtime+"','"+AG+"',now(),'1','"+uniqueData+"')";

query="insert into liveScore (gubun,liveDate,gameData,lastUpdate,isdisp,uniqueData) values "+que +"  ON     DUPLICATE KEY UPDATE lastUpdate=now(), gameData='"+AG+"'"

#print(query);
#sys.exit()
curs.execute(query)


conn.commit()
conn.close()



