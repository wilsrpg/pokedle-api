<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
date_default_timezone_set('America/Sao_Paulo');
if(session_status() === 1) {
  session_set_cookie_params(["SameSite" => "Strict"]);
  session_start();
}

require 'vendor/autoload.php';

$metodo = $_SERVER['REQUEST_METHOD'];
if ($metodo != 'GET' && $metodo != 'POST') {
  http_response_code(405);
  echo json_encode(['erro' => 'Método não suportado.']);
  exit;
}
if ($metodo == 'POST' && empty($_POST)) {
  http_response_code(405);
  echo json_encode(['erro' => 'POST vazio.']);
  exit;
}

$caminho = '';
if(empty($_GET['caminho'])) {
  http_response_code(404);
  echo json_encode(['erro' => 'Rota não encontrada. Verifique se o endereço corresponde ao padrão domínio/versão/rota.']);
  exit;
}

$caminho = $_GET['caminho'];
$subdir = explode('/', $caminho);
//$query_params = $_GET;
//array_shift($query_params);
$query_params = $_POST;

if (isset($subdir[0]))
  $versao = $subdir[0];
if (isset($subdir[1]))
  $acao = $subdir[1];
if (isset($subdir[2]))
  $param = $subdir[2];

if (empty($versao) || empty($acao)) {
  http_response_code(404);
  echo json_encode(['erro' => 'Rota não encontrada. Verifique se o endereço corresponde ao padrão domínio/versão/rota.']);
  exit;
}

include_once 'api/poke.php';
