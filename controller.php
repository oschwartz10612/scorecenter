<?php
session_start();

// DB Connection --------------
require_once 'login.php';
	$mysqli = mysqli_init();
	mysqli_options($mysqli, MYSQLI_OPT_LOCAL_INFILE, true);
	mysqli_real_connect($mysqli, $db_hostname,$db_username,$db_password,$db_database);
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}


// Begin MAIN METHOD -------------------------->	
	
if (isset($_GET['addTournament'])) {
	clearTournament();
	header("Location: tournament_detail.php");	
	exit();
}
else if (isset($_GET['deleteTournament'])) {	
	$_SESSION["tournamentId"] = $_GET['deleteTournament'];
	deleteTournament($_GET['deleteTournament'], $mysqli);
	header("Location: tournament.php");
	exit();
}
else if (isset($_GET['addNewEvent'])) {	
	clearEvent();
	header("Location: event_detail.php");
	exit();
}
else if (isset($_GET['editEvent'])) {
	clearEvent();	
	loadEvent($_GET['editEvent'], $mysqli);
	header("Location: event_detail.php");
	exit();
}
else if (isset($_GET['deleteEvent'])) {
	//deleteEvent($_GET['deleteEvent'], $mysqli);	
	header("Location: event.php");
	exit();
}
else if (isset($_GET['saveEvent'])) {
	saveEvent($mysqli);	
	loadAllEvents($mysqli);
	header("Location: event.php");
	exit();
}

else if (isset($_GET['cancelEvent'])) {	
	header("Location: event.php");
	exit();
}
else if ($_GET['command'] != null and $_GET['command'] == 'validateDeleteTeam') {
	cacheTournamnent();
	deleteTournamentTeam($mysqli, $_GET['TournTeamRowId']);
	exit();
}
else if ($_GET['command'] != null and $_GET['command'] == 'validateDeleteEvent') {
	cacheTournamnent();
	deleteTournamentEvent($mysqli, $_GET['TournEventRowId']);
	exit();
}
else if (isset($_GET['searchTournament']) or ($_GET['command'] != null and $_GET['command'] == 'loadAllTournaments')) {
	if (isset($_GET['searchTournament'])) {
		$_SESSION["fromTournamentDate"] = $_GET['fromDate'];
		$_SESSION["toTournamentDate"] = $_GET['toDate'];
		$_SESSION["tournamentsNumber"] = $_GET['tournamentsNumber'];
	}
	if ($_SESSION["tournamentsNumber"] == null or $_SESSION["tournamentsNumber"] == '') $_SESSION["tournamentsNumber"] = "20";
	loadAllTournaments($mysqli);
	
	header("Location: tournament.php");
	exit();
}
else if (isset($_GET['searchEvent']) or ($_GET['command'] != null and $_GET['command'] == 'loadAllEvents')) {
	if (isset($_GET['searchEvent'])) {
		$_SESSION["eventFilterNumber"] = $_GET['eventsNumber'];
		$_SESSION["eventFilterName"] = $_GET['eventName'];
	}
	loadAllEvents($mysqli);
	header("Location: event.php");
	exit();	
}

else if (isset($_GET['loadTournament'])) {
	clearTournament();
	$_SESSION["tournamentId"] = $_GET['loadTournament'];
	loadTournament($_GET['loadTournament'], $mysqli);
	header("Location: tournament_detail.php");
	exit();
}
else if (isset($_GET['enterScores'])) {
	$_SESSION["tournamentId"] = $_GET['enterScores'];
	loadTournamentEvents($mysqli);
	header("Location: tournament_events.php");
	exit();
}
else if (isset($_GET['enterEventScores'])) {
	$_SESSION["tournEventId"] = $_GET['enterEventScores'];
	loadEventScores($mysqli);
	header("Location: event_scores.php");
	exit();
}
else if (isset($_GET['cancelEvent'])) {
		$_SESSION["tournEventId"] = null;
		loadTournamentEvents($mysqli);
		header("Location: tournament_events.php");
		exit();
}
else if (isset($_GET['saveEventScores'])) {
		saveEventScores($mysqli);
		$_SESSION["tournEventId"] = null;
		loadTournamentEvents($mysqli);
		header("Location: tournament_events.php");
		exit();
}


else if (isset($_GET['printScores'])) {

}

