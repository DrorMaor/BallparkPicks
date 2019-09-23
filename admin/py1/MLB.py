class TeamData:
	def __init__(self, team, b_RSPG, b_PA, b_AB, b_R, b_H, b_DBL, b_TRPL, b_HR, b_RBI, b_SB, b_CS, b_BB, b_SO, b_BA, b_OBP, b_SLG, b_OPS, b_OPSP, b_TB, b_GDP, b_HBP, b_SH, b_SF, b_IBB, b_LOB,                    p_RGPG, p_W, p_L, p_WLP, p_ERA, p_G, p_GS, p_GF, p_CG, p_tSho, p_cSho, p_SV, p_IP, p_H, p_R, p_ER, p_HR, p_BB, p_IBB, p_SO, p_HBP, p_BK, p_WP, p_BF, p_ERAP, p_FIP, p_WHIP, p_H9, p_HR9, p_BB9, p_SO9, p_SOW, p_LOB):
		self.team = team   # (3 letter team code)

		# batting (each param starts with b_ )
		self.b_RSPG = b_RSPG   # Runs Scored Per Game
		self.b_PA = b_PA       # Plate Appearances
		self.b_AB = b_AB       # at bats
		self.b_R = b_R         # Runs Score
		self.b_H = b_H         # Hits/Hit
		self.b_DBL = b_DBL     # Doubles
		self.b_TRPL = b_TRPL   # Triples
		self.b_HR = b_HR       # Home Runs
		self.b_RBI = b_RBI     # Runs Batted In
		self.b_SB = b_SB       # Stolen Bases
		self.b_CS = b_CS       # Caught Stealing
		self.b_BB = b_BB       # Bases on Balls/Walks
		self.b_SO = b_SO       # Strikeouts
		self.b_BA = b_BA	   # batting average
		self.b_OBP = b_OBP     # on base percentage (H + BB + HBP)/(At Bats + BB + HBP + SF)
		self.b_SLG = b_SLG     # slugging percentage
		self.b_OPSP = b_OPSP   # OPS+ (adjusted for player's park - add during home games)
		self.b_TB = b_TB       # Total Bases
		self.b_GDP = b_GDP     # Double Plays Grounded Into
		self.b_HBP = b_HBP     # Times Hit by a Pitch.b_
		self.b_SH = b_SH       # Sacrifice Hits (Sacrifice Bunts)
		self.b_SF = b_SF       # Sacrifice Flies
		self.b_IBB = b_IBB     # Intentional Bases on Balls
		self.b_LOB = b_LOB    # Runners Left On Base

		# pitching (each param starts with p_ )	
		self.p_RGPG = p_RGPG   #  Runs Allowed Per Game
		self.p_W = p_W         # wins
		self.p_L = p_L         # losses
		self.p_WLP = p_WLP     # W/L percentage
		self.p_ERA = p_ERA     # earned run average
		self.p_G = p_G         # games
		self.p_GS = p_GS       # Games Played or Pitched
		self.p_GF = p_GF       #  Games Finished
		self.p_CG = p_CG       #  Complete Game
		self.p_tSho = p_tSho   #  Shutouts by a team
		self.p_cSho = p_cSho   #  Shutouts
		self.p_SV = p_SV       #  Saves
		self.p_IP = p_IP       #  Innings Pitched
		self.p_H = p_H         #  Hits Allowed
		self.p_R = p_R         #  Runs Allowed
		self.p_ER = p_ER       #  Earned Runs Allowed
		self.p_HR = p_HR       #  Home Runs Allowed
		self.p_BB = p_BB       #  Bases on Balls/Walks
		self.p_IBB = p_IBB     #  Intentional Bases on Balls
		self.p_SO = p_SO       #  Strikeouts
		self.p_HBP = p_HBP     #  Times Hit by a Pitch
		self.p_BK = p_BK       #  Balks
		self.p_WP = p_WP       #  Wild Pitches
		self.p_BF = p_BF       #  Batters Faced
		self.p_ERAP = p_ERAP   #  ERA+
		self.p_FIP = p_FIP     #  Fielding Independent Pitching
		self.p_WHIP = p_WHIP   #  (BB + H)/IP
		self.p_H9 = p_H9       #  9 x H / IP
		self.p_HR9 = p_HR9     #  9 x HR / IP
		self.p_BB9 = p_BB9     #  9 x BB / IP
		self.p_SO9 = p_SO9     #  9 x SO / IP
		self.p_SOW = p_SOW     #  SO/W or SO/BB
		self.p_LOB = p_LOB     #  Runners Left On Base




