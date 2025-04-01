<?php
session_start();
require_once('conexao.php');

$id = rand(0, 9999);
$nome = $_POST['nome'];
$senha = md5(sha1($_POST['senha'] . "iptv"));
$login = $_POST['login'];
$admin = "0";
$vendedor = "0";
$estado = "1";
$conectado = "1";
$credito = "0";
$master = "0";
$acesso = substr(md5(time()) ,0);
$criador = $_SESSION['id_usuario'];
$data = date('Y-m-d', strtotime('+31 days'));
$dia = "31";
$uso = "0";
$uso_dia = "0";
$lista = $_POST['lista'];
$password = $_POST['senha'];

$result = "INSERT INTO usuario(id_usuario, nome_usuario, senha_usuario, login_usuario, admin, vendedor, estado_usuario, conectado, credito, master, acesso, id_criador, data, dia, uso, uso_dia) VALUES ('$id','$nome','$senha','$login','$admin','$vendedor','$estado','$conectado','$credito','$master','$acesso','$criador','$data','$dia','$uso','$uso_dia')";
$usuarios = mysqli_query($conexao, $result);

$result = "INSERT INTO lista_usuario(id_lista, id_usuario) VALUES ('$lista','$id')";
$listas = mysqli_query($conexao, $result);

$result = "INSERT INTO passwords(senha, id_usuario) VALUES ('$password','$id')";
$passwords = mysqli_query($conexao, $result);
?>