
<html>
	<head>
		<title>A Forms Menu</title>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
		<script type="text/javascript" src="styles/jquery-1.6.1.min.js"></script>
		<link rel='stylesheet' type='text/css' href='styles/style.css' />
		<script type='text/javascript'>
			$(document).ready(function(){
			    $('ul#menu-bar li').click(
			    function(e)
			    {
			   		$('ul#menu-bar li').removeClass('active');
					$(e.currentTarget).addClass('active');
				}
				);
			});
		</script>
	</head>
	<body>
		<div id='container'>
			<div id='head' class='center'>
				<img alt="" src="styles/images/PCB banner.jpg" style='width:898px; height:150px'>
				<ul id="menu-bar">
					<li class='active'><a href="A Forms/a1form.php" target="body">A1 Form</a></li>
					<li><a href="A Forms/a2form.php" target="body">A2 Form</a></li>
					<li><a href="A Forms/a3form.php" target="body">A3 Form</a></li>
					<li><a href="A Forms/a4form.php" target="body">A4 Form</a></li>
					<li><a href="A Forms/a5form.php" target="body">A5 Form</a></li>
					<li><a href="phmember.php" target="body">Philhealth Membership</a></li>
				</ul>
			</div>
		</div>
	</body>
</html>

