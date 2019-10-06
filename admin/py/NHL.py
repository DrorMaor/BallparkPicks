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

year = str(datetime.date.today().year )
year =str(2020)
url = "https://www.hockey-reference.com/leagues/NHL_" + year + ".html"
page = urllib.urlopen(url).read()
tableData = page[page.find("Eastern Conference")+1:page.find("Team Statistics")]
#tableData "-stat="games" >82</td><td class="right " data-stat="wins" >48</td><td class="right " data-stat="losses" >27</td><td class="right " data-stat="losses_ot" >7</td><td class="right " data-stat="points" >103</td><td class="right " data-stat="points_pct" >.628</td><td class="right " data-stat="goals" >228</td><td class="right " data-stat="opp_goals" >196</td><td class="right " data-stat="srs" >0.38</td><td class="right " data-stat="sos" >-0.01</td><td class="right " data-stat="points_pct_old" >.585</td><td class="right " data-stat="ro_wins" >43</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="544" ><a href="/teams/PIT/2019.html">Pittsburgh Penguins</a>*</th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >44</td><td class="right " data-stat="losses" >26</td><td class="right " data-stat="losses_ot" >12</td><td class="right " data-stat="points" >100</td><td class="right " data-stat="points_pct" >.610</td><td class="right " data-stat="goals" >273</td><td class="right " data-stat="opp_goals" >241</td><td class="right " data-stat="srs" >0.38</td><td class="right " data-stat="sos" >-0.01</td><td class="right " data-stat="points_pct_old" >.543</td><td class="right " data-stat="ro_wins" >42</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="541" ><a href="/teams/CAR/2019.html">Carolina Hurricanes</a>*</th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >46</td><td class="right " data-stat="losses" >29</td><td class="right " data-stat="losses_ot" >7</td><td class="right " data-stat="points" >99</td><td class="right " data-stat="points_pct" >.604</td><td class="right " data-stat="goals" >245</td><td class="right " data-stat="opp_goals" >223</td><td class="right " data-stat="srs" >0.26</td><td class="right " data-stat="sos" >-0.01</td><td class="right " data-stat="points_pct_old" >.561</td><td class="right " data-stat="ro_wins" >44</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="537" ><a href="/teams/CBJ/2019.html">Columbus Blue Jackets</a>*</th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >47</td><td class="right " data-stat="losses" >31</td><td class="right " data-stat="losses_ot" >4</td><td class="right " data-stat="points" >98</td><td class="right " data-stat="points_pct" >.598</td><td class="right " data-stat="goals" >258</td><td class="right " data-stat="opp_goals" >232</td><td class="right " data-stat="srs" >0.31</td><td class="right " data-stat="sos" >-0.01</td><td class="right " data-stat="points_pct_old" >.567</td><td class="right " data-stat="ro_wins" >45</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="82" ><a href="/teams/PHI/2019.html">Philadelphia Flyers</a></th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >37</td><td class="right " data-stat="losses" >37</td><td class="right " data-stat="losses_ot" >8</td><td class="right " data-stat="points" >82</td><td class="right " data-stat="points_pct" >.500</td><td class="right " data-stat="goals" >244</td><td class="right " data-stat="opp_goals" >281</td><td class="right " data-stat="srs" >-0.42</td><td class="right " data-stat="sos" >0.03</td><td class="right " data-stat="points_pct_old" >.439</td><td class="right " data-stat="ro_wins" >34</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="78" ><a href="/teams/NYR/2019.html">New York Rangers</a></th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >32</td><td class="right " data-stat="losses" >36</td><td class="right " data-stat="losses_ot" >14</td><td class="right " data-stat="points" >78</td><td class="right " data-stat="points_pct" >.476</td><td class="right " data-stat="goals" >227</td><td class="right " data-stat="opp_goals" >272</td><td class="right " data-stat="srs" >-0.52</td><td class="right " data-stat="sos" >0.03</td><td class="right " data-stat="points_pct_old" >.384</td><td class="right " data-stat="ro_wins" >26</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="72" ><a href="/teams/NJD/2019.html">New Jersey Devils</a></th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >31</td><td class="right " data-stat="losses" >41</td><td class="right " data-stat="losses_ot" >10</td><td class="right " data-stat="points" >72</td><td class="right " data-stat="points_pct" >.439</td><td class="right " data-stat="goals" >222</td><td class="right " data-stat="opp_goals" >275</td><td class="right " data-stat="srs" >-0.61</td><td class="right " data-stat="sos" >0.04</td><td class="right " data-stat="points_pct_old" >.384</td><td class="right " data-stat="ro_wins" >28</td></tr></tbody></table>      </div>   </div></div></div><div><div id="all_standings_WES" class="table_wrapper has_sticky_cols"><div class="section_heading">  <span class="section_anchor" id="standings_WES_link" data-label="Western Conference"></span><h2>Western Conference</h2>    <div class="section_heading_text">      <ul> <li>Playoff teams are marked with an asterisk (*)</li>      </ul>    </div>    		</div>   <div class="table_outer_container">      <div class="overthrow table_container" id="div_standings_WES">  <table class="suppress_all sortable stats_table" id="standings_WES" data-cols-to-freeze="1"><caption>2019 WES Standings</caption>   <colgroup><col><col><col><col><col><col><col><col><col><col><col><col><col></colgroup>   <thead>            <tr>         <th aria-label=" " data-stat="team_name" scope="col" class=" poptip left" ></th>         <th aria-label="Games Played" data-stat="games" scope="col" class=" poptip center" data-tip="Games Played" >GP</th>         <th aria-label="Wins" data-stat="wins" scope="col" class=" poptip center" data-tip="Wins" >W</th>         <th aria-label="Losses" data-stat="losses" scope="col" class=" poptip center" data-tip="Losses" >L</th>         <th aria-label="Overtime/Shootout Losses" data-stat="losses_ot" scope="col" class=" poptip center" data-tip="Overtime/Shootout Losses" >OL</th>         <th aria-label="Points" data-stat="points" scope="col" class=" poptip center" data-tip="Points" >PTS</th>         <th aria-label="Points percentage (i.e., points divided by maximum points)" data-stat="points_pct" scope="col" class=" poptip center" data-tip="Points percentage (i.e., points divided by maximum points)" >PTS%</th>         <th aria-label="Goals" data-stat="goals" scope="col" class=" poptip center" data-tip="Goals For (includes shootout wins)" >GF</th>         <th aria-label="Opp Goals" data-stat="opp_goals" scope="col" class=" poptip center" data-tip="Goals Against (includes shootout losses)" >GA</th>         <th aria-label="Simple Rating System; a team rating that takes into account average goal differential and strength of schedule. The rating is denominated in goals above/below average, where zero is average." data-stat="srs" scope="col" class=" poptip center" data-tip="Simple Rating System; a team rating that takes into account average goal differential and strength of schedule. The rating is denominated in goals above/below average, where zero is average." >SRS</th>         <th aria-label="Strength of Schedule; a rating of strength of schedule. The rating is denominated in goals above/below average, where zero is average." data-stat="sos" scope="col" class=" poptip center" data-tip="Strength of Schedule; a rating of strength of schedule. The rating is denominated in goals above/below average, where zero is average." >SOS</th>         <th aria-label="Points percentage counting no points for OT loss, and any shootout game as a tie. i.e. pre-2000 situation" data-stat="points_pct_old" scope="col" class=" poptip right" data-tip="Points percentage counting no points for OT loss, and any shootout game as a tie. i.e. pre-2000 situation" >RPt%</th>         <th aria-label="Wins in Regulation or Overtime" data-stat="ro_wins" scope="col" class=" poptip right" data-tip="Wins in Regulation or Overtime" >ROW</th>      </tr>         </thead>   <tbody><tr class="thead"><td colspan="13">Central Division</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="1047" ><a href="/teams/NSH/2019.html">Nashville Predators</a>*</th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >47</td><td class="right " data-stat="losses" >29</td><td class="right " data-stat="losses_ot" >6</td><td class="right " data-stat="points" >100</td><td class="right " data-stat="points_pct" >.610</td><td class="right " data-stat="goals" >240</td><td class="right " data-stat="opp_goals" >214</td><td class="right " data-stat="srs" >0.31</td><td class="right " data-stat="sos" >-0.01</td><td class="right " data-stat="points_pct_old" >.561</td><td class="right " data-stat="ro_wins" >43</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="542" ><a href="/teams/WPG/2019.html">Winnipeg Jets</a>*</th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >47</td><td class="right " data-stat="losses" >30</td><td class="right " data-stat="losses_ot" >5</td><td class="right " data-stat="points" >99</td><td class="right " data-stat="points_pct" >.604</td><td class="right " data-stat="goals" >272</td><td class="right " data-stat="opp_goals" >244</td><td class="right " data-stat="srs" >0.33</td><td class="right " data-stat="sos" >-0.02</td><td class="right " data-stat="points_pct_old" >.567</td><td class="right " data-stat="ro_wins" >45</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="540" ><a href="/teams/STL/2019.html">St. Louis Blues</a>*</th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >45</td><td class="right " data-stat="losses" >28</td><td class="right " data-stat="losses_ot" >9</td><td class="right " data-stat="points" >99</td><td class="right " data-stat="points_pct" >.604</td><td class="right " data-stat="goals" >247</td><td class="right " data-stat="opp_goals" >223</td><td class="right " data-stat="srs" >0.28</td><td class="right " data-stat="sos" >-0.01</td><td class="right " data-stat="points_pct_old" >.549</td><td class="right " data-stat="ro_wins" >42</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="508" ><a href="/teams/DAL/2019.html">Dallas Stars</a>*</th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >43</td><td class="right " data-stat="losses" >32</td><td class="right " data-stat="losses_ot" >7</td><td class="right " data-stat="points" >93</td><td class="right " data-stat="points_pct" >.567</td><td class="right " data-stat="goals" >210</td><td class="right " data-stat="opp_goals" >202</td><td class="right " data-stat="srs" >0.09</td><td class="right " data-stat="sos" >0.00</td><td class="right " data-stat="points_pct_old" >.530</td><td class="right " data-stat="ro_wins" >42</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="488" ><a href="/teams/COL/2019.html">Colorado Avalanche</a>*</th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >38</td><td class="right " data-stat="losses" >30</td><td class="right " data-stat="losses_ot" >14</td><td class="right " data-stat="points" >90</td><td class="right " data-stat="points_pct" >.549</td><td class="right " data-stat="goals" >260</td><td class="right " data-stat="opp_goals" >246</td><td class="right " data-stat="srs" >0.17</td><td class="right " data-stat="sos" >-0.01</td><td class="right " data-stat="points_pct_old" >.463</td><td class="right " data-stat="ro_wins" >36</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="84" ><a href="/teams/CHI/2019.html">Chicago Blackhawks</a></th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >36</td><td class="right " data-stat="losses" >34</td><td class="right " data-stat="losses_ot" >12</td><td class="right " data-stat="points" >84</td><td class="right " data-stat="points_pct" >.512</td><td class="right " data-stat="goals" >270</td><td class="right " data stat="opp_goals" >292</td><td class="right " data-stat="srs" >-0.25</td><td class="right " data-stat="sos" >0.02</td><td class="right " data-stat="points_pct_old" >.427</td><td class="right " data-stat="ro_wins" >33</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="83" ><a href="/teams/MIN/2019.html">Minnesota Wild</a></th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >37</td><td class="right " data-stat="losses" >36</td><td class="right " data-stat="losses_ot" >9</td><td class="right " data-stat="points" >83</td><td class="right " data-stat="points_pct" >.506</td><td class="right " data-stat="goals" >211</td><td class="right " data-stat="opp_goals" >237</td><td class="right " data-stat="srs" >-0.30</td><td class="right " data-stat="sos" >0.02</td><td class="right " data-stat="points_pct_old" >.470</td><td class="right " data-stat="ro_wins" >36</td></tr><tr class="thead"><td colspan="13">Pacific Division</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="1120" ><a href="/teams/CGY/2019.html">Calgary Flames</a>*</th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >50</td><td class="right " data-stat="losses" >25</td><td class="right " data-stat="losses_ot" >7</td><td class="right " data-stat="points" >107</td><td class="right " data-stat="points_pct" >.652</td><td class="right " data-stat="goals" >289</td><td class="right " data-stat="opp_goals" >227</td><td class="right " data-stat="srs" >0.70</td><td class="right " data-stat="sos" >-0.06</td><td class="right " data-stat="points_pct_old" >.634</td><td class="right " data-stat="ro_wins" >50</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="551" ><a href="/teams/SJS/2019.html">San Jose Sharks</a>*</th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >46</td><td class="right " data-stat="losses" >27</td><td class="right " data-stat="losses_ot" >9</td><td class="right " data-stat="points" >101</td><td class="right " data-stat="points_pct" >.616</td><td class="right " data-stat="goals" >289</td><td class="right " data-stat="opp_goals" >261</td><td class="right " data-stat="srs" >0.30</td><td class="right " data-stat="sos" >-0.04</td><td class="right " data-stat="points_pct_old" >.579</td><td class="right " data-stat="ro_wins" >46</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="508" ><a href="/teams/VEG/2019.html">Vegas Golden Knights</a>*</th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >43</td><td class="right " data-stat="losses" >32</td><td class="right " data-stat="losses_ot" >7</td><td class="right " data-stat="points" >93</td><td class="right " data-stat="points_pct" >.567</td><td class="right " data-stat="goals" >249</td><td class="right " data-stat="opp_goals" >230</td><td class="right " data-stat="srs" >0.19</td><td class="right " data-stat="sos" >-0.04</td><td class="right " data-stat="points_pct_old" >.518</td><td class="right " data-stat="ro_wins" >40</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="86" ><a href="/teams/ARI/2019.html">Arizona Coyotes</a></th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >39</td><td class="right " data-stat="losses" >35</td><td class="right " data-stat="losses_ot" >8</td><td class="right " data-stat="points" >86</td><td class="right " data-stat="points_pct" >.524</td><td class="right " data-stat="goals" >213</td><td class="right " data-stat="opp_goals" >223</td><td class="right " data-stat="srs" >-0.14</td><td class="right " data-stat="sos" >-0.02</td><td class="right " data-stat="points_pct_old" >.470</td><td class="right " data-stat="ro_wins" >35</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="81" ><a href="/teams/VAN/2019.html">Vancouver Canucks</a></th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >35</td><td class="right " data-stat="losses" >36</td><td class="right " data-stat="losses_ot" >11</td><td class="right " data-stat="points" >81</td><td class="right " data-stat="points_pct" >.494</td><td class="right " data-stat="goals" >225</td><td class="right " data-stat="opp_goals" >254</td><td class="right " data-stat="srs" >-0.35</td><td class="right " data-stat="sos" >0.01</td><td class="right " data-stat="points_pct_old" >.427</td><td class="right " data-stat="ro_wins" >29</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="80" ><a href="/teams/ANA/2019.html">Anaheim Ducks</a></th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >35</td><td class="right " data-stat="losses" >37</td><td class="right " data-stat="losses_ot" >10</td><td class="right " data-stat="points" >80</td><td class="right " data-stat="points_pct" >.488</td><td class="right " data-stat="goals" >199</td><td class="right " data-stat="opp_goals" >251</td><td class="right " data-stat="srs" >-0.63</td><td class="right " data-stat="sos" >0.01</td><td class="right " data-stat="points_pct_old" >.427</td><td class="right " data-stat="ro_wins" >32</td></tr><tr          class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="79" ><a href="/teams/EDM/2019.html">Edmonton Oilers</a></th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >35</td><td class="right " data-stat="losses" >38</td><td class="right " data-stat="losses_ot" >9</td><td class="right " data-stat="points" >79</td><td class="right " data-stat="points_pct" >.482</td><td class="right " data-stat="goals" >232</td><td class="right " data-stat="opp_goals" >274</td><td class="right " data-stat="srs" >-0.51</td><td class="right " data-stat="sos" >0.01</td><td class="right " data-stat="points_pct_old" >.427</td><td class="right " data-stat="ro_wins" >32</td></tr><tr class="full_table" ><th scope="row" class="left " data-stat="team_name" csk="71" ><a href="/teams/LAK/2019.html">Los Angeles Kings</a></th><td class="right " data-stat="games" >82</td><td class="right " data-stat="wins" >31</td><td class="right " data-stat="losses" >42</td><td class="right " data-stat="losses_ot" >9</td><td class="right " data-stat="points" >71</td><td class="right " data-stat="points_pct" >.433</td><td class="right " data-stat="goals" >202</td><td class="right " data-stat="opp_goals" >263</td><td class="right " data-stat="srs" >-0.73</td><td class="right " data-stat="sos" >0.02</td><td class="right " data-stat="po
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
	if index == 5: W = AllStats[StatCounter]
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
