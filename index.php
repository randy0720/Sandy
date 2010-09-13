<?php
    //Start session
    session_start();

    //=== Include configuration details
    require_once('classes/dbio.class.php');
    require_once('classes/lang.class.php');
    require_once('classes/security.class.php');
    require_once('config.php');

        // Not only will array_merge and array_keys give a warning if
	// a parameter is not an array, array_merge will actually fail.
	// So we check if _SESSION has been initialized.
	if (!isset($_SESSION) || !is_array($_SESSION)) {
		$_SESSION = array();
	}
	$_SESSION["TopBackgroundColor"] = "navy";
   	$_SESSION["LocID"] = 1;
	$_SESSION["LocName"] = "Location Name Here, Inc.";
	$_SESSION["LangCode"] = "EN";
	$_SESSION["gblSkin"] = "";
	$_SESSION["LoggedinUser"] = "Welcome Randy - Randal Martin";
	$_SESSION["Username"] = "randy0720";
	$_SESSION["PersonName"] = "Randal Martin";
	$_SESSION["UserID"] = 2;
		
    $db = new sql_dbio();
	$sc = new Security();

	$_SESSION["ErrMsgs"] = "Make selection above.";
	
    //=========================================================================
    //=== Setup/Validate Globals
    //=========================================================================
//	$db->WriteDebugOutput("index","At the beginning of module");
//	$db->WriteDebugOutput("index_DB_HOST",DB_HOST);
//	$db->WriteDebugOutput("index_DB_USER",DB_USER);
//	$db->WriteDebugOutput("index_DB_PASSWORD",DB_PASSWORD);
//	$db->WriteDebugOutput("index_DB_DATABASE",DB_DATABASE);
    if (strlen($_SESSION["gblSkin"]) == 0) {
    	$_SESSION["gblSkin"] = gblSkin;
    }
    //=== Connect to current server and database
	$sqlResult = $db->sql_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	if (!$sqlResult) {
		header('Location: DBError.php?Err='.$sqlResult);
		exit();
    }

	//=========================================================================
	//=== Build the Language Lookup table
	//=========================================================================
    //	$_SESSION["LangTbl"] = Array;
	

	
	//=================================================================================================
	//=== Check to see if the SEGR table has been updated since last Location date.
	//=== Read the LOMA table and check the LastSEGRUpdateDate.  If it is less than today, then
	//=== Perform logic to UPDATE the SEGR table from the FRMA table.
	//=================================================================================================

	$theQuery = "select * from LOMA where ID = ".$_SESSION["LocID"];
//	$db->WriteDebugOutput("index_LOMA_Read_theQuery",$theQuery);
	
	$sqlResult = $db->sql_query($theQuery);
	if (!$sqlResult) {
//		$db->WriteDebugOutput("index_LOMA_Read_theQuery","Severe error processing query!");
		$_SESSION["ErrMsgs"] = "Severe error processing query.  See error file.";
	} else {
		//=============================================================================================
		//=== Fetch the row from the query results
		//=============================================================================================
		$row = $db->sql_fetchrow($sqlResult);
//		$db->WriteDebugOutput("index_LOMA_Read_LastSEGRUpdateDate",$row["LastSEGRUpdateDate"]);
//		$db->WriteDebugOutput("index_LOMA_Read_date", date("Y-m-d"));
		if ($row["LastSEGRUpdateDate"] < date("Y-m-d")) {
			$sc->UpdateSEGRData();
		}

//		$sc->LoadSctyData($row["SECLID"]);
	}





	//=================================================================================================
	//=== Load the Security Data into a SESSION variable
	//=================================================================================================
	$theQuery = "select * from SEMA where ID = ".$_SESSION["UserID"];
