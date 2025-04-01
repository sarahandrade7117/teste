<?php
require_once("categorias.php");
require_once("links.php");

// Função para obter conteúdo da URL
function get_url($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    $page = curl_exec($ch);
    curl_close($ch);
    return $page;
}

// Validação da URL
$url = $_POST['url'] ?? '';
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    die("URL inválida.");
}

// Obter e processar o conteúdo
$data = get_url($url);
if (strpos($data, '#EXTM3U') === false || strpos($data, '#EXTINF') === false) {
    die("O conteúdo não está no formato .m3u válido.");
}

// Processar o conteúdo
$data = str_replace("'", '"', $data);
$data = explode('#EXTINF:', $data);
$groups = [];
$channels = [];

foreach ($data as $item) {
    $groupName = extractGroupName($item);
    if ($groupName && !array_key_exists($groupName, $groups)) {
        $groups[$groupName] = processCategory($groupName);
    }

    $channelName = extractChannelName($item);
    if ($channelName && !array_key_exists($channelName, $channels)) {
        $channels[$channelName] = true;
        processChannel($channelName, $item, $groups[$groupName]);
    }
}

// Funções auxiliares
function extractGroupName($item) {
    preg_match('/title="([^"]+)"/', $item, $matches);
    return trim($matches[1] ?? "");
}

function extractChannelName($item) {
    preg_match('/,(.*?)\n/', $item, $matches);
    return trim($matches[1] ?? "");
}

function processCategory($groupName) {
    $category = obterCategoria(0, $groupName);
    if (empty($category)) {
        adicionarCategoria($groupName);
        $category = obterCategoria(0, $groupName);
    }
    return $category;
}

function processChannel($channelName, $item, $category) {
    $link = extractLink($item);
    $image_url = extractImageUrl($item);
    adicionarlink($channelName, $link, $category[0]['id'], $image_url);
}

function extractLink($item) {
    preg_match('/http[^\s]+/', $item, $matches);
    return trim($matches[0] ?? "");
}

function extractImageUrl($item) {
    preg_match('/logo="([^"]+)"/', $item, $matches);
    return trim($matches[1] ?? "");
}
?>