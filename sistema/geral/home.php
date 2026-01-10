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


    include_once('graficos_home.php');


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

                    <!-- Gráfico 6: Resultado dos Processos -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Resultado dos Processos</h3>
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
                    labels: <?php echo json_encode($labels_graf4) ?>,
                    datasets: [{
                        label: 'Processos',
                        data: <?php echo json_encode($valores_graf4) ?>,
                        backgroundColor: [
                            '#4A9EFF', // azul
                            '#10B981', // verde
                            '#F59E0B', // amarelo
                            '#8B5CF6', // roxo
                            '#EF4444', // vermelho
                            '#06B6D4', // ciano
                            '#F97316', // laranja
                            '#22C55E'  // verde claro
                        ]
                        ,
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
                    labels: <?php echo json_encode($labels_graf5) ?>,
                    datasets: [{
                        data: <?php echo json_encode($valores_graf5) ?>,
                        backgroundColor: [
                            '#4A9EFF', // azul
                            '#10B981', // verde
                            '#F59E0B', // amarelo
                            '#8B5CF6', // roxo
                            '#EF4444', // vermelho
                            '#06B6D4', // ciano
                            '#F97316', // laranja
                            '#22C55E'  // verde claro
                        ]
                        ,
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
                    labels: <?php echo json_encode($labels_graf6) ?>,
                    datasets: [{
                        data: <?php echo json_encode($valores_graf6) ?>,
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