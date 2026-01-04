<?php
// Puxo os dados para alimentar os gráficos da home

// Gráfico 1 - Novos Processos
$sql_novos_processos = "SELECT 
    COUNT(*) AS total,
    DATE_FORMAT(dt_cadastro_processo, '%m-%Y') AS mes
        FROM processo
        WHERE dt_cadastro_processo >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        AND usuario_config_id_usuario_config = $id_user
        GROUP BY DATE_FORMAT(dt_cadastro_processo, '%Y-%m')
        ORDER BY mes;";

$novos_processos = $conexao->query($sql_novos_processos);

//  Array que recebe o resultado do SQL 
$array_processos = [];
while ($row = $novos_processos->fetch_assoc()) {
    $array_processos[] = $row;
}

//  Cria os últimos 12 meses com valor 0 
$meses = [];
for ($i = 11; $i >= 0; $i--) {
    $meses[date('m-Y', strtotime("-$i months"))] = 0;
}

//  Converte o SQL em array associativo 
$dados_sql = [];
foreach ($array_processos as $item) {
    $dados_sql[$item['mes']] = (int) $item['total'];
}

//  Mescla os dados reais nos meses 
foreach ($meses as $mes => $valor) {
    if (isset($dados_sql[$mes])) {
        $meses[$mes] = $dados_sql[$mes];
    }
}

//  Arrays finais para o gráfico 
$mapaMeses = [
    '01' => 'JAN',
    '02' => 'FEV',
    '03' => 'MAR',
    '04' => 'ABR',
    '05' => 'MAI',
    '06' => 'JUN',
    '07' => 'JUL',
    '08' => 'AGO',
    '09' => 'SET',
    '10' => 'OUT',
    '11' => 'NOV',
    '12' => 'DEZ'
];

$labels = array_map(function ($mes) use ($mapaMeses) {
    $mesNumero = substr($mes, 0, 2); // pega o MM do m-Y
    return $mapaMeses[$mesNumero];
}, array_keys($meses));

$valores = array_values($meses);










// Atividades do Mês - Gráfico 2
$sql_atividades_mes = "SELECT 
    DAYOFWEEK(data_inicio) AS dia_semana_num,
    COUNT(*) AS total
FROM eventos_crm
WHERE data_inicio >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
  AND data_inicio <  DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH)
    AND usuario_config_id_usuario_config = $id_user
GROUP BY DAYOFWEEK(data_inicio)
ORDER BY dia_semana_num";

$atividades_mes = $conexao->query($sql_atividades_mes);
$dias_atividades = [];
while ($row = $atividades_mes->fetch_assoc()) {
    $dias_atividades[] = $row;
}

$mapaDias = [
    1 => 'DOM',
    2 => 'SEG',
    3 => 'TER',
    4 => 'QUA',
    5 => 'QUI',
    6 => 'SEX',
    7 => 'SAB'
];

$resultado = array_fill_keys($mapaDias, 0);

foreach ($dias_atividades as $dia) {
    $nomeDia = $mapaDias[$dia['dia_semana_num']];
    $resultado[$nomeDia] = (int) $dia['total'];
}

$labels_graf2 = array_keys($resultado);
$valores_graf2 = array_values($resultado);










// Gráfico 3 - Honorários Mensais 
$sql_honorarios_mensais = "SELECT 
    SUM(
        CAST(
            REPLACE(
                REPLACE(valor_honorarios, '.', ''),
                ',', '.'
            ) AS DECIMAL(10,2)
        )
    ) AS total_honorarios,
    DATE_FORMAT(dt_cadastro_processo, '%m-%Y') AS mes
FROM processo
WHERE dt_cadastro_processo >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
  AND usuario_config_id_usuario_config = $id_user
GROUP BY DATE_FORMAT(dt_cadastro_processo, '%Y-%m')
ORDER BY mes";

$honorarios_mensais = $conexao->query($sql_honorarios_mensais);
//  Array que recebe o resultado do SQL 
$array_honorarios = [];
while ($row = $honorarios_mensais->fetch_assoc()) {
    $array_honorarios[] = $row;
}

//  Cria os últimos 12 meses com valor 0 
$meses_honorarios = [];
for ($i = 11; $i >= 0; $i--) {
    $meses_honorarios[date('m-Y', strtotime("-$i months"))] = 0;
}

//  Converte o SQL em array associativo 
$dados_sql_honorarios = [];
foreach ($array_honorarios as $item) {
    $dados_sql_honorarios[$item['mes']] = (float) $item['total_honorarios'];
}

//  Mescla os dados reais nos meses 
foreach ($meses_honorarios as $mes => $valor) {
    if (isset($dados_sql_honorarios[$mes])) {
        $meses_honorarios[$mes] = $dados_sql_honorarios[$mes];
    }
}
;


//  Arrays finais para o gráfico
$labels_graf3 = array_map(function ($mes) use ($mapaMeses) {
    $mesNumero = substr($mes, 0, 2); // pega o MM do m-Y
    return $mapaMeses[$mesNumero];
}, array_keys($meses_honorarios));

$valores_graf3 = array_values($meses_honorarios);









// Gráfico 4 - Tipos de Casos 
$sql_tipos_casos = "SELECT 
    grupo_acao,
    COUNT(*) AS total
FROM processo
WHERE usuario_config_id_usuario_config = $id_user
GROUP BY grupo_acao
ORDER BY total DESC";

$tipos_casos = $conexao->query($sql_tipos_casos);
$labels_graf4 = [];
$valores_graf4 = [];
while ($row = $tipos_casos->fetch_assoc()) {
    $labels_graf4[] = ucfirst($row['grupo_acao']);
    $valores_graf4[] = (int) $row['total'];
}
;









// Gráfico 5 - Processos por Etapa (CRM)
$sql_processos_etapa = "SELECT 
    p.etapa_kanban, e.nome,
    COUNT(*) AS total
FROM processo p
RIGHT JOIN etapas_crm e  ON p.etapa_kanban = e.id_etapas_crm
WHERE p.usuario_config_id_usuario_config = $id_user
AND p.status = 'ativo'
GROUP BY p.etapa_kanban, e.nome
ORDER BY total DESC";

$processos_etapa = $conexao->query($sql_processos_etapa);
$labels_graf5 = [];
$valores_graf5 = [];
while ($row = $processos_etapa->fetch_assoc()) {
    $labels_graf5[] = ucfirst($row['nome']);
    $valores_graf5[] = (int) $row['total'];
}









// Gráfico 6 - Resultados dos Processos
$sql_resultados_processos = "SELECT 
    resultado_processo,
    COUNT(*) AS total
FROM processo
WHERE usuario_config_id_usuario_config = $id_user
AND resultado_processo IS NOT NULL
GROUP BY resultado_processo
ORDER BY total DESC";
$resultados_processos = $conexao->query($sql_resultados_processos);
$labels_graf6 = [];
$valores_graf6 = [];
while ($row = $resultados_processos->fetch_assoc()) {
    $labels_graf6[] = ucfirst($row['resultado_processo']);
    $valores_graf6[] = (int) $row['total'];
}


?>