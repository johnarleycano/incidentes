<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Twitter DEVIMED S.A</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">        
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link href="css/estilo.css" rel="stylesheet" media="screen"> 
		<!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">
	</head>
	<body>
    <!--login modal-->
    <div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h1 class="text-center">DEVIMED S.A</h1>
                  <label  class="control-label">Publicación de Twitter</label>
              </div>
              <div class="modal-body">
                  <form action="login.php" method="post" class="form col-md-12 center-block">
                    <div class="form-group">
                      <!-- <input type="text" class="form-control input-lg" placeholder="Email"> -->
                      <input type="mail" class="form-control input-lg" name="login" id="login" placeholder="Usuario" required>
                    </div>
                    <div class="form-group">
                      <!-- <input type="password" class="form-control input-lg" placeholder="Password"> -->
                      <input type="password" class="form-control input-lg" name="clave" id="clave" placeholder="Clave" required>
                    </div>
                    <div class="form-group">
                      <!-- <button class="btn btn-primary btn-lg btn-block">Sign In</button> -->
                      <button type="submit" class="btn btn-success btn-lg btn-block">Ingresar</button>
                      <!-- <span class="pull-right"><a href="#">Register</a></span><span><a href="#">Need help?</a></span> -->
                    </div>
                  </form>
              </div>
              <div class="modal-footer">
                    <div class="col-md-12">
                    <!-- <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button> -->
          		      </div>	
              </div>
          </div>
        </div>
    </div>
    	<!-- script references -->
    		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    		<script src="js/bootstrap.min.js"></script>
	</body>
</html>