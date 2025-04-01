<?php
include('categorias.php');
include('links.php');

// Validação de upload
if (!isset($_FILES['files']) || $_FILES['files']['error'][0] !== UPLOAD_ERR_OK) {
    die("Erro ao enviar arquivos.");
}

foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
    $file_tmp = $_FILES['files']['tmp_name'][$key];
    $data = file_get_contents($file_tmp);

    // Verificar formato do arquivo
    if (strpos($data, '#EXTM3U') === false) {
        die("O arquivo não está no formato .m3u válido.");
    }

    // Processar o arquivo
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
}

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