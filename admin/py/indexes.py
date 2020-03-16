from bs4 import BeautifulSoup
import requests
import re
import datetime
import urllib
import sys

url = "https://finance.yahoo.com/quote/%5E" + sys.argv[1] + "/history/"
#print url
page = urllib.urlopen(url).read()


soup = BeautifulSoup(page, 'lxml')
rows = soup.findAll("span")
rates = re.findall(">(.*?)</span>", str(rows), flags=0)

counter = 0
closes = []
months = ['Jan ', 'Feb ', 'Mar ', 'Apr ', 'May ', 'Jun ', 'Jul ', 'Aug ', 'Sep ', 'Oct ', 'Nov ', 'Dec ']
for rate in rates:
	if rate[0:4] in months:
            counter = 0
	if counter == 4:
            val = rate.replace(',', '')
            if val[:2].isdigit():
                closes.append(val)

	counter = counter +1
print closes
