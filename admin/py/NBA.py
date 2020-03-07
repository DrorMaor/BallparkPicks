
class TeamData:
        def __init__(self, team, W, L, PSG, PAG):
                self.team = team   # (3 letter team code)

                """
                Legend:
                W    : wins
                L    : losses
                PSG  : Points Scored per Game
                PAG  : Points Against per Game
                """

                self.W = W
                self.L = L
                self.PSG = PSG
                self.PAG = PAG

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
tableData = page[page.find("Conference Standings") : page.find("Division Standings")]
tableData = tableData[tableData.find("<tbody>") : ]
soup = BeautifulSoup(tableData, 'lxml')
rows = soup.findAll("tr")
AllStats = re.findall(">(.*?)<", str(rows), flags=0)
#print AllStats

StatCounter = 0
index = 0
# go thru the entire stats, and reset for each new team
for stat in AllStats:
        if stat in TeamsDict:
                index = 0
                team = TeamsDict[AllStats[StatCounter]]
        if index == 5:  W = AllStats[StatCounter]
        if index == 7:  L = AllStats[StatCounter]
        if index == 13: PSG = AllStats[StatCounter]
        if index == 15:
            PAG = AllStats[StatCounter]
            teamData = TeamData(team, W, L, PSG, PAG)
            AllTeamsData.append(teamData)

        StatCounter += 1
        index += 1

for teamData in AllTeamsData:
        print (vars(teamData))

