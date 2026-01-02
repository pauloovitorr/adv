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
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home/home.css">
    <script src="/adv/sistema/js/home.js" defer></script>
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
                <div class="welcome-section">
                    <div class="welcome-content">
                        <h1 class="welcome-title">Seja bem-vindo novamente!</h1>
                        <p class="welcome-subtitle">Aqui está um resumo do seu escritório</p>

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
                            <h3>Atividades do mês</h3>
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

            <!-- CDN Libraries -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
            <script src="https://unpkg.com/lucide@latest"></script>

        </div>
    </main>


</body>

</html>