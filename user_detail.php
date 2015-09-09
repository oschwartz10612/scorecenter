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
     
     <h1>Edit User</h1>
	 <hr>
	<table width="100%" class="borderless">
	<tr>
	<td width="20%"><label for="userFirstLastName">User Name: </label></td>
	<td width="30%"><?php echo $_SESSION["userFirstLastName"]; ?></td>
	<td width="20%"><label for="userName">Email: </label></td>
	<td width="30%"><?php echo $_SESSION["userName"]; ?></td>
	</tr>
	<tr>
	<td><label for="phoneNumber">Phone Number: </label></td>
	<td><?php echo $_SESSION["userPhoneNumber"]; ?></td>
	<td></td>
	<td></td>
	</tr>
	<tr>
	<td><label for="userRoleCode">User Role: </label></td>
	<td>
			<select class="form-control" name="userRoleCode" id="userRoleCode">
			<option value="ADMIN" <?php if ($_SESSION["userRoleCode"] == 'ADMIN') echo 'selected'; ?> >Admin</option>
			<option value="VERIFIER" <?php if ($_SESSION["userRoleCode"] == 'VERIFIER') echo 'selected'; ?>>Verifier</option>
			<option value="SUPERVISOR" <?php if ($_SESSION["userRoleCode"] == 'SUPERVISOR') echo 'selected'; ?>>Supervisor</option>
			</select>
	</td>
	<td><label for="userActiveFlag">Active User: </label></td>
	<td>
			<select class="form-control" name="userActiveFlag" id="userActiveFlag">
			<option value="0" <?php if ($_SESSION["userActiveFlag"] == 0) echo 'selected'; ?> >No</option>
			<option value="1" <?php if ($_SESSION["userActiveFlag"] == 1) echo 'selected'; ?>>Yes</option>
			</select>
	</td>
	</tr>

	</table>
	
	<hr>

     <button type="submit" class="btn btn-xs btn-danger" name="saveUser" value="<?php echo $_SESSION["userId"];?>">Save</button>
 	 <button type="submit" class="btn btn-xs btn-primary" name="cancelUser" value="0">Cancel</button>


      <hr>
	<?php include_once 'footer.php'; ?>

    </div><!--/.container-->
    </form>
      
      
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.3.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    
  </body>
</html>