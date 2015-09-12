<?php session_start(); 
include_once('score_center_objects.php');
include_once('logon_check.php');
	require_once 'login.php';
	$mysqli = mysqli_init();
	mysqli_options($mysqli, MYSQLI_OPT_LOCAL_INFILE, true);
	mysqli_real_connect($mysqli, $db_hostname,$db_username,$db_password,$db_database);
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	// Security Level Check
	include_once('role_check.php');
	checkUserRole(2);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php include_once('libs/head_tags.php'); ?>
	
  <script type="text/javascript">
  $(document).ready(function(){
  
    //	$("#addTournament").click(function(){
     //   	alert("add");
    //	});

    	
    	
	});
	function validate() {
		if ($('#teamName').val().trim() == '') {
			displayError("<strong>Validation Error:</strong> Team Name is required.");
			return false;
		}
		if ($('#teamDivision').val().trim() == '') {
			displayError("<strong>Validation Error:</strong> Team Divison is required.");
			return false;
		}
		return true;
	}

  
  </script>
    <style>
  	.borderless td {
  			padding-top: 1em;
			padding-right: 2em;
  			border: none;
  	}
	.red {
		color: red;
	}
  
  
  </style>
  </head>
  
  <body>
  <?php include_once 'navbar.php'; ?>
  
  	<form action="controller.php" method="GET">
     <div class="container">
     
      <div id="errors" class="alert alert-danger" role="alert" style="display: none;"></div>
      <div id="messages" class="alert alert-success" role="alert" style="display: none;"></div>
     
     <h1>Edit Team</h1>
	 <hr>
	<table width="100%" class="borderless">
	<tr>
	<td width="20%"><label for="eventName">Team Name:<span class="red">*</span></label></td>
	<td width="30%">
	<input type="text" size="40" class="form-control" name="teamName" id="teamName" value="<?php echo $_SESSION["teamName"];?>">
	</td>
	<td width="20%"><label for="eventName">Team City: </label></td>
	<td width="30%">
	<input type="text" size="40" class="form-control" name="teamCity" id="teamCity" value="<?php echo $_SESSION["teamCity"];?>">
	</td>
	</tr>
	<tr>
	<td><label for="eventName">Team Phone Number: </label></td>
	<td>
	<input type="text" size="40" class="form-control" name="teamPhone" id="teamPhone" value="<?php echo $_SESSION["teamPhone"];?>">
	</td>
	<td><label for="eventName">Team Email Address: </label></td>
	<td>
	<input type="text" size="40" class="form-control" name="teamEmail" id="teamEmail" value="<?php echo $_SESSION["teamEmail"];?>">
	</td>
	</tr>
	<tr>
	<td><label for="eventName">Division:<span class="red">*</span></label></td>
	<td>
	<select class="form-control" name="teamDivision" id="teamDivision" >
			<option value=""></option>
			<option value="A" <?php if($_SESSION["teamDivision"] == 'A'){echo("selected");}?>>A</option>
			<option value="B" <?php if($_SESSION["teamDivision"] == 'B'){echo("selected");}?>>B</option>
			<option value="C" <?php if($_SESSION["teamDivision"] == 'C'){echo("selected");}?>>C</option>
	</select>
	</td>
	<td></td>
	<td></td>
	</tr>
	
	<tr>
	<td><label>Team Description: </label></td>
	<td></td>
	</tr>
	<tr>
		<td colspan="4">
			<textarea class="form-control"  name="teamDescription" id="teamDescription" spellcheck="true" rows="5" cols="100"><?php echo $_SESSION["teamDescription"];?></textarea>
		</td>
	</tr>
	</table>
	
	<hr>

     <button type="submit" class="btn btn-xs btn-danger" name="saveTeam" onclick="return validate();" value="<?php echo $_SESSION["teamId"];?>">Save</button>
 	 <button type="submit" class="btn btn-xs btn-primary" name="cancelTeam" value="0">Cancel</button>


      <hr>
	<?php include_once 'footer.php'; ?>

    </div><!--/.container-->
    </form>
      
      
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.3.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    
    <?php 
    	if ($_SESSION['saveTeamError'] != null and $_SESSION['saveTeamError'] == '1') { ?>
    	<script type="text/javascript">displayError("<strong>Cannot Add Team:</strong> Team has already been added.");</script>
   	<?php $_SESSION['saveTeamError'] = null; } ?>
    
  </body>
</html>