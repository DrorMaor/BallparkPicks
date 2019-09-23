class TeamData:
	def __init__(self, team, o_P, o_F, o_p_C, o_p_A, o_p_Y, o_p_TD, o_p_I, o_p_FD, o_r_A, o_r_Y, o_r_TD, o_r_FD, o_n_N, o_n_Y, o_n_FD, d_P, d_F, d_p_C, d_p_A, d_p_Y, d_p_TD, d_p_I, d_p_FD, d_r_A, d_r_Y, d_r_TD, d_r_FD, d_n_N, d_n_Y, d_n_FD):
		self.team = team   # (3 letter team code)
		"""

		LEGEND:
		o_   : offense
		d_   : defense
		_p_  : passing
		_r_  : rushing
		_n_  : penalties
		_P   : points
		_F   : fumbles
		_C   : complete (only for passing)
		_A   : attempts
		_Y   : yards
		_TD  : touchtowns
		_I   : interceptions
		_FD  : first downs

		"""
		
		# offense
		self.o_P = o_P
		self.o_F = o_F
		self.o_p_C = o_p_C
		self.o_p_A = o_p_A
		self.o_p_Y = o_p_Y
		self.o_p_TD = o_p_TD
		self.o_p_I = o_p_I
		self.o_p_FD = o_p_FD
		self.o_r_A = o_r_A
		self.o_r_Y = o_r_Y
		self.o_r_TD = o_r_TD
		self.o_r_FD = o_r_FD
		self.o_n_N = o_n_N
		self.o_n_Y = o_n_Y
		self.o_n_FD = o_n_FD

		# defense
		self.d_P = d_P
		self.d_F = d_F
		self.d_p_C = d_p_C
		self.d_p_A = d_p_A
		self.d_p_Y = d_p_Y
		self.d_p_TD = d_p_TD
		self.d_p_I = d_p_I
		self.d_p_FD = d_p_FD
		self.d_r_A = d_r_A
		self.d_r_Y = d_r_Y
		self.d_r_TD = d_r_TD
		self.d_r_FD = d_r_FD
		self.d_n_N = d_n_N
		self.d_n_Y = d_n_Y
		self.d_n_FD = d_n_FD
		

from bs4 import BeautifulSoup
import requests
import re
import datetime
import urllib
from NFL_TeamsDict import NFL_TeamsDict

AllTeamsData = []

# offense
url = "https://www.pro-football-reference.com/years/" + str(datetime.date.today().year) + "/"
page = urllib.urlopen(url).read()
offense_start = page.find('all_team_stats')
page_cut = page[offense_start:]
tableData = page_cut[page_cut.find("<tbody>") : page_cut.find("</tbody>")]
soup = BeautifulSoup(tableData, 'lxml')
rows = soup.findAll("tr")
AllStats = re.findall(">(.*?)<", str(rows), flags=0)
# AllStats = [... 'Baltimore Ravens', '', '', '1', '', '59', '', '643', '', '73', '', '8.8', '', '', '', '0', '', '31', '', '23', '', '26', '', '378', '', '6', '', '0', '', '14.0', '', '17', '', '46', '', '265', '', '2', '', '5.8', '', '10', '', '4', '', '40', '', '4', '', '', '', '', '', '', '', ', ', ']