else if (isset($_GET['saveTournament'])) {
	cacheTournamnent();
	saveTournament($mysqli);
	header("Location: tournament.php");
	exit();
}

else if (isset($_GET['cancelTournament'])) {
	clearTournament();
	header("Location: tournament.php");
	exit();
}
else if ($_GET['command'] != null and $_GET['command'] == 'addEvent') {
	cacheTournamnent();
	addEvent($_GET['eventAdded'], $mysqli);
	
	//header("Location: tournament_detail.php");
	exit();
}
else if ($_GET['command'] != null and $_GET['command'] == 'addTeam') {
	cacheTournamnent();
	addTeam($_GET['teamAdded'], $mysqli);
	
	//header("Location: tournament_detail.php");
	exit();
}

// No commands were met. Return to Home Page
else {	
	//header("Location: index.php");
	exit();
}

?>
<!-- END MAIN METHOD -->



<!-- FUNCTIONS -->


<?php


// TOURNAMENT DISPLAY SCREEN ---------------------------------------
	function loadAllTournaments($mysqli) {
		$query = "SELECT TOURNAMENT_ID, NAME, LOCATION,DIVISION, DATE_FORMAT(DATE,'%m/%d/%Y') 'DATE1' FROM TOURNAMENT WHERE 1=1 ";	
		if ($_SESSION["fromTournamentDate"] !=null and $_SESSION["fromTournamentDate"] != '') {
			$date1 = strtotime($_SESSION["fromTournamentDate"]); $date = date('Y-m-d', $date1 );
			$query = $query . " and DATE >= '".$date."' ";
		}
		if ($_SESSION["toTournamentDate"] !=null and $_SESSION["toTournamentDate"] != '') {
			$date1 = strtotime($_SESSION["toTournamentDate"]); $date = date('Y-m-d', $date1 );
			$query = $query . " and DATE <= '".$date."' ";
		}
		 
		$query = $query . " ORDER BY DATE DESC ";
		
		if ($_SESSION["tournamentsNumber"] !=null and $_SESSION["tournamentsNumber"] != '') {
			$query = $query . " LIMIT ".$_SESSION["tournamentsNumber"];
		}
		
		$_SESSION["allTournaments"] = $query;
	}
	
	
	function deleteTournament($id, $mysqli) {
		
		// delete score_table
		
		// delete TOURN_EVENT_LINK
		
		// delete TOURN_TEAM_LINK
		
		// DELETE TOURNAMENT
		//$result = $mysqli->query("DELETE FROM TOURNAMENT WHERE TOURNAMENT_ID = " .$id); 
		
	}


