
class TeamData:
	def __init__(self, team, G, Pt2P, Pt3P, FTP, RB, STL, BLK, TOV, PF, P):
		self.team = team   # (3 letter team code)

		"""
		Legend:
		G    : Games
		Pt2P : 2 Pointers Pct
		Pt3P : 3 Pointer Pct
		FTP  : Free Throws Pct
		RB   : Rebounds
		STL  : Steals
		BLK  : Blocks
		TOV  : Turnovers
		PF   : Personal Fouls
		P    : Points
		"""

		self.G = G
		self.Pt2P = Pt2P
		self.Pt3P = Pt3P
		self.FTP = FTP
		self.RB = RB
		self.STL = STL
		self.BLK = BLK
		self.TOV = TOV
		self.PF = PF
		self.P = P

from bs4 import BeautifulSoup
import requests
import re
import datetime
import urllib
from NBA_TeamsDict import TeamsDict

AllTeamsData = []

year = str(datetime.date.today().year);
year = str(2020);
url = "https://www.basketball-reference.com/leagues/NBA_"+year+".html"
page = urllib.urlopen(url).read()
tableData = page[page.find("Team Stats") : page.find("Opponent Stats")]
tableData = tableData[tableData.find("<tbody>") : ]
soup = BeautifulSoup(tableData, 'lxml')
rows = soup.findAll("tr")
AllStats = re.findall(">(.*?)<", str(rows), flags=0)

StatCounter = 0
index = 0
# go thru the entire stats, and reset for each new team
for stat in AllStats:
	if stat in TeamsDict:
		index = 0
		team = TeamsDict[AllStats[StatCounter]]
	if index == 3:  G = AllStats[StatCounter]
	if index == 23: Pt2P = AllStats[StatCounter]
	if index == 17: Pt3P = AllStats[StatCounter]
	if index == 23: FTP = AllStats[StatCounter]
	if index == 35: RB = AllStats[StatCounter]
	if index == 39: STL = AllStats[StatCounter]
	if index == 41: BLK = AllStats[StatCounter]
	if index == 43: TOV = AllStats[StatCounter]
	if index == 45: PF = AllStats[StatCounter]
	if index == 47:
		P = AllStats[StatCounter]
		teamData = TeamData(team, G, Pt2P, Pt3P, FTP, RB, STL, BLK, TOV, PF, P)
		AllTeamsData.append(teamData)

	StatCounter += 1
	index += 1

for teamData in AllTeamsData:
	print (vars(teamData))