from bs4 import BeautifulSoup
import requests
import re
import datetime
import urllib
from MLB_TeamsDict import MLB_TeamsDict

AllTeamsData = []

# batting
url = "https://www.baseball-reference.com/leagues/MLB/" + str(datetime.date.today().year) + "-standard-batting.shtml"
page = urllib.urlopen(url).read()
tableData = page[page.find("<tbody>")+1:page.find("</tbody>")]
soup = BeautifulSoup(tableData, 'lxml')
rows = soup.findAll("tr")
AllStats = re.findall(">(.*?)<", str(rows), flags=0)
#AllStats = ['', '', 'ARI', '', '', '42', '', '28.9', '', '5.20', '', '121', '', '4749', '', '4236', '', '629', '', '1100', '', '221', '', '30', '', '173', '', '603', '', '68', '', '11', '', '401', '', '1003', '', '.260', '', '.329', '', '.449', '', '.778', '', '99', '', '1900', '', '96', '', '55', '', '22', '', '34', '', '28', '', '842', '', ', ']

StatCounter = 0
index = 0
# go thru the entire stats, and reset for each new team
for stat in AllStats:
	if stat in MLB_TeamsDict:
		index = 0
		team = AllStats[StatCounter]
	if index == 7:  RSPG = AllStats[StatCounter]
	if index == 11: PA = AllStats[StatCounter]
	if index == 13: AB = AllStats[StatCounter]
	if index == 15: R = AllStats[StatCounter]
	if index == 17: H = AllStats[StatCounter]
	if index == 19: DBL = AllStats[StatCounter]
	if index == 21: TRPL = AllStats[StatCounter]
	if index == 23: HR = AllStats[StatCounter]
	if index == 25: RBI = AllStats[StatCounter]
	if index == 27: SB = AllStats[StatCounter]
	if index == 29: CS = AllStats[StatCounter]
	if index == 31: BB = AllStats[StatCounter]
	if index == 33: SO = AllStats[StatCounter]
	if index == 35:	BA = AllStats[StatCounter]
	if index == 37:	OBP = AllStats[StatCounter]
	if index == 39:	SLG = AllStats[StatCounter]
	if index == 41: OPS = AllStats[StatCounter]
	if index == 43:	OPSP = AllStats[StatCounter]
	if index == 45:	TB = AllStats[StatCounter]
	if index == 47: GDP = AllStats[StatCounter]
	if index == 49: HBP = AllStats[StatCounter]
	if index == 51: SH = AllStats[StatCounter]
	if index == 53: SF = AllStats[StatCounter]
	if index == 55: IBB = AllStats[StatCounter]
	if index == 57:
		LOB = AllStats[StatCounter]
		# this is the end of the batting data; so initialize the object here
		# (the pitching data will be added leter, to this object)
		teamData = TeamData(team, RSPG, PA, AB, R, H, DBL, TRPL, HR, RBI, SB, CS, BB, SO, BA, OBP, SLG, OPS, OPSP, TB, GDP, HBP, SH, SF, IBB, LOB, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
		AllTeamsData.append(teamData)

	StatCounter += 1
	index += 1

# pitching
url = "https://www.baseball-reference.com/leagues/MLB/" + str(datetime.date.today().year) + "-standard-pitching.shtml"
page = urllib.urlopen(url).read()
tableData = page[page.find("<tbody>")+1:page.find("</tbody>")]
soup = BeautifulSoup(tableData, 'lxml')
rows = soup.findAll("tr")
AllStats = re.findall(">(.*?)<", str(rows), flags=0)
#AllStats = ['', '', 'ARI', '', '', '25', '', '29.1', '', '4.65', '', '61', '', '62', '', '.496', '', '4.41', '', '123', '', '123', '', '123', '', '0', '', '7', '', '0', '', '28', '', '1108.2', '', '1062', '', '572', '', '543', '', '164', '', '386', '', '33', '', '1068', '', '46', '', '3', '', '37', '', '4711', '', '103', '', '4.38', '', '1.306', '', '8.6', '', '1.3', '', '3.1', '', '8.7', '', '2.77', '', '813', '', ', ']

StatCounter = 0
TeamCounter = 0
# go thru the entire stats, and reset for each new team
for stat in AllStats:
	if stat in MLB_TeamsDict:
		index = 0
	# the object was already initialized above with batting, so here we just add the pitching data
	if index == 7:  AllTeamsData[TeamCounter].p_RGPG = AllStats[StatCounter]
	if index == 9:  AllTeamsData[TeamCounter].p_W = AllStats[StatCounter]
	if index == 11: AllTeamsData[TeamCounter].p_L = AllStats[StatCounter]
	if index == 13:	AllTeamsData[TeamCounter].p_WLP = AllStats[StatCounter]
	if index == 15:	AllTeamsData[TeamCounter].p_ERA = AllStats[StatCounter]
	if index == 17:	AllTeamsData[TeamCounter].p_G = AllStats[StatCounter]
	if index == 19:	AllTeamsData[TeamCounter].p_GS = AllStats[StatCounter]
	if index == 21:	AllTeamsData[TeamCounter].p_GF = AllStats[StatCounter]
	if index == 23:	AllTeamsData[TeamCounter].p_CG = AllStats[StatCounter]
	if index == 25:	AllTeamsData[TeamCounter].p_tSho = AllStats[StatCounter]
	if index == 27:	AllTeamsData[TeamCounter].p_cSho = AllStats[StatCounter]
	if index == 29:	AllTeamsData[TeamCounter].p_SV = AllStats[StatCounter]
	if index == 31:	AllTeamsData[TeamCounter].p_IP = AllStats[StatCounter]
	if index == 33:	AllTeamsData[TeamCounter].p_H = AllStats[StatCounter]
	if index == 35:	AllTeamsData[TeamCounter].p_R = AllStats[StatCounter]
	if index == 37:	AllTeamsData[TeamCounter].p_ER = AllStats[StatCounter]
	if index == 39:	AllTeamsData[TeamCounter].p_HR = AllStats[StatCounter]
	if index == 41:	AllTeamsData[TeamCounter].p_BB = AllStats[StatCounter]
	if index == 43:	AllTeamsData[TeamCounter].p_IBB = AllStats[StatCounter]
	if index == 45:	AllTeamsData[TeamCounter].p_SO = AllStats[StatCounter]
	if index == 47:	AllTeamsData[TeamCounter].p_HBP = AllStats[StatCounter]
	if index == 49:	AllTeamsData[TeamCounter].p_BK = AllStats[StatCounter]
	if index == 51:	AllTeamsData[TeamCounter].p_WP = AllStats[StatCounter]
	if index == 53:	AllTeamsData[TeamCounter].p_BF = AllStats[StatCounter]
	if index == 55:	AllTeamsData[TeamCounter].p_ERAP = AllStats[StatCounter]
	if index == 57:	AllTeamsData[TeamCounter].p_FIP = AllStats[StatCounter]
	if index == 59:	AllTeamsData[TeamCounter].p_WHIP = AllStats[StatCounter]
	if index == 61:	AllTeamsData[TeamCounter].p_H9 = AllStats[StatCounter]
	if index == 63:	AllTeamsData[TeamCounter].p_HR9 = AllStats[StatCounter]
	if index == 65:	AllTeamsData[TeamCounter].p_BB9 = AllStats[StatCounter]
	if index == 67:	AllTeamsData[TeamCounter].p_SO9 = AllStats[StatCounter]
	if index == 69:	AllTeamsData[TeamCounter].p_SOW = AllStats[StatCounter]
	if index == 71:	AllTeamsData[TeamCounter].p_LOB = AllStats[StatCounter]

	if index == 73:
		TeamCounter += 1

	StatCounter += 1
	index += 1


for teamData in AllTeamsData:
	print (vars(teamData))