//	$db->WriteDebugOutput("index_LoadSecurity_theQuery",$theQuery);
	
	$sqlResult = $db->sql_query($theQuery);
	if (!$sqlResult) {
//		$db->WriteDebugOutput("index_LoadSecurity_theQuery","Severe error processing query!");
		$_SESSION["ErrMsgs"] = "Severe error processing query.  See error file.";
	} else {
		//=============================================================================================
		//=== Fetch the row from the query results
		//=============================================================================================
		$row = $db->sql_fetchrow($sqlResult);
		$sc->LoadSctyData($row["SECLID"]);
	}



    
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Index Page for Sandy Creek</title>
<script type="text/javascript">
	function openLoginPage() {
		var xType = "";
		var xSize = 0;
		var ySize = 0;
		if(typeof(window.outerWidth)=='number') {
    		//IE
    		xType="FF";
    		xSize=window.outerWidth;
    		ySize=window.outerHeight;
  		} else if(typeof(window.innerWidth)=='number') {
    		//IE
    		xType="IE";
    		xSize=window.innerWidth;
    		ySize=window.innerHeight;
  		} else if(document.documentElement &&
      		(document.documentElement.clientWidth||document.documentElement.clientHeight)) {
    		//IE 6 xhtml
    		xType="IE6";
    		xSize=document.documentElement.clientWidth;
    		ySize=document.documentElement.clientHeight;
  		} else if(document.body&&(document.body.clientWidth||document.body.clientHeight)) {
    		//IE 4 5 or 6 standard
    		xType="IE456";
    		xSize=document.body.clientWidth;
    		ySize=document.body.clientHeight;
  		} else {
    		// unknown
    		xType="UNK";
    		xSize=999;
    		ySize=999;  		
  		}
		var theURL = "Login.php";
		var theLOCATION = "_blank";
		var theWIDTH = xSize;
		var theHEIGHT = ySize;
		var theOPTIONS = "width=" + theWIDTH + ", height=" + theHEIGHT + ", " + "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no";	

		window.open(theURL,theLOCATION,theOPTIONS);		
	}
	
	function getPageSizes() {
		var xType = "";
		var xSize = 0;
		var ySize = 0;
		if(typeof(window.outerWidth)=='number') {
    		//IE
    		xType="FF";
    		xSize=window.outerWidth;
    		ySize=window.outerHeight;
  		} else if(typeof(window.innerWidth)=='number') {
    		//IE
    		xType="IE";
    		xSize=window.innerWidth;
    		ySize=window.innerHeight;
  		} else if(document.documentElement &&
      		(document.documentElement.clientWidth||document.documentElement.clientHeight)) {
    		//IE 6 xhtml
    		xType="IE6";
    		xSize=document.documentElement.clientWidth;
    		ySize=document.documentElement.clientHeight;
  		} else if(document.body&&(document.body.clientWidth||document.body.clientHeight)) {
    		//IE 4 5 or 6 standard
    		xType="IE456";
    		xSize=document.body.clientWidth;
    		ySize=document.body.clientHeight;
  		} else {
    		// unknown
    		xType="UNK";
    		xSize=999;
    		ySize=999;  		
  		}
	}
</script>

</head>
<body onload="getPageSizes()">
<p><a href="Dashboard.php">Dashboard</a></p>
<p><a href="NoahCS.php?frm=0301">Settings Account Types - NoahCS.php?frm=0301</a></p>
<p><a href="LO1000.php?TblType=LOMA">Locations - LO1000</a></p>
<p><a href="SE1000.php?TblType=SEMA">User Maintenance</a></p>
<p>&nbsp;</p>
<p><a href="javascript: openLoginPage();">Login to NOAH</a></p>
<p>&nbsp;</p>
<p><?php echo $_SESSION["ErrMsgs"] ?></p>
<p>&nbsp;</p>

<ul>
	<li ><a href="Dashboard.php">Dashboard</a></li>
	<li ><a href="Settings.php">Settings</a></li>
	<li ><a href="Locations.php">Locations</a></li>
	<li ><a href="Login.php">Login to NOAH</a></li>
	<li ><a href="" onClick="window.open('http://localhost/Sandy/Login.php', '', 'fullscreen=yes, scrollbars=auto');">Login to NOAH using Full Screen</a></li>


</ul>

</body>
</html>