#-*- coding: utf-8 -*-
from selenium import webdriver
import requests
from bs4 import BeautifulSoup as bs
import pymysql
from datetime import datetime
import sys
import json
import os
import paramiko
import shutil
import time

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

options = webdriver.ChromeOptions()
options.add_argument('headless')
options.add_argument('window-size=800x600')
options.add_argument("disable-gpu")

driver = webdriver.Chrome('/home/propick/chromedriver', chrome_options=options)
driver.implicitly_wait(3)

driver.get('http://m.flashscore.co.kr/')
time.sleep(10)
driver.implicitly_wait(3)
html = driver.page_source

dtime="fs_soccer"+datetime.today().strftime("%Y%m%d")
f = open(r"%s.html" % dtime, 'w')
print(f)
f.write(html)
f.close

localpath = './'+dtime+'.html'
remotepath = '/var/www/propick/public_html/score/'+dtime+'.html'

shutil.move (localpath , remotepath)
