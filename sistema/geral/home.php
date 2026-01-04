<?php include_once('../../scripts.php');

$id_user = $_SESSION['cod'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sql_verifica_primeiro_acesso = "SELECT primeiro_acesso from usuario_config where id_usuario_config = $id_user";
    $res = $conexao->query($sql_verifica_primeiro_acesso);
    $res = $res->fetch_assoc();

    if ($res["primeiro_acesso"] == 'sim') {

        $etapas_padrao_crm = [
            "Análise do Caso",
            "Negociação",
            "Aguardando Documentos",
            "Proposta",
            "Ação Protocolada",
            "Aguardando Audiência",
            "Aguardando Julgamento",
            "Desenvolvendo Recurso",
            "Fechamento"
        ];

        try {
            $conexao->begin_transaction();

            foreach ($etapas_padrao_crm as $indice => $etapa) {
                $ordem = $indice + 1;
                $sql_insert_etapas = "INSERT INTO etapas_crm (ordem, nome, usuario_config_id_usuario_config) 
                              VALUES ($ordem, '{$conexao->real_escape_string($etapa)}', $id_user)";

                if (!$conexao->query($sql_insert_etapas)) {
                    throw new Exception("Erro ao inserir etapa: " . $conexao->error);
                }
            }

            $sql_remove_primeiro_acesso = "UPDATE usuario_config 
                                   SET primeiro_acesso = 'nao' 
                                   WHERE id_usuario_config = $id_user";

            if (!$conexao->query($sql_remove_primeiro_acesso)) {
                throw new Exception("Erro ao atualizar primeiro acesso: " . $conexao->error);
            }

            $conexao->commit();
        } catch (Exception $erro) {
            $conexao->rollback();
            echo "Falha: " . $erro->getMessage();
        }
    }


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


    // Gráfico 3 - Honorários Mensais da tabela processo
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



}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home/home.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />



    <title>ADV Conectado</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>
<div class="container_breadcrumb">
    <div class="pai_topo">
        <div class="breadcrumb">
            <span class="breadcrumb-current">Home</span>
            <span class="breadcrumb-separator">/</span>
        </div>
    </div>
