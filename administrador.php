<?php
session_start();
require_once("controles/usuarios.php");
require_once("controles/listas.php");
if (checarUsuario()) {
require_once("cabecalho.php");
$usuarios = listarUsuarios();
$listas = listarListas();
?>
<?php if($_SESSION['vendedor']) { ?>
<script>if(window==window.top) document.location="dashboard.php"</script>
<?php } ?>
<style id="checkme">
	.page-2content {
	    padding-left: 240px !important;
		padding-right: 240px !important;
	}
	</style>
        <div id="conteudo-painel" style="padding-left: 0px;" class="table-responsive container">
			<?php if ($usuarios) { ?>
                <div class="mb-5 form-group float-right">
                </div>
				<div class="mb-5 form-group float-left">
                    <input type="text" class="pesquisar form-control" placeholder="Pesquisar...">
                </div>
				<table class='table table-bordered table-hover'>
                    <caption>Administradores</caption>
				<thead class="thead-light">
					<tr align="center">
						<th class='nomecol' style="width: 15%" scope="col">Nome</th>
						<th class='nomecol' style="width: 15%" scope="col">Login</th>
						<th class='nomecol' style="width: 15%" scope="col">Senha</th>
						<th class='nomecol' style="width: 15%" scope="col">Criador</th>
                        <th class='nomecol' style="width: 15%" scope="col">Estado</th>
						<th class='nomecol' style="width: 9%" scope="col">Opções</th>
					</tr>
				</thead>
                <tbody id="conteudo">
					<?php foreach($usuarios as $usuario) { ?>
                    <?php if ($usuario['admin'] == 1) if ($usuario['vendedor'] == 0) { ?>
					<?php
                    $passwords = "SELECT * FROM passwords WHERE id_usuario = ".$usuario['id_usuario']."";
                    $resut = mysqli_query($conexao, $passwords);
                    while($password = mysqli_fetch_array($resut)){
					?>
					<tr>
							<td align="center"> <?=$usuario['nome_usuario']?> </td>
							<td align="center"> <?=$usuario['login_usuario']?> </td>
							<td align="center"> <?php echo $password['senha']; ?> </td>
							<td align="center"> <?php echo $usuario['criador'] ? $usuario['criador']['nome_usuario'] : 'Sistema'; ?> </td>
                            <td align="center"> <?php if ($usuario['estado_usuario'] == 1) {echo "Ativado";} else {echo "Desativado";} ?> </td>
							<td style="display: contents;">
								<div class="dropdown">
									<style>.no-zero { padding-top: 0px; padding-bottom: 0px; position: relative; }</style>
									<button class="btn" type="button" data-toggle="dropdown" aria-expanded="false" style="top: 3px; margin: 0 auto; position: relative; display: block">Opções<span class="fa fa-caret-down" style="left: 4px; position: relative" aria-hidden="true"></span></button>
									<style>.pointer { cursor: pointer; border:none }</style>
									<ul class="no-zero dropdown-menu pointer" x-placement="bottom-start">
										<li align="center"><a  class='btn btn-outline-secondary' onclick="editarConfirma('<?php echo $usuario['dia']; ?>', '<?=$usuario['id_usuario']?>','<?=$usuario['nome_usuario']?>','<?php echo $password['senha'];?>','<?=$usuario['login_usuario']?>','<?=$usuario['estado_usuario']?>','<?=$usuario['admin']?>', '<?=$usuario['vendedor']?>', [<?php foreach (listasUsuario($usuario['id_usuario']) as $lista) echo $lista['id_lista'] .',' ?> ])" style="width: 100%;display: block;padding: 5px;top: -10px;"><i class="fas fa-user-edit"></i> Editar</a></li>
										<li align="center"><a  class='btn btn-outline-secondary' onclick="removerConfirma('<?=$usuario['id_usuario']?>', '<?=$usuario['nome_usuario']; ?>')" style="width: 100%; display: block; padding: 5px"><i class="far fa-trash-alt"></i> Excluir</a></li>
									</ul>
								</div>
							</td>
						</tr>
					<?php } ?>
					<?php } ?>
					<?php } ?>
				</tbody>
			</table>
        <?php
        }
        ?>
		  <?php if($_SESSION['admin']) { ?>
             <div class="h3 mt-5 row align-items-center justify-content-center">
             <i onclick="$('#cadastro').modal()" class="btn btn-outline-info text-dark fas fa-plus"></i>
          <?php } ?>
            </div>
        </div>
    </div>
  </main>
<!-- page-content" -->
</div>
<?php if($_SESSION['admin']) { ?>
<!-- Cadastro Inicio -->
<div class="modal fade" id="cadastro" tabindex="-1" role="dialog" aria-labelledby="Cadastrar" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalLongoExemplo">Adicionar Administrador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="cadastro-form">
            <div class="container">
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" class="form-control" name="nome" placeholder="Nome" value="<?php $gerador = rand(8,8); $valor = substr(str_shuffle("abcdefghijlkmnopqrstuvxyzwABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $gerador); echo $valor; ?>">
                    <small class="form-text text-muted">Deixa gerar um nome automático se não quiser colocar</small>
                </div>
                <div class="form-group">
                    <label>Login:</label>
                    <input type="text" class="form-control" name="login" placeholder="Login" value="<?php echo rand(1,999999);?>">
                    <small class="form-text text-muted">Deixa gerar um login automático se não quiser colocar</small>
                </div>
                <div id="sC">
                    <div id="divSenhaC" class="form-group">
                    <label>Senha:</label>
                    <input type="password" class="form-control" name="senha" placeholder="Senha" value="<?php echo rand(1,999999);?>">
                    <small class="form-text text-muted">Deixa gerar uma senha automático se não quiser colocar</small>
                </div>
                </div>
                <div class="form-group">
                    <label>Nível:</label>
                    <div class="ml-0 row">
                        <select id="nivelC" class="selectpicker" title="Nível">
                            <?php if($_SESSION['admin']) { ?>
							<option value="admin">Administrador</option>
                            <option value="vendedor" hidden></option>
							<option value="cliente" hidden></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="display: none">
                    <label>Administrador:</label>
                    <div class="ml-0 row">
                        <select value="0" id="adminC" class="selectpicker" title="Administrador" name="admin" required>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="display: none">
                    <label>Vendedor:</label>
                    <div class="ml-0 row">
                        <select value="0" id="vendedorC" class="selectpicker" title="Vendedor" name="vendedor" required>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                </div>
                <button type="none" onclick="addForce()" class="btn btn-danger">Adicionar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Cadastro Fim-->
<?php } ?>
<?php if($_SESSION['admin']) { ?>
<!-- Remove Inicio -->
<div class="modal fade" id="remover" tabindex="-1" role="dialog" aria-labelledby="Cadastrar" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tem certeza?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="remover-conteudo" class="modal-body"></div>
    </div>
  </div>
</div>
<!-- Remove Fim-->
<?php } ?>
<?php if($_SESSION['admin']) { ?>
<!-- Edita Inicio -->
<div class="modal fade" id="editar" tabindex="-1" role="dialog" aria-labelledby="Cadastrar" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Administrador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editar-form">
            <div class="container">
                <input type="hidden" id="idE" name="id">
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" class="form-control" id="nomeE" name="nome" placeholder="Nome">
                </div>
                <div class="form-group">
                    <label>Login:</label>
                    <input type="text" class="form-control" id="loginE" name="login" placeholder="Login">
                </div>
                <div id="s">
                <div id="divSenha" class="form-group">
                    <label>Senha:</label>
                    <input type="password" class="form-control" id="SenhasE" name="senha" placeholder="Senha">
                    </div>
                </div>
               <div class="form-group">
                    <label>Estado:</label>
                    <div class="ml-0 row">
                        <select id="estadoE" class="selectpicker" title="Estado" name="estado">
                            <option value="1">Ativo</option>
                            <option value="0">Desativado</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Nível:</label>
                    <div class="ml-0 row">
                        <select id="nivelE" class="selectpicker" title="Nível">
                            <?php if($_SESSION['admin']) { ?>
							<option value="admin">Administrador</option>
                            <option value="vendedor" hidden></option>
							<option value="cliente" hidden></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="display: none">
                    <label>Administrador:</label>
                    <div class="ml-0 row">
                        <select id="adminE" class="selectpicker" title="Administrador" name="admin" required>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="display: none">
                    <label>Vendedor:</label>
                    <div class="ml-0 row">
                        <select id="vendedorE" class="selectpicker" title="Vendedor" name="vendedor" required>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                </div>
                <button type="none" onclick="forceEdit()" class="btn btn-danger">Salvar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Edita Fim-->
<?php } ?>
</body>
<script>
    var clone = $("#divSenha").clone();
    var cloneC = $("#divSenhaC").clone();
    //$("#divSenhaC").remove();

    $("#cadastro").on('hidden.bs.modal', function (e) {
        if ($( "#adminC" ).val() != 1) {
            //$("#sC").empty();//DO_NOTHING
        }
    });

    $( "#adminC" ).change(function() {
        if ($( "#adminC" ).val() == 1) {
            $("#sC").append(cloneC);
        } else {
            $("#sC").empty();
        }
    });

    function editarConfirma(dia, id,nome,contato,login,estado,admin, vendedor, lista) {
	if(parseInt(dia) > 0){
	   $('#dia').val(parseInt(dia));
	} else {
	   $('#dia').val('');
	}
        $('#idE').val(id);
        $('#nomeE').val(nome);
        $('#nivelE').val(parseInt(vendedor) == 1 ? 'vendedor' : (parseInt(admin) == 1) ? 'admin' : 'cliente');
        setTimeout(() => {
            $('#nivelE').val(parseInt(vendedor) == 1 ? 'vendedor' : (parseInt(admin) == 1) ? 'admin' : 'cliente');
            $('*[data-id=nivelE]').text(parseInt(vendedor) == 1 ? 'vendedor' : (parseInt(admin) == 1) ? 'admin' : 'cliente');
        }, 500);
        $('#SenhasE').val(contato);
        $('#loginE').val(login);
        $('#estadoE').val(estado);
        $('#estadoE').selectpicker('render');
        $('#adminE').val(admin);
        $('#adminE').selectpicker('render');
        if (parseInt(admin) == 0) {
	    console.log(parseInt(vendedor) !== 1);
	    if(parseInt(vendedor) !== 1){
		$('#exdiaE').show();
		$('#CreditoC').show();
		$('#ConectadoE').show();
	    } else {
		$('#exdiaE').hide();
		$('#CreditoE').hide();
		$('#ConectadoE').hide();
	    }
            //$('#divSenha').remove();
        } else if ($('#divSenha').length < 1) {
            $('#s').append(clone);
        }
        $('#listaE').val(lista);
        $('#listaE').selectpicker('render');
        $('#editar').modal();
    }

    function removerConfirma(id,nome) {
        $('#remover-conteudo').html('<div class="alert alert-danger" role="alert"><strong> Remover </strong>' + nome + '?</div><button onclick="remover(' + id + ')" type="submit" class="btn btn-danger float-right">Remover</button>');
        $('#remover').modal();
    }

	function remover(id){
        window.location.href = 'controles/remover-administrador.php?id_usuario=' + id;
    }

    var addForce = function(){
        $( "#cadastro-form" ).trigger('submit');
    };
    var added = false;
    $( "#cadastro-form" ).submit(function( event ) {
        if(!added){
            added = true;
            if($('#cadastro-form select[name="vendedor"]').val().trim() == ""){
                $('#cadastro-form select[name="vendedor"]').val(0);
            }
            if($('#cadastro-form select[name="admin"]').val().trim() == ""){
                $('#cadastro-form select[name="admin"]').val(0);
            }
            $.ajax({
                type: "POST",
                url: "controles/adicionar-administrador.php",
                data: $("#cadastro-form").serialize(),
                success: function(data) {
                    location.reload();
                },
                error: function(data) {
                  resultado(data.responseText);
                }
            });
        }
        event.preventDefault();
    });
    var eddited = false;
    var forceEdit = function(){
        $( "#editar-form" ).trigger('submit');
    }
    $( "#editar-form" ).submit(function( event ) {
        if(!eddited){
            eddited = true;
            if($('#editar-form select[name="vendedor"]').val().trim() == ""){
                $('#editar-form select[name="vendedor"]').val(0);
            }
            if($('#editar-form select[name="admin"]').val().trim() == ""){
                $('#editar-form select[name="admin"]').val(0);
            }
            $.ajax({
                type: "POST",
                url: "controles/editar-administrador.php",
                data: $("#editar-form").serialize(),
                success: function(data) {
                    location.reload();
                },
                error: function(data) {
                  resultado(data.responseText);
                }
            });
        }
        event.preventDefault();
    });
    <?php if($_SESSION['admin']) { ?>
	  $('#vendedorC').val(0);
      $('#adminC').val(1);
    <?php } ?>

    $('#nivelC').on('change', function(){
       var value = $(this).val();
       $('#sC').empty();
       if(value == 'vendedor'){
           $('#adminC').val(0);
           $('#vendedorC').val(1);
           $("#sC").append(cloneC);
	   $('#exdiaC').hide();
	   $('#ConectadoC').hide();
	   $('#CreditoC').val(1);
       } else if (value == 'admin'){
           $('#adminC').val(1);
           $('#vendedorC').val(0);
           $("#sC").append(cloneC);
	   $('#exdiaC').hide();
	   $('#ConectadoC').hide();
	   $('#CreditoC').val(0);
       } else {
           $('#adminC').val(0);
           $('#vendedorC').val(0);
	   $("#sC").append(cloneC);	   
	   $('#exdiaC').show();
	   $('#ConectadoC').show();
	   $('#CreditoC').val(0);
       }
    });

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
}
    function logar(id){
	if(!getCookie('original')){
	   setCookie('original', <?php echo $_SESSION['id_usuario'] ?>, 1);	
	}
	$.get('controles/forcar-login.php?id_usuario=' + id, function(){
	   window.location.reload();
        });
    }
    function verLogs(id){
        window.location.href = 'log.php?id_usuario=' + id;
    }
    $('#nivelE').on('change', function(){
       var value = $(this).val();
       if(value == 'vendedor'){
           $('#adminE').val(0);
           $('#vendedorE').val(1);
           $("#s").append(clone);
	   $('#exdiaE').hide();
	   $('#CreditoE').hide();
	   $('#ConectadoE').hide();
       } else if (value == 'admin') {
           $('#adminE').val(1);
           $('#vendedorE').val(0); 
           $("#s").append(clone);
	   $('#exdiaE').hide();
	   $('#CreditoE').hide();
	   $('#ConectadoE').hide();
       } else {
           $('#adminE').val(0);
           $('#vendedorE').val(0);
           $("#s").append(clone);
	   $('#exdiaE').show();
	   $('#CreditoE').show();
	   $('#ConectadoE').show();
       }
    });
    if(window.mobilecheck()){
	$('#checkme').remove();
    }
    $('.thead-light th:not(".nomecol")').remove();
</script>
<?php require_once("comum.php"); 
require_once("alerta.php");?>

</html>
<?php 
} else {
    header("Location: index.php");
    die();
}
?>