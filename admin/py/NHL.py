class TeamData:
	def __init__(self, team, W, L, P, G, GA):
		self.team = team   # (3 letter team code)
		self.W = W
		self.L = L
		self.P = P
		self.G = G
		self.GA = GA



from bs4 import BeautifulSoup
import requests
import re
import datetime
import urllib
from NHL_TeamsDict import TeamsDict

AllTeamsData = []

year = str(datetime.date.today().year +1)
url = "https://www.hockey-reference.com/leagues/NHL_" + year + ".html"
page = urllib.urlopen(url).read()
tableData = page[page.find("<tbody>")+1:page.find("</tbody>")]
soup = BeautifulSoup(tableData, 'lxml')
rows = soup.findAll("tr")
AllStats = re.findall(">(.*?)<", str(rows), flags=0)
#print (AllStats)

StatCounter = 0
index = 0
# go thru the entire stats, and reset for each new team
team = ""
for stat in AllStats:
	if stat in TeamsDict:
		index = 0
		team = TeamsDict[AllStats[StatCounter]]
	if index == 5:  W = AllStats[StatCounter]
	if index == 7: L = AllStats[StatCounter]
	if index == 11: P = AllStats[StatCounter]
	if index == 15: G = AllStats[StatCounter]
	if index == 17: 
		GA = AllStats[StatCounter]
		teamData = TeamData(team, W, L, P, G, GA)
		AllTeamsData.append(teamData)

	StatCounter += 1
	index += 1


for teamData in AllTeamsData:
	print (vars(teamData))