</div>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">


            <section class="container_home">
                <!-- Seção de Boas-vindas com Confetti -->
                <div class="welcome-section" data-aos="fade-up">
                    <div class="welcome-content">
                        <h1 class="welcome-title">Seja bem-vindo novamente!</h1>
                        <p class="welcome-subtitle">Aqui está um resumo das suas atividades</p>

                    </div>

                </div>

                <!-- Grid de Gráficos -->
                <div class="charts-grid">


                    <!-- Gráfico 1: Novos Processos -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Novos Processos</h3>
                            <i data-lucide="trending-up"></i>
                        </div>
                        <canvas id="chartNovosProcessos"></canvas>
                    </div>


                    <!-- Gráfico 2: Atividades da Semana -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Atividades do mês (Dia Início)</h3>
                            <i data-lucide="calendar-check"></i>
                        </div>
                        <canvas id="chartAtividades"></canvas>
                    </div>

                    <!-- Gráfico 3: Honorários Mensais -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Honorários Mensais</h3>
                            <i data-lucide="dollar-sign"></i>
                        </div>
                        <canvas id="chartHonorarios"></canvas>
                    </div>

                    <!-- Gráfico 4: Processos por Área -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Áreas de Atuação</h3>
                            <i data-lucide="briefcase"></i>
                        </div>
                        <canvas id="chartAreasAtuacao"></canvas>
                    </div>


                    <!-- Gráfico 5: Processos por Status -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Processos por Etapa (CRM)</h3>
                            <i data-lucide="folder-open"></i>
                        </div>
                        <canvas id="chartProcessosStatus"></canvas>
                    </div>

                    <!-- Gráfico 6: Taxa de Sucesso -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Taxa de Sucesso</h3>
                            <i data-lucide="target"></i>
                        </div>
                        <canvas id="chartTaxaSucesso"></canvas>
                    </div>


                </div>
            </section>

            <!-- Efeito de confetti -->
            <canvas id="confetti-canvas"></canvas>



        </div>
    </main>


    <!-- CDN Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Gráficos da Home -->
    <script>
        $(function () {
            // Configuração global dos gráficos
            Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
            Chart.defaults.font.size = 12;
            Chart.defaults.color = '#6b7280';

            // Gráfico 1: Novos Processos (Line)
            const ctx1 = document.getElementById('chartNovosProcessos').getContext('2d');

            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label: 'Processos',
                        data: <?php echo json_encode($valores); ?>,
                        borderColor: '#4A9EFF',
                        backgroundColor: 'rgba(74, 158, 255, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            // Gráfico 2: Atividades da Semana (Bar Horizontal)
            const ctx2 = document.getElementById('chartAtividades').getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($labels_graf2) ?>,
                    datasets: [{
                        label: 'Atividades',
                        data: <?php echo json_encode($valores_graf2) ?>,
                        backgroundColor: '#8B5CF6',
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });


            // Gráfico 3: Honorários Mensais (Line com área)
            const ctx3 = document.getElementById('chartHonorarios').getContext('2d');
            new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($labels_graf3) ?>,
                    datasets: [{
                        label: 'R$ Honorários',
                        data: <?php echo json_encode($valores_graf3) ?>,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'R$ ' + value.toLocaleString('pt-BR');
                                }
                            },
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });


            // Gráfico 4: Áreas de Atuação (Bar)
            const ctx4 = document.getElementById('chartAreasAtuacao').getContext('2d');
            new Chart(ctx4, {
                type: 'polarArea',
                data: {
                    labels: ['Civil', 'Trabalhista', 'Criminal', 'Família', 'Tributário'],
                    datasets: [{
                        label: 'Processos',
                        data: [45, 38, 28, 35, 22],
                        backgroundColor: ['#4A9EFF', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });


            // Gráfico 5: Processos por Status (Doughnut)
            const ctx5 = document.getElementById('chartProcessosStatus').getContext('2d');
            new Chart(ctx5, {
                type: 'doughnut',
                data: {
                    labels: ['Em Andamento', 'Arquivados', 'Aguardando', 'Concluídos'],
                    datasets: [{
                        data: [45, 28, 15, 32],
                        backgroundColor: ['#4A9EFF', '#10B981', '#F59E0B', '#8B5CF6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });



            // Gráfico 6: Taxa de Sucesso (Pie)
            const ctx6 = document.getElementById('chartTaxaSucesso').getContext('2d');
            new Chart(ctx6, {
                type: 'pie',
                data: {
                    labels: ['Ganhos', 'Perdidos', 'Acordos'],
                    datasets: [{
                        data: [65, 15, 20],
                        backgroundColor: ['#10B981', '#EF4444', '#F59E0B'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        })
    </script>


    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    </script>




    <!-- Inicializar Lucide Icons e efeito de confetti -->
    <script>

        $(function () {
            lucide.createIcons();

            // // Efeito de Confetti
            // function launchConfetti() {
            //     var end = Date.now() + (1 * 1000); // 1 segundos

            //     var colors = ['#061124', '#ffffff'];

            //     (function frame() {
            //         confetti({
            //             particleCount: 2,
            //             angle: 60,
            //             spread: 55,
            //             origin: { x: 0 },
            //             colors: colors
            //         });

            //         confetti({
            //             particleCount: 2,
            //             angle: 120,
            //             spread: 55,
            //             origin: { x: 1 },
            //             colors: colors
            //         });

            //         if (Date.now() < end) {
            //             requestAnimationFrame(frame);
            //         }
            //     })();
            // }


            // // Lançar confetti ao carregar a página
            // window.addEventListener('load', function () {
            //     setTimeout(launchConfetti, 1000);
            // });
        })

    </script>
</body>

</html>