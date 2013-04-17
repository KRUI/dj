<?php
//Login
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
<link rel="stylesheet" type="text/css" href="assets/css/login.css" />
<link rel="stylesheet" type="text/css" href="assets/js/jquery-ui-aristo/css/aristo/jquery-ui-1.8rc3.custom.css" />
<script type="text/javascript" src="assets/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.tipsy.js"></script>
<script type="text/javascript" src="assets/js/jquery.placeholder.js"></script>
<title>KRUI DJ</title>
</head>
<body>
<div id="login">
<div id="logo"><img alt="KRUI DJ" src="assets/images/app_logo.png" /><br /><noscript>Error: Please verify your browser has JavaScript enabled before logging in.</noscript></div>
  <div id="form">
  <form id="auth" name="auth" method="post" action="<?php echo $PHP_SELF; ?>">
    <p>
      <input name="username" type="text" id="username" placeholder="Username"/>
    </p>
    <p>
      <input name="password" type="password" id="password" placeholder="Password"/>
    </p>
	<p>Select a studio:
		<select id="select_studio" name="studio">
			<option value="main">KRUI 89.7FM</option>
			<option value="lab">The Lab</option>
		</select>
	</p>
	<br/>
    <a id="login_button">Login</a>&nbsp;<a id="request_button">Request Access</a>
  </form>
  </div>
  <div id="request">
  	<form id="request_form">
	<p>Need a new staff account or having trouble getting in? Fill out the form below.</p><br/>
		<p><input name="firstname" type="text" id="request_firstname" placeholder="First name"/>
			<input name="lastname" type="text" id="request_lastname" placeholder="Last name"/>
			<input name="email" type="text" id="request_email" placeholder="Email"><br/>
			Semester:
			<select id="request_semester" name="semester">
				<option value="Fall">Fall</option>
				<option value="Spring">Spring</option>
				<option value="Summer">Summer</option>
			</select>
			<select id="request_year" name="year">
				<?php $year = date('o', time()); ?>
				<option value="<?php echo($year);?>"><?php echo($year);?></option>
				<option value="<?php echo($year + 1);?>"><?php echo($year + 1);?></option>
			</select>
		</p>
		<div id="recaptcha"></div>
	</form>
  </div>
</div>
<script type="text/javascript" src="assets/js/login.js"></script>
<script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
</body>
</html>