// MANAGE TOURNAMENTS SCREEN ---------------------------------------
	function cacheTournamnent() {
		if ($_GET['tournamentName'] != null) $_SESSION["tournamentName"] = $_GET['tournamentName'];
		if ($_GET['tournamentDivision'] != null) $_SESSION["tournamentDivision"] = $_GET['tournamentDivision'];
		if ($_GET['tournamentLocation'] != null) $_SESSION["tournamentLocation"] = $_GET['tournamentLocation'];
		if ($_GET['tournamentDate'] != null) $_SESSION["tournamentDate"] = $_GET['tournamentDate'];
		if ($_GET['numberEvents'] != null) $_SESSION["numberEvents"] = $_GET['numberEvents'];
		if ($_GET['numberTeams'] != null) $_SESSION["numberTeams"] = $_GET['numberTeams'];
		if ($_GET['highestScore'] != null) $_SESSION["highestScore"] = $_GET['highestScore'];
		if ($_GET['tournamentDescription'] != null) $_SESSION["tournamentDescription"] = $_GET['tournamentDescription'];
		
		// Team Cache - teamNumber, alternateTeam
		$count = 0;
		$teamList = $_SESSION["teamList"];
		while ($count < 100) {
			$team = $teamList[$count];
			if ($_GET['teamNumber'.$count] != null or $_GET['alternateTeam'.$count] != null) {	
				if ($_GET['teamNumber'.$count] != null) {			
					$team[2] = $_GET['teamNumber'.$count];	
				}
				if ($_GET['alternateTeam'.$count] != null) {			
					$team[3] = $_GET['alternateTeam'.$count];	
				}
				$teamList[$count] = $team;
				$_SESSION["teamList"] = $teamList;	
				$count++;		
			} 
			else {
				break;
			}			
		}
		
		// Events Cache - trialEvent
		$count = 0;
		$eventList = $_SESSION["eventList"];
		
		//$event = $eventList['1'];
		//echo $event['0'];
		
		while ($count < 100) {
			$event = $eventList[$count];
			if ($_GET['trialEvent'.$count] != null) {	
				
				$event['2'] = $_GET['trialEvent'.$count];	
				$eventList[$count] = $event;

				$_SESSION["eventList"] = $eventList;	

				$count++;		
			} 
			else {
				break;
			}			
		}

	}
	
	function clearTournament() {
		$_SESSION["tournamentName"] = null;
		$_SESSION["tournamentLocation"] = null;
		$_SESSION["tournamentDivision"] = null;
		$_SESSION["tournamentDate"] = null;
		$_SESSION["numberEvents"] = null;
		$_SESSION["numberTeams"] = null;
		$_SESSION["highestScore"] = null;
		$_SESSION["tournamentDescription"] = null;
		$_SESSION["tournamentId"] = null;
		$_SESSION["eventList"] = null;
		$_SESSION["teamList"] = null;
	}
	
	function addTeam($selectedTeam,$mysqli) {
		// Validation: cannot add existing Team or blank	
			$teamList = null;
			if ($_SESSION["teamList"] == null) $teamList = array();
			else $teamList = $_SESSION["teamList"];
		
			$error = FALSE;
			$errorStr = "";			
			$count = 0;
			
			if ($selectedTeam == '') { $error = TRUE; $errorStr = 'error1';}	
			if ($teamList) {
				foreach ($teamList as $team) { 
					$count++;
					if ($selectedTeam == $team['0']) { $error = TRUE; $errorStr = 'error1';}	
				}
			}

			if ($_GET['numberTeams'] != null and $_GET['numberTeams'] != '' and $_GET['numberTeams'] < $count+1) {
				$error = TRUE; 
				$errorStr = 'error2';
			}
			
			
		
			if (!$error) {
				//echo $_SERVER['REQUEST_URI'];
				// Load Event Name
				$result = $mysqli->query("SELECT NAME FROM TEAM WHERE TEAM_ID = ".$selectedTeam); 
				$row1 = $result->fetch_row();
	
				$team = array($selectedTeam, $row1['0'], "", "","", "1"); // 0: TEAM_ID 1: NAME 2:TEAM_NUMBER 3: ALTERNATE 4: TOURN_TEAM_ID 5: NEW TEAM 0/1
				array_push($teamList, $team);
				$_SESSION["teamList"] = $teamList;
				reloadTournamentTeam();
			}
			else {
				echo $errorStr;
			}
	}
	
	function addEvent($selectedEvent, $mysqli) {
		// Validation: cannot add existing event or blank
			$eventList = null;
			if ($_SESSION["eventList"] == null) $eventList = array();
			else $eventList = $_SESSION["eventList"];
		
			$error = FALSE;
			$errorStr = "";			
			$count = 0;
		
			if ($selectedEvent == '') { $error = TRUE; $errorStr = 'error1';}
			if ($eventList) {
				foreach ($eventList as $event) { 
					$count++;
					if ($selectedEvent == $event['0']) { $error = TRUE; $errorStr = 'error1';}						
				}
			}
			
			if ($_GET['numberEvents'] != null and $_GET['numberEvents'] != '' and $_GET['numberEvents'] < $count+1) {
				$error = TRUE; 
				$errorStr = 'error2';
			}
	
			if (!$error) {
				// Load Event Name
				$result = $mysqli->query("SELECT NAME FROM EVENT WHERE EVENT_ID = ".$selectedEvent); 
				$row1 = $result->fetch_row();
	
				$event = array($selectedEvent, $row1['0'], "","","1"); // 0: EVENT_ID 1: NAME 2:TRIAL_EVENT 3: TOURN_EVENT_ID 4: New Event 0/1
				array_push($eventList, $event);
				$_SESSION["eventList"] = $eventList;
				reloadTournamentEvent();
			} else {
				echo $errorStr;
			}
	}
	
	function deleteTournamentTeam($mysqli, $row) {
		// Remove From Cache
		$teamList = $_SESSION["teamList"];
		$count = 0;
		if ($teamList) {
			foreach ($teamList as $key => $team) { 
				if ($row == $count) {				
					$result = $mysqli->query("SELECT TES.SCORE FROM TEAM_EVENT_SCORE TES WHERE TES.TOURN_TEAM_ID = " .$team[4]);
					if ($result) {
						$row = $result->fetch_row();
						if ($row['0'] != null and $row['0'] != '') echo 'error';
						else {
							// delete tourn team
							$result = $mysqli->query("DELETE FROM TOURNAMENT_TEAM WHERE TOURN_TEAM_ID = " .$team[4]);
							unset($teamList[$key]);
							$teamList = array_values($teamList);
							$_SESSION["teamList"] = $teamList;
							reloadTournamentTeam();
						}
					}
					else {
						unset($teamList[$key]);
						$teamList = array_values($teamList);
						$_SESSION["teamList"] = $teamList;
						reloadTournamentTeam();
					}	
					break;
				}
				$count++;
			}
		}	
	}
	
	function deleteTournamentEvent($mysqli, $row) {
		// Remove From Cache
		$eventList = $_SESSION["eventList"];
		$count = 0;
		if ($eventList) {
			foreach ($eventList as $key => $event) { 
				if ($row == $count) {
					$result = $mysqli->query("SELECT TES.SCORE FROM TEAM_EVENT_SCORE TES WHERE TES.TOURN_EVENT_ID = " .$event[3]);
					if ($result) {
						$row = $result->fetch_row();
						if ($row['0'] != null and $row['0'] != '') echo 'error';
						else {
							// delete tourn event
							$result = $mysqli->query("DELETE FROM TOURNAMENT_EVENT WHERE TOURN_EVENT_ID = " .$event[3]);
							unset($eventList[$key]);
							$eventList = array_values($eventList);
							$_SESSION["eventList"] = $eventList;
							reloadTournamentEvent();
						}
					} 
					else {
						unset($eventList[$key]);
						$eventList = array_values($eventList);
						$_SESSION["eventList"] = $eventList;
						reloadTournamentEvent();
					}			
					break;
				}
				$count++;		
			}
		}
	}
	
	function reloadTournamentTeam() {
			$teamList = $_SESSION["teamList"];
			$teamCount = 0;
			if ($teamList) {		
				foreach ($teamList as $team) {
					echo '<tr>';
      				echo '<td>'; echo $team['1']; echo '</td>';
      				echo '<td><div class="col-xs-5 col-md-5">';
      				echo '<input type="number"  class="form-control" size="10" onkeydown="limit(this);" onkeyup="limit(this);" 
      						min="0" max="100" step="1" 
      						name="teamNumber'.$teamCount.'" id="teamNumber'.$teamCount.'" value="'.$team['2'].'">';
      				echo '</div></td>';
					echo '<td><div class="col-xs-5 col-md-5">'; 
					echo '<select   class="form-control" name="alternateTeam'.$teamCount.'" id="alternateTeam'.$teamCount.'" >';
					echo '<option value="0"'; if($team['3'] == '' or $$team['3'] == 0){echo("selected");} echo '>No</option>';
					echo '<option value="1"'; if($team['3'] == 1){echo("selected");} echo '>Yes</option>';
					echo '</select>';
					echo '</div></td>';
					echo '<td><button type="button" class="btn btn-xs btn-danger" name="deleteTeam" onclick="validateDeleteTeam(this)" value='.$team['4'].'>Delete</button></td>';
					echo '</tr>';
					
					$teamCount++;
				}
			} 
	}
	
	function reloadTournamentEvent() {
			$eventList = $_SESSION["eventList"];
			$eventCount = 0;
			if ($eventList) {				
				foreach ($eventList as $event) {
					echo '<tr>';
      				echo '<td>'; echo $event['1']; echo '</td>';
					echo '<td><div class="col-xs-5 col-md-5">'; 
					echo '<select  class="form-control" name="trialEvent'.$eventCount.'" id="trialEvent'.$eventCount.'">';
					echo '<option value="0"'; if($event['2'] == '' or $event['2'] == 0){echo("selected");} echo '>No</option>';
					echo '<option value="1"'; if($event['2'] == 1){echo("selected");} echo '>Yes</option>';
					echo '</select>';
					echo '</div></td>';
					echo '<td><button type="button" class="btn btn-xs btn-danger" name="deleteEvent" onclick="validateDeleteEvent(this)" 
						 value='.$event['3'].'>Delete</button></td>';
					echo '</tr>';
					
					$eventCount++;
				}
			} 
	}
	
	
	
	
		function loadTournament($id, $mysqli) {
	
		 	$tournamentRow = array("","","","","","","","","","","");
 			$result = $mysqli->query("SELECT * FROM TOURNAMENT WHERE TOURNAMENT_ID = " .$id); 
 			if ($result) {
 				$tournamentRow = $result->fetch_row();
 				
 				$_SESSION["tournamentName"] = $tournamentRow['1'];
 				$_SESSION["tournamentDivision"] = $tournamentRow['3'];
 				$_SESSION["tournamentLocation"] = $tournamentRow['2'];
 				$_SESSION["numberEvents"] = $tournamentRow['5'];
 				$_SESSION["numberTeams"] = $tournamentRow['6'];
 				$_SESSION["highestScore"] = $tournamentRow['7'];
 				$_SESSION["tournamentDescription"] = $tournamentRow['8'];
 				
 				$date = strtotime($tournamentRow['4']);
 				$_SESSION["tournamentDate"] = date('m/d/Y', $date);
 				
    		}
	
			// Load Events
			$eventList = array();
			$result = $mysqli->query("SELECT TE.EVENT_ID, E.NAME, TE.TRIAL_EVENT_FLAG, TE.TOURN_EVENT_ID FROM TOURNAMENT_EVENT TE INNER JOIN TOURNAMENT T on 
									T.TOURNAMENT_ID=TE.TOURNAMENT_ID 
									INNER JOIN EVENT E on E.EVENT_ID=TE.EVENT_ID WHERE TE.TOURNAMENT_ID= " .$id. " ORDER BY UPPER(E.NAME) ASC "); 
 			if ($result) {
 				while($eventRow = $result->fetch_array()) {
 					$event = array();
 					array_push($event, $eventRow['0']);
 					array_push($event, $eventRow['1']);
 					array_push($event, $eventRow['2']);
 					array_push($event, $eventRow['3']);
 					array_push($event, "0");
				
 					array_push($eventList, $event);
 				}
			}			
			$_SESSION["eventList"] = $eventList;
			
			// Load Teams
			$teamList = array();
			$result = $mysqli->query("SELECT TT.TEAM_ID, T.NAME, TT.TEAM_NUMBER, TT.ALTERNATE_FLAG, TT.TOURN_TEAM_ID FROM TOURNAMENT_TEAM TT INNER JOIN TOURNAMENT TR on 		
									TR.TOURNAMENT_ID=TT.TOURNAMENT_ID 
									INNER JOIN TEAM T on T.TEAM_ID=TT.TEAM_ID WHERE TT.TOURNAMENT_ID= " .$id. " ORDER BY TT.TEAM_NUMBER ASC "); 
 			if ($result) {
 				while($teamRow = $result->fetch_array()) {
 					$team = array();
 					array_push($team, $teamRow['0']);
 					array_push($team, $teamRow['1']);
 					array_push($team, $teamRow['2']);
 					array_push($team, $teamRow['3']);
 					array_push($team, $teamRow['4']);
 					array_push($team, "0");
				
 					array_push($teamList, $team);
 				}
			}			
			$_SESSION["teamList"] = $teamList;
	}
	
	
	
	// SAVE TOURNAMENT
	function saveTournament($mysqli) {

		// Formate
			$date = $_SESSION['tournamentDate'];
			if ($date == '') $date = null;
			else { 
				$date1 = strtotime($date);
				$date = date('Y-m-d H:i:s', $date1 );				
			}
			$name = $_SESSION['tournamentName'];
			$location = $_SESSION["tournamentLocation"];
			$division = $_SESSION['tournamentDivision'];
			$numberEvents = $_SESSION['numberEvents'];
			$numberTeams = $_SESSION['numberTeams'];
			$highestScore = $_SESSION["highestScore"];
			$description = $_SESSION['tournamentDescription'];
	
		// if Tournament id is null create new
		if ($_SESSION["tournamentId"] == null) { 
			$result = $mysqli->query("select max(TOURNAMENT_ID) + 1 from TOURNAMENT");
			$row = $result->fetch_row(); 
			$id = 0;
			if ($row != null) $id = $row['0'];  
			$_SESSION["tournamentId"] = $id;
			
			$query = $mysqli->prepare("INSERT INTO TOURNAMENT (TOURNAMENT_ID, NAME, LOCATION, DIVISION, DATE, NUMBER_EVENTS, NUMBER_TEAMS, 
			HIGHEST_SCORE_POSSIBLE, DESCRIPTION) VALUES (".$id.", ?, ?, ?, ?, ?, ?, ?,?) ");
			
			$query->bind_param('ssssiiis',$name,$location, $division,$date, $numberEvents, $numberTeams, $highestScore, $description);
			
			$query->execute();
			$query->free_result();
			//echo $query;
			//$result = mysql_query($query);
		}
		else {
			$query = $mysqli->prepare("UPDATE TOURNAMENT SET NAME=?, LOCATION=?, DIVISION=?, DATE=?, NUMBER_EVENTS=?,NUMBER_TEAMS=?,
			HIGHEST_SCORE_POSSIBLE=?, DESCRIPTION=? WHERE TOURNAMENT_ID=".$_SESSION["tournamentId"]);
			
			$query->bind_param('ssssiiis',$name,$location, $division,$date, $numberEvents, $numberTeams, $highestScore, $description);
			$query->execute();
			$query->free_result();
			//$result = mysql_query($query);		

		
		}
	
	
		// save events
		// 0: EVENT_ID 1: NAME 2: TRIAL_EVENT 3: TOURN_EVENT_ID
		$eventList = $_SESSION["eventList"];
		if ($eventList) {
			foreach ($eventList as $event) {
			if ($event['3'] == null or $event['3'] == '') {
				// Generate Next TOURN_EVENT_ID
				$result = $mysqli->query("select max(TOURN_EVENT_ID) + 1 from TOURNAMENT_EVENT");
				$row = $result->fetch_row();
				$id = 0;
				if ($row['0'] != null) $id = $row['0']; 
				
				$query = $mysqli->prepare("INSERT INTO TOURNAMENT_EVENT (TOURN_EVENT_ID, TOURNAMENT_ID, EVENT_ID, TRIAL_EVENT_FLAG) VALUES (".$id.", ?, ?, ?) ");
				$query->bind_param('iii',$_SESSION["tournamentId"],$event['0'], $event['2']); 
				$query->execute();
			}
			else {
				$query = $mysqli->prepare("UPDATE TOURNAMENT_EVENT SET TRIAL_EVENT_FLAG=? WHERE TOURN_EVENT_ID=".$event['3']);			
				$query->bind_param('i',$event['2']);
				$query->execute();
			}
			
			
				
			}
		}
		 
		
		
		// save teams
		// 0: TEAM_ID 1: NAME 2:TEAM_NUMBER 3: ALTERNATE 4: TOURN_TEAM_ID
		$teamList = $_SESSION["teamList"];
		if ($teamList) {
			foreach ($teamList as $team) {
			
			if ($team['4'] == null or $team['4'] == '') {
				// Generate Next TOURN_TEAM_ID
				$result = $mysqli->query("select max(TOURN_TEAM_ID) + 1 from TOURNAMENT_TEAM");
				$row = $result->fetch_row();
				$id = 0;
				if ($row['0'] != null) $id = $row['0'];
				 
				$query = $mysqli->prepare("INSERT INTO TOURNAMENT_TEAM (TOURN_TEAM_ID, TOURNAMENT_ID, TEAM_ID, TEAM_NUMBER, ALTERNATE_FLAG) VALUES (".$id.", ?, ?, ?,?) ");
				$query->bind_param('iiii',$_SESSION["tournamentId"],$team['0'], $team['2'], $team['3']); 
				$query->execute();
			}
			else {
				$query = $mysqli->prepare("UPDATE TOURNAMENT_TEAM SET TEAM_NUMBER=?, ALTERNATE_FLAG=? WHERE TOURN_TEAM_ID=".$team['4']);			
				$query->bind_param('ii',$team['2'], $team['3']);
				$query->execute();
			}
			
			
				
			}
		}
		
		// save Confirmation
		$_SESSION['savesuccessTournament'] = "1";	
	
	}
	
	
	
		
// DISPLAY TOURNAMENT'S EVENTS SCREEN ---------------------------------------	
	function loadTournamentEvents($mysqli) {
	
		$query = "SELECT TE.EVENT_ID, E.NAME, TE.TRIAL_EVENT_FLAG, TE.TOURN_EVENT_ID, COUNT(TES.TEAM_EVENT_SCORE_ID) as SCORES_COMPLETED, 
					T.NUMBER_TEAMS FROM TOURNAMENT_EVENT TE 
					INNER JOIN TOURNAMENT T on T.TOURNAMENT_ID=TE.TOURNAMENT_ID 
					INNER JOIN EVENT E on E.EVENT_ID=TE.EVENT_ID 
					LEFT JOIN TEAM_EVENT_SCORE TES on TES.TOURN_EVENT_ID=TE.TOURN_EVENT_ID AND TES.SCORE IS NOT NULL									
					WHERE TE.TOURNAMENT_ID=".$_SESSION["tournamentId"]. 
					" GROUP BY EVENT_ID,NAME, TRIAL_EVENT_FLAG,TOURN_EVENT_ID, NUMBER_TEAMS
					ORDER BY UPPER(E.NAME) ASC"; 
					
		$_SESSION["tournamentEventsQuery"] = $query;
		
		 $result = $mysqli->query("SELECT * FROM TOURNAMENT WHERE TOURNAMENT_ID = " .$_SESSION["tournamentId"]); 
 			if ($result) {
 				$tournamentRow = $result->fetch_row();
 				
 				$_SESSION["tournamentName"] = $tournamentRow['1'];
 				$_SESSION["tournamentDivision"] = $tournamentRow['3'];
 				$_SESSION["tournamentLocation"] = $tournamentRow['2'];
 				$_SESSION["numberEvents"] = $tournamentRow['5'];
 				$_SESSION["numberTeams"] = $tournamentRow['6'];
 				$_SESSION["highestScore"] = $tournamentRow['7'];
 				$_SESSION["tournamentDescription"] = $tournamentRow['8'];
 				
 				$date = strtotime($tournamentRow['4']);
 				$_SESSION["tournamentDate"] = date('m/d/Y', $date);
 				
    		}
		
		
	}
	
	
// MANAGE EVENT SCORES SCREEN ---------------------------------------	
	function loadEventScores($mysqli) {
				
		 $result = $mysqli->query("SELECT E.NAME, T.HIGHEST_SCORE_POSSIBLE FROM EVENT E INNER JOIN TOURNAMENT_EVENT TE ON TE.EVENT_ID=E.EVENT_ID 
		 					INNER JOIN TOURNAMENT T ON T.TOURNAMENT_ID=TE.TOURNAMENT_ID WHERE TE.TOURN_EVENT_ID = " .$_SESSION["tournEventId"]); 
 			if ($result) {
 				$tournamentRow = $result->fetch_row(); 				
 				$_SESSION["eventName"] = $tournamentRow['0'];
 				$_SESSION["tournamentHighestScore"] = $tournamentRow['1'];
    		}
    		
    	 $result = $mysqli->query("SELECT T.NAME, TT.TEAM_NUMBER, TES.SCORE, TES.TEAM_EVENT_SCORE_ID, TT.TOURN_TEAM_ID 
    	 					FROM TEAM T INNER JOIN TOURNAMENT_TEAM TT ON TT.TEAM_ID=T.TEAM_ID 
    	 					LEFT JOIN TEAM_EVENT_SCORE TES on TES.TOURN_TEAM_ID=TT.TOURN_TEAM_ID AND TES.TOURN_EVENT_ID = " .$_SESSION["tournEventId"].
    	 					" WHERE TT.TOURNAMENT_ID = " .$_SESSION["tournamentId"]. " ORDER BY TEAM_NUMBER ASC "); 
 			$teamEventScoreList = array();
 			if ($result) {
 				while($scoreRow = $result->fetch_array()) {
 					$scoreRecord = array();	
 					array_push($scoreRecord, $scoreRow['0']);
 					array_push($scoreRecord, $scoreRow['1']);
 					array_push($scoreRecord, $scoreRow['2']);
 					array_push($scoreRecord, $scoreRow['3']);
 					array_push($scoreRecord, $scoreRow['4']);	
 						
 					array_push($teamEventScoreList, $scoreRecord);
 				}
    		}
    		$_SESSION["teamEventScoreList"] = $teamEventScoreList;
	}
	
	function saveEventScores($mysqli) {	
		$scoreList = $_SESSION["teamEventScoreList"];
		if ($scoreList) {
			$teamCount = 0;
			foreach ($scoreList as $score) {
				$value = $_GET['teamScore'.$teamCount];
				if ($value == '' || $value == '0') $value = null;
					
				if ($score['3'] == null or $score['3'] == '') {
					$result = $mysqli->query("select max(TEAM_EVENT_SCORE_ID) + 1 from TEAM_EVENT_SCORE");
					$row = $result->fetch_row();
					$id = 0;
					if ($row['0'] != null) $id = $row['0']; 
				
					$query = $mysqli->prepare("INSERT INTO TEAM_EVENT_SCORE (TEAM_EVENT_SCORE_ID, TOURN_TEAM_ID, TOURN_EVENT_ID, SCORE) VALUES (".$id.", ?, ?, ?) ");
					$query->bind_param('iii',$score['4'],$_SESSION["tournEventId"], $value); 
					$query->execute();
					$score['3'] = $id;
				}
				else {
					$query = $mysqli->prepare("UPDATE TEAM_EVENT_SCORE SET SCORE=? WHERE TEAM_EVENT_SCORE_ID=".$score['3']);			
					$query->bind_param('i',$value);
					$query->execute();
				}
				$teamCount++;	
			}
		}	
		$_SESSION["teamEventScoreList"] = $scoreList;
		$_SESSION['savesuccessScore'] = "1";
	}


	// MANAGE EVENTS SCREEN ---------------------------------------
	function loadAllEvents($mysqli) {
			$eventList = array();
			$query = "Select * from EVENT WHERE 1=1 ";
			if ($_SESSION["eventFilterName"] != null) {
				$query = $query . " AND NAME LIKE '".$_SESSION["eventFilterName"]."%' " ;
			}
			$query = $query . " ORDER BY NAME ASC ";
			if ($_SESSION["eventFilterNumber"] != null) {
				$query = $query . " LIMIT ".$_SESSION["eventFilterNumber"];
			}
			
			$result = $mysqli->query($query); 
 			if ($result) {
				while($eventRow = $result->fetch_array()) {
 					$eventRecord = array();	
					array_push($eventRecord, $eventRow['0']);
					array_push($eventRecord, $eventRow['1']);
 				
					array_push($eventList, $eventRecord);
				}
			}
		
		
		$_SESSION["eventsList"] = $eventList;
	}
	
	function clearEvent() {
		$_SESSION["eventId"] = null;
		$_SESSION["eventName"] = null;
		$_SESSION["eventDescription"] = null;
	}


	function loadEvent($id, $mysqli) {
		$result = $mysqli->query("SELECT * FROM EVENT WHERE EVENT_ID = " .$id); 
 			if ($result) {
 				$eventRow = $result->fetch_row();	
 				$_SESSION["eventId"] = $eventRow['0'];
 				$_SESSION["eventName"] = $eventRow['1'];
 				$_SESSION["eventDescription"] = $eventRow['2']; 				
    		}
	}


	function saveEvent($mysqli) {
		// if Event id is null create new
		if ($_SESSION["eventId"] == null) { 
			$result = $mysqli->query("select max(EVENT_ID) + 1 from EVENT");
			$row = $result->fetch_row(); 
			$id = 0;
			if ($row != null) $id = $row['0'];  
			$_SESSION["eventId"] = $id;
			
			$query = $mysqli->prepare("INSERT INTO EVENT (EVENT_ID, NAME, COMMENTS) VALUES (".$id.", ?, ?) ");
			
			$query->bind_param('ss',$_GET["eventName"], $_GET["eventDescription"]);
			
			$query->execute();
			$query->free_result();
		}
		else {
			$query = $mysqli->prepare("UPDATE EVENT SET NAME=?, COMMENTS=? WHERE EVENT_ID=".$_SESSION["eventId"]);
			
			$query->bind_param('ss',$_GET["eventName"], $_GET["eventDescription"]);
			$query->execute();
			$query->free_result();
		}
		// save Confirmation
		$_SESSION['savesuccessEvent'] = "1";	
	}





?>