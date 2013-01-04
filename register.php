<?php include_once('./header.php');?>
<script type="text/javascript">
function comparePasswords()
{
	var pass1 = document.getElementById('password').value;
	var pass2 = document.getElementById('passconfirm').value;

	if(pass1!=pass2)
	{
		//alert("Passwords didn't match!");
		document.getElementById('passmessage').innerHTML = "Passwords don't match!";
	}
	else	//passwords match; if formerly they didn't, then remove the error message
	{
		document.getElementById('passmessage').innerHTML = "";
	}
}

function checkName(username)
{
	var xmlhttp;    
	if (username=="") //no username entered yet
	{
		document.getElementById("usernamemessage").innerHTML="";
		return;
	}

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("usernamemessage").innerHTML=xmlhttp.responseText;
		}
	}

	xmlhttp.open("GET","checkUsername.php?username="+username,true); //send
	xmlhttp.send();
}

function validate()
{
	//check all the elements of the form to make sure they are correctly filled out (except for email; it does that automatically)
	//if they are filled out, enable the Submit button

	username = document.getElementById("username").value;
	displayname = document.getElementById("displayname").value;
	password = document.getElementById("password").value;
	passconfirm = document.getElementById("passconfirm").value;
	joincode = document.getElementById("joincode").value;
	tos = document.getElementById("tos").value;
	submit = document.getElementById("submit");

	if((username!="")||(displayname!="")||(password!="")||(passconfirm!="")||(joincode!="")||(tos=="Accept"))
	{
		sumbit.disabled="";
	}
}
</script>
<?php
if (isset($_POST["joincode"])) //user has attempted to register
{
	$username = mysql_real_escape_string($_POST["username"]);
	$displayname = mysql_real_escape_string($_POST["displayname"]);
	$password = mysql_real_escape_string($_POST["password"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$joincode = mysql_real_escape_string($_POST["joincode"]);
	$tos = mysql_real_escape_string($_POST["tos"]);

	if(($tos!="Accept")) //if the ToS is not checked
	{echo "<div id='warning'>You must accept the Terms of Service to use this site.</div>";}

	if((!$username)||(!$displayname)||(!$password)||(!$email)) //if one of the fields is blank
	{echo "<div id='warning'>Error! Make sure all fields are filled in!</div>";}

	$SQL = "SELECT code FROM joincodes WHERE code='$joincode'"; //check the database to see if the join code exists

	$exists = mysql_query($SQL);
	$exists = mysql_num_rows($exists);

	if($exists>0) //the joincode was found in the database
	{
		$passencrypt = md5("$password"."$username");
		$query = "INSERT INTO users (username,displayname,password,rank,email) VALUES ('$username','$displayname','$passencrypt', '9', '$email')";
		$insert = (mysql_query($query));
		if(!$insert) //if inserting the new user fails for some reason
		{
			echo "<div id='warning'>I'm sorry, we were unable to create your account. There has been an error: ".mysql_error()."</div>";
		}
		else //inserting the new user was successful!
		{
			echo "<div id='alert'>$displayname, welcome to Dellusionary!</div>";
			$SQL = "DELETE FROM joincodes WHERE code='$joincode' LIMIT 1"; //Now, remove the join code so no one else can use it
			mysql_query($SQL);
		}
	}
	else //the joincode was not found in the databse (or some error occured)
	{
		echo '<div id=\'warning\'>Error: this join code doesn\'t exist! '.mysql_error().'</div>'; //print error message
	}
}

else //user is registering
{
?>
<h1 class='pagetitle'>Register an Account</h1>
<div id="register" style="position:relative; margin-left:10%; margin-right:10%;">
<form action="register.php" method="post">
You should have received a unique, 5-digit code from Tala. Please enter it below!
<br />
Join Code: <input type="text" name="joincode" id="joincode" />
<br /><br />
Now, please enter your desired account information:
<br />
<div style="float:left; line-height:150%;">
Username:<br />
Display Name:<br />
Password:<br />
Confirm Password:<br />
Email:<br />
<br />
</div>
<div id="fields"">
<input type="text" name="username" id="username" onblur="checkName(this.value)"/> <span id="usernamemessage"></span><br />
<input type="text" name="displayname" id="displayname" /><br />
<input type="password" name="password" id="password" /><br />
<input type="password" name="passconfirm" id="passconfirm" onblur="comparePasswords()" /> <span id="passmessage"></span><br />
<input type="email" name="email" /><br />
</div>
<br />
<input type="checkbox" name="tos" id="tos" value="Accept" onclick="validate()" /> I have read and understood the <a href="tos">Terms of Service</a>
<div style="width:50%;"><input type="submit" id="sumbit" value="Submit" disabled="disabled" style="float:right;"/></div>
<br /><br />
</form>
</div>

<?php
}
include_once('./footer.php'); ?>
