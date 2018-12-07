<?php include("inc/header.php"); ?>


	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Login App</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
          </ul>
		  		<ul class="nav navbar-nav navbar-right">
            <li ><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<div class="container">


	<div class="jumbotron">
		<h1 class="text-center">
		<?php
			if (logged_in()){
				
				echo "
					<h1>Wellcome ".$_SESSION['username']."</h1>
				";
			}else{
				redirect("index.php");
			}
		?>
		</h1>
	</div>




  <?php include("chatbot_template.php") ?>
</div>




	
</body>
</html>