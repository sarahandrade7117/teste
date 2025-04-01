<?php
session_start();
require_once('controles/usuarios.php');
if (!checarUsuario()) {
?>
<!DOCTYPE html>

<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login | <?php echo $nome; ?></title>
    <link href="css/login.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<style>
#random{
  background-image: url('');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
}

a:link, a:visited {
  background-color: green;
  color: white;
  padding: 5px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
}

a:hover, a:active {
  background-color: #B22222;
}
</style>
<body>
<body onload="randombg()" id="random">
    <form style="background-color:white;border-radius: 15px;" id="login" class="form-signin">
        <img class="mx-auto d-block mb-4" src="<?php echo $img; ?>" alt="logo">
        <label for="inputEmail" class="sr-only">Usuário</label>
        <input name="usuario" type="text" id="inputEmail" class="form-control" placeholder="Usuário" required autofocus>
        <label for="inputPassword" class="sr-only">Senha</label>
        <input name="senha" type="password" id="inputPassword" class="form-control" placeholder="Senha" required>
        <button class="btn btn-lg btn-danger btn-block" type="submit">Entrar</button>
       <div class="separator" style="clear: both; text-align: center;">
</div>
<div class="separator" style="clear: both; text-align: center;">
</div>
<div style="text-align: center;">
<br />
&copy; <?php echo date("Y"); ?> <?php echo $copy; ?> -</span><br />
Desenvolvido por PJT</span></font></center>
</form>
<!-- Alerta Inicio -->
<div class="modal fade" id="alerta" tabindex="-1" role="dialog" aria-labelledby="Alerta" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="text-center">
        <h5 id="textoAlerta" class="h5"></h5> 
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Alerta Fim-->
</body>
<script>
function randombg(){
  var random= Math.floor(Math.random() * 2) + 0;
  var bigSize = ["url('img/fundo1.jpg')",
				 "url('img/fundo2.jpg')"];
  document.getElementById("random").style.backgroundImage=bigSize[random];
}
    $( "#login" ).submit(function( event ) {
        $.ajax({
            type: "POST",
            url: "controles/login.php",
            data: $("#login").serialize(),
            success: function(data) {
                location = "dashboard.php";
            },
            error: function(data) {
                $("#textoAlerta").text(data.responseText);
                $("#alerta").modal();
            }
        });
        event.preventDefault();
    });
</script>
</html>
<?php
} else {
    header("Location: dashboard.php");
    die();
}
?>