StatCounter = 0
index = 0
# go thru the entire stats, and reset for each new team
for stat in AllStats:
	if stat in NFL_TeamsDict:
		index = 0
		team = AllStats[StatCounter]
	if index == 5 : o_P = AllStats[StatCounter]
	if index == 15: o_F = AllStats[StatCounter]
	if index == 19: o_p_C = AllStats[StatCounter]
	if index == 21: o_p_A = AllStats[StatCounter]
	if index == 23: o_p_Y = AllStats[StatCounter]
	if index == 25: o_p_TD = AllStats[StatCounter]
	if index == 27: o_p_I = AllStats[StatCounter]
	if index == 31: o_p_FD = AllStats[StatCounter]
	if index == 33: o_r_A = AllStats[StatCounter]
	if index == 35: o_r_Y = AllStats[StatCounter]
	if index == 37: o_r_TD = AllStats[StatCounter]
	if index == 41: o_r_FD = AllStats[StatCounter]
	if index == 43: o_n_N = AllStats[StatCounter]
	if index == 45:	o_n_Y = AllStats[StatCounter]
	if index == 47:
		o_n_FD = AllStats[StatCounter]
		# this is the end of the offense data; so initialize the object here
		# (the defense data will be added leter, to this object)
		teamData = TeamData(team, o_P, o_F, o_p_C, o_p_A, o_p_Y, o_p_TD, o_p_I, o_p_FD, o_r_A, o_r_Y, o_r_TD, o_r_FD, o_n_N, o_n_Y, o_n_FD, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
		AllTeamsData.append(teamData)

	StatCounter += 1
	index += 1

# defense
url = "https://www.pro-football-reference.com/years/" + str(datetime.date.today().year) + "/opp.htm"
page = urllib.urlopen(url).read()
tableData = page[page.find("<h2>Team Defense</h2>") : page.find("</tbody>")]
soup = BeautifulSoup(tableData, 'lxml')
rows = soup.findAll("tr")
AllStats = re.findall(">(.*?)<", str(rows), flags=0)
# AllStats =  [...'New England Patriots', '', '', '1', '', '3', '', '308', '', '61', '', '5.0', '', '1', '', '0', '', '15', '', '27', '', '47', '', '276', '', '0', '', '1', '', '5.8', '', '11', '', '13', '', '32', '', '0', '', '2.5', '', '1', '', '6', '', '54', '', '3', '', '', '', '', '', '', '', ', ', '', ]

StatCounter = 0
# go thru the entire stats, and reset for each new team
for stat in AllStats:
	# first, find which object index it is (for that team)
	if stat in NFL_TeamsDict:
		index = 0
		TeamCounter = 0
		for teamData in AllTeamsData:
			if teamData.team == stat:
				break
			else:
				TeamCounter += 1
	# the object was already initialized above with batting, so here we just add the pitching data
	if index == 5 : AllTeamsData[TeamCounter].d_P = AllStats[StatCounter]
	if index == 15: AllTeamsData[TeamCounter].d_F = AllStats[StatCounter]
	if index == 19: AllTeamsData[TeamCounter].d_p_C = AllStats[StatCounter]
	if index == 21: AllTeamsData[TeamCounter].d_p_A = AllStats[StatCounter]
	if index == 23:	AllTeamsData[TeamCounter].d_p_Y = AllStats[StatCounter]
	if index == 25:	AllTeamsData[TeamCounter].d_p_TD = AllStats[StatCounter]
	if index == 27:	AllTeamsData[TeamCounter].d_p_I = AllStats[StatCounter]
	if index == 31:	AllTeamsData[TeamCounter].d_p_FD = AllStats[StatCounter]
	if index == 33:	AllTeamsData[TeamCounter].d_r_A = AllStats[StatCounter]
	if index == 35:	AllTeamsData[TeamCounter].d_r_Y = AllStats[StatCounter]
	if index == 37:	AllTeamsData[TeamCounter].d_r_TD = AllStats[StatCounter]
	if index == 41:	AllTeamsData[TeamCounter].d_r_FD = AllStats[StatCounter]
	if index == 43:	AllTeamsData[TeamCounter].d_n_N = AllStats[StatCounter]
	if index == 45:	AllTeamsData[TeamCounter].d_n_Y = AllStats[StatCounter]
	if index == 47:	AllTeamsData[TeamCounter].d_n_FD = AllStats[StatCounter]

	StatCounter += 1
	index += 1


for teamData in AllTeamsData:
	print (vars(teamData))
