<?php
require "parametros.php";


$color = true;
$query = mssql_query(
    "SELECT r.*, t.*
    FROM acervo_geral ag, referencia r, tipo_obra t
    WHERE ag.cod_sit_acervo = '0'
    AND NOT EXISTS (SELECT * FROM acervos_incompletos ai
    WHERE ai.cod_acervo = ag.cod_acervo
    AND ai.cod_tipo_obra = ag.cod_tipo_obra
    AND ai.cod_empresa = ag.cod_empresa)
    AND ag.cod_acervo = r.cod_acervo
    AND ag.cod_empresa = r.cod_empresa
    AND t.cod_empresa = 18
    AND t.cod_tipo_obra = ag.cod_tipo_obra
    AND ag.cod_tipo_obra in(6,9)
    AND (r.ano_publicacao = '" . $ano . "' or '" . $ano . "'='')
    AND (r.cod_acervo = '" . $acervo . "' or '" . $acervo . "'='')
    -- AND r.ano_publicacao >= '2009' AND r.ano_publicacao <= '2009'
    -- AND r.cod_acervo=348186
    ORDER BY r.ano_publicacao DESC
    ",
    $db
);
$trabalhos = array();
while ($array1 = mssql_fetch_assoc($query)) {
    // print_r($array1);
    // die("teste");
    $t = array();
    $url_link = null;
    foreach ($array1 as $k => $v) {
        $t[$k] = utf8_encode(htmlentities($v, ENT_COMPAT, "ISO-8859-1"));
        // $t[$k] = utf8_encode(htmlentities($v ));
    }

    $link1 = mssql_query("spwper_busca_links856 18," . $t['cod_acervo'], $db);

    while ($reg_link = @mssql_fetch_array($link1)) {
        $url_link = $reg_link["descricao"];
        if (trim($url_link) == "") {
            $url_link = $reg_link["texto_descricao"];
        }
        $t['links'] = htmlentities($url_link);
        // $t['links'] = $url_link;
    }
    $trabalhos[] = $t;
}
header('Content-Type: application/json; charset=utf8');
echo json_encode($trabalhos);
