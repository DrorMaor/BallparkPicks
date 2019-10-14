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
from NBA_TeamsDict import NBA_TeamsDict

AllTeamsData = []

year = str(datetime.date.today().year);
#year = str(2020);
url = "https://www.basketball-reference.com/leagues/NBA_"+year+".html"
page = urllib.urlopen(url).read()
tableData = page[page.find("Team Stats") : page.find("Opponent Stats")]
# <tr ><th scope="row" class="right " data-stat="ranker" csk="30" >30</th><td class="left " data-stat="team_name" ><a href="/teams/MEM/2019.html">Memphis Grizzlies</a></td><td class="right " data-stat="g" >82</td><td class="right " data-stat="mp" >19880</td><td class="right " data-stat="fg" >3113</td><td class="right " data-stat="fga" >6924</td><td class="right " data-stat="fg_pct" >.450</td><td class="right " data-stat="fg3" >811</td><td class="right " data-stat="fg3a" >2368</td><td class="right " data-stat="fg3_pct" >.342</td><td class="right " data-stat="fg2" >2302</td><td class="right " data-stat="fg2a" >4556</td><td class="right " data-stat="fg2_pct" >.505</td><td class="right " data-stat="ft" >1453</td><td class="right " data-stat="fta" >1882</td><td class="right " data-stat="ft_pct" >.772</td><td class="right " data-stat="orb" >723</td><td class="right " data-stat="drb" >2703</td><td class="right " data-stat="trb" >3426</td><td class="right " data-stat="ast" >1963</td><td class="right " data-stat="stl" >684</td><td class="right " data-stat="blk" >448</td><td class="right " data-stat="tov" >1147</td><td class="right " data-stat="pf" >1801</td><td class="right " data-stat="pts" >8490</td></tr>

soup = BeautifulSoup(tableData, 'lxml')
rows = soup.findAll("tr")
AllStats = re.findall(">(.*?)<", str(rows), flags=0)

StatCounter = 0
index = 0
# go thru the entire stats, and reset for each new team
for stat in AllStats:
	if stat in NBA_TeamsDict:
		index = 0
		team = AllStats[StatCounter]
	if index == 1:  G = AllStats[StatCounter]
	if index == 13: Pt2P = AllStats[StatCounter]
	if index == 10: Pt3P = AllStats[StatCounter]
	if index == 16: FTP = AllStats[StatCounter]
	if index == 19: RB = AllStats[StatCounter]
	if index == 21: STL = AllStats[StatCounter]
	if index == 22: BLK = AllStats[StatCounter]
	if index == 23: TOV = AllStats[StatCounter]
	if index == 24: PF = AllStats[StatCounter]
	if index == 25:
		P = AllStats[StatCounter]
		teamData = TeamData(team, G, Pt2P, Pt3P, FTP, RB, STL, BLK, TOV, PF, P)
		AllTeamsData.append(teamData)

	StatCounter += 1
	index += 1

for teamData in AllTeamsData:
	print (vars(teamData))
