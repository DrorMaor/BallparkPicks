from bs4 import BeautifulSoup
import requests
import re
import datetime
import urllib
import sys

url = "https://www.exchange-rates.org/history/" + sys.argv[2] + "/" + sys.argv[1] + "/T"
#print url
page = urllib.urlopen(url).read()
tableData = page[page.find("The table below") : page.find("The table above")]
tableData = tableData[tableData.find("<tbody>") : ]
soup = BeautifulSoup(tableData, 'lxml')
rows = soup.findAll("tr")
rates = re.findall(">(.*?)<", str(rows), flags=0)


index = 0
counter = 0
days = []
for td in rates:
	if counter % 12 == 1:
		day = []
		day.append(td)
	if counter % 12 == 5:
		day.append(td)
		days.append(day)
	counter = counter + 1

print days
