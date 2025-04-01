<?php
// DADOS DA HOSPEDAGEM
$endereco = "localhost"; // aqui você coloca host mysql
$usuario = "root"; // aqui você coloca nome de usuário mysql
$senha = ""; // aqui você coloca senha mysql
$banco = "painelpainel"; // aqui você coloca banco de dados mysql

// CONFIGURAÇÃO PAINEL
$nome = "TV Serve"; // aqui você coloca seu nome da empresa
$copy = "XtreamServe.ga"; // aqui você coloca seu copyright
$img = "img/logo.png"; // aqui você coloca seu logotipo na página de login
$img2 = "img/logo-min.png"; // aqui você coloca seu logotipo na página do painel

// HORA PAINEL
date_default_timezone_set('America/Sao_Paulo');

// DATA PAINEL
$t = date('d-m-Y');
$dayNum = strtolower(date("d",strtotime($t)));
$dayNum = intval($dayNum);

// CONEXÃO PAINEL
if (mysqli_connect($endereco, $usuario, $senha, $banco)) {
    $conexao = mysqli_connect($endereco, $usuario, $senha, $banco);
} else {
    header("Location: erro.php");
    die();
}

// ATUALIZAR DATA DE ACESSO
$q1 = 'UPDATE usuario SET uso = uso + 1, uso_dia = '.$dayNum.' WHERE uso_dia != '.$dayNum.'';
$q2 = 'UPDATE usuario SET estado_usuario = 0, uso = 0 WHERE dia = '.$dayNum.' and uso >= 1';
mysqli_query($conexao, $q1);	
mysqli_query($conexao, $q2);
?>