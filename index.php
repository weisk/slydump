<?php include("inc/client.php") ?>
<!DOCTYPE html>
<html>


	<head>
		<meta charset="utf-8">
		<title>SlyDump!</title>
		<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
		<meta name="description" content="Series,Online,Peliculas,Documentales,Streaming,Download,Media,Movies,Shows">
		<meta name="author" content="FML">
		<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen"></link>
		<link href="lib/bootstrap/css/bootstrap-responsive.css" rel="stylesheet"></link>
		<link href="lib/bootstrap/css/bootstrap-modal.css" rel="stylesheet"></link>
		<link href="css/custombs.css" rel="stylesheet"></link>
	</head>

	<body onload="initPage()">

		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">

					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>

					<a class="brand" href="">SlyDump!</a>

					<div class="nav-collapse collapse">
						<ul class="nav">
							<li class="active"><a>Series</a></li><li><a>Peliculas</a></li><li><a>Documentales</a></li>
						</ul>
						<ul class="nav pull-right">
							<?php if(isset($_SESSION['logged'])){ ?>
								<li class="dropdown" style="float:right;">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['user']; ?>&nbsp;<b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li class="nav-header">Acciones</li>
										<li><a>Mi cuenta</a></li>
										<li class="divider"></li>
										<li>
											<a onclick="$('#logout').click();">Salir</a>
											<form method="post" class="hidden"><button type="submit" name="logout" id="logout"></button></form>
										</li>
									</ul>
								</li>
							<?php } else { ?>
								<form method="post" class="navbar-form pull-right">
									<input class="span2" type="text" placeholder="Email" name="user">
									<input class="span2" type="password" placeholder="Password" name="pass">
									<button type="submit" class="btn" name="login">Sign in</button>
									<input type="hidden" id="serie-ids" name="serie-ids" value=""></input>
									<input type="hidden" id="serie-poster" name="serie-poster" value=""></input>
									<input type="hidden" id="serie-title" name="serie-title" value=""></input>								
								</form>
							<?php } ?>
						</ul>
					</div>

				</div>
			</div>
		</div>

		<div class="container">

			<div id="topnav" class="navbar"><div class="navbar-inner">
				<ul id="subnav" class="nav">
					<li class="level active" pos="1"><a onclick="slide(1)">Inicio</a></li>
					<li class="divider-vertical"></li>
					<li class="level active hidden" pos="2"><a onclick="slide(2)"></a></li>
					<li class="divider-vertical hidden"></li>
				</ul>
				<ul class="nav pull-right">
					<li class="divider-vertical"></li>
					<form onsubmit="this.children[0].children[1].click();return false;" class="navbar-form pull-right">
						<div class="input-append">
							<input class="span2" id="inpserie" type="text" placeholder="Buscar serie">
							<span onclick="search(1,$('#inpserie').val())" class="add-on" style="cursor:pointer;"><i class="icon-search"></i></span>
						</div>
					</form>
				</ul>
			</div></div>

			<div id="panel">

				<div id="box1" class="box">
					<div class="hero-unit"><h3>Bienvenido a SlyDump!</h3></div>
    				<div id="favshows"></div>
				</div>

				<div id="box2" class="box">
					<table class="table table-striped table-bordered table-hover">
						<thead><tr><th>&nbsp;</th><th>Titulo</th><th>Temporadas</th><th>Episodios</th></tr></thead>
						<tbody id="tbody_1"></tbody>
					</table>
				</div>

				<div id="box3" class="box" >					
					<div id="descr"><div class="row">
						<div class="span2">
							<img id="poster" src=""></img>
						</div>
						<div class="span9"></div>
					</div></div>
					<div id="temp" class="navbar">
						<div class="navbar-inner">
							<a class="brand">Temporada</a>
							<ul class="nav"></ul>
						</div>
					</div>
					<div class="row">
						<div id="episodes" class="span6"></div>
						<div id="links" class="span6">							
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#streaming" data-toggle="tab">Streaming</a></li>
									<li><a href="#download" data-toggle="tab">Download</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="streaming"><ul class="nav nav-list"></ul></div>
									<div class="tab-pane" id="download"><ul class="nav nav-list"></ul></div>						
								</div>
							</div>								
						</div>
					</div>				
				</div>

			</div>
		</div>
		<div class="container">
			<hr>
			<footer><p>&copy; SlyDump! 2012&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;weisK<i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;powered by series.ly</i></p></footer>
		</div>


		<script type="text/javascript" src="src/common.js"></script>
		<script type="text/javascript" src="src/js.js"></script>
		<script type="text/javascript" src="lib/jquery-1.8.2.min.js"></script>
		<script type="text/javascript" src="lib/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript" src="lib/bootstrap/js/bootstrap-modalmanager.js"></script>
		<script type="text/javascript" src="lib/bootstrap/js/bootstrap-modal.js"></script>
		<script type="text/javascript" src="lib/ga.js"></script>
		<script type="text/javascript">
			(function(){setTimeout(function(){
			<?php
				if($_POST['serie-ids']!="")	echo "loadSerie(".$_POST['serie-ids'].");";
				else 						echo "slide(1);";
			?>
			},300)})();
		</script>
	</body>
</html>