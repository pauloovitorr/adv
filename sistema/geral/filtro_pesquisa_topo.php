<?php

include_once('../../scripts.php');

// Função que adiciona o link do modelo no "visualizar site"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['valor']) && $_POST['acao'] === 'pesquisar_dados') {

    $pesquisa = $conexao->escape_string($_POST['valor']);

    $dados = [];


    $sql_pessoas = "
        SELECT 
            p.tk,
            p.nome,
            p.tipo_pessoa,
            p.tipo_parte,
            'pessoa' AS tipo_resultado
        FROM pessoas p
        WHERE p.nome LIKE '%$pesquisa%'
        LIMIT 20
    ";

    $res_pessoas = $conexao->query($sql_pessoas);

    if ($res_pessoas && $res_pessoas->num_rows > 0) {
        while ($row = $res_pessoas->fetch_assoc()) {
            $dados[] = $row;
        }
    }

    $sql_processos = "
        SELECT
            pr.tk,
            pr.referencia,
            pr.tipo_acao,
            'processo' AS tipo_resultado
        FROM processo pr
        WHERE pr.referencia LIKE '%$pesquisa%'
        LIMIT 20
    ";

    $res_processos = $conexao->query($sql_processos);

    if ($res_processos && $res_processos->num_rows > 0) {
        while ($row = $res_processos->fetch_assoc()) {
            $dados[] = $row;
        }
    }


    if (!empty($dados)) {
        $res = [
            'status' => 'success',
            'message' => 'Resultados encontrados',
            'total' => count($dados),
            'dados' => $dados
        ];
    } else {
        $res = [
            'status' => 'error',
            'message' => 'Nenhum resultado encontrado'
        ];
    }

    $conexao->close();
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    exit;
}
?>