<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pessoas/ficha_pessoa.css">
    <title>Pessoas</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <!-- Conteúdo da página -->
            <div class="page-content">
                <!-- Header do perfil -->
                <div class="profile-header">
                    

                    <div class="profile-title-section">
                        <div class="profile-photo-container">
                            <img src="/img/img_clientes/68c71cb2ea79468c71cb2ea799.jpg" alt="Foto do cliente" class="profile-photo" id="clientPhoto">
                            <div class="profile-photo-placeholder" id="photoPlaceholder" style="display: none;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="profile-info">
                            <h1 class="profile-name">PAULO VITOR SANTOS DA SILVA</h1>
                            <div class="profile-meta">
                                <span class="profile-type">
                                    <i class="fas fa-user-tie"></i> Cliente
                                </span>
                                <span class="profile-origin">
                                    <i class="fas fa-tag"></i> Indicação
                                </span>

                                <span class="profile-origin">
                                    <i class="fas fa-file-alt"></i>
                                    Última Atualização: 10/09/2025 às 19:56
                                </span>

                                <span class="profile-status active">
                                    <i class="fas fa-circle"></i> Ativo
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <button class="btn-secondary">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn-primary">
                            <i class="fas fa-plus"></i> Novo Processo
                        </button>
                    </div>
                </div>

                <!-- Conteúdo principal em tabs -->
                <div class="profile-content">
                    <!-- Tab Navigation -->
                    <div class="tab-navigation">
                        <a href="#personal">
                            <button class="tab-item active" data-tab="personal">
                                <i class="fas fa-user"></i> Dados Pessoais
                            </button>
                        </a>
                        <a href="#contact">
                            <button class="tab-item" data-tab="contact">
                                <i class="fas fa-phone"></i> Contato
                            </button>
                        </a>
                        <a href="#address">
                            <button class="tab-item" data-tab="address">
                                <i class="fas fa-map-marker-alt"></i> Endereço
                            </button>
                        </a>
                        <a href="#documents">
                            <button class="tab-item" data-tab="documents">
                                <i class="fas fa-file-alt"></i> Documentos
                            </button>
                        </a>
                        <a href="#processes">
                            <button class="tab-item" data-tab="processes">
                                <i class="fas fa-briefcase"></i> Processos
                            </button>
                        </a>

                    </div>


                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Dados Pessoais -->
                        <div class="tab-pane" id="personal">
                            <div class="info-grid">
                                <div class="info-card">
                                    <div class="info-item">
                                        <label class="info-label">Nome Completo</label>
                                        <div class="info-value">PAULO VITOR SANTOS DA SILVA</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Data de Nascimento</label>
                                        <div class="info-value">02/09/2025</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Sexo</label>
                                        <div class="info-value">Masculino</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Estado Civil</label>
                                        <div class="info-value">Solteiro(a)</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Profissão</label>
                                        <div class="info-value">Dentista</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Nome da Mãe</label>
                                        <div class="info-value">Eliete</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contato -->
                        <div class="tab-pane" id="contact">
                            <div class="info-grid">
                                <div class="info-card">
                                    <div class="info-item">
                                        <label class="info-label">Telefone Principal</label>
                                        <div class="info-value">
                                            <i class="fas fa-phone contact-icon"></i>
                                            (18) 99760-7919
                                            <button class="btn-link">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Telefone Secundário</label>
                                        <div class="info-value">
                                            <i class="fas fa-phone contact-icon"></i>
                                            (18) 99760-7919
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Celular</label>
                                        <div class="info-value">
                                            <i class="fas fa-mobile-alt contact-icon"></i>
                                            (18) 9976-0791
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">E-mail Principal</label>
                                        <div class="info-value">
                                            <i class="fas fa-envelope contact-icon"></i>
                                            paulov.pv50@gmail.com
                                            <button class="btn-link">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">E-mail Secundário</label>
                                        <div class="info-value">
                                            <i class="fas fa-envelope contact-icon"></i>
                                            paulov.pv50@gmail.com
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div class="tab-pane" id="address">
                            <div class="info-grid">
                                <div class="info-card">
                                    <div class="info-item">
                                        <label class="info-label">CEP</label>
                                        <div class="info-value">19062-339</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Estado</label>
                                        <div class="info-value">SP</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Cidade</label>
                                        <div class="info-value">Presidente Prudente</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Bairro</label>
                                        <div class="info-value">Residencial Parque dos Girassóis</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Logradouro</label>
                                        <div class="info-value">Rua João Pedro Pereira</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Número</label>
                                        <div class="info-value">629</div>
                                    </div>
                                    <div class="info-item full-width">
                                        <label class="info-label">Complemento</label>
                                        <div class="info-value">Próximo ao mercado</div>
                                    </div>
                                    <div class="info-item full-width">
                                        <label class="info-label">Observações</label>
                                        <div class="info-value">Visitas apenas nos fim de semana</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documentos -->
                        <div class="tab-pane" id="documents">
                            <div class="info-grid">
                                <div class="info-card">
                                    <div class="info-item">
                                        <label class="info-label">CPF</label>
                                        <div class="info-value">376.842.074-40</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">RG</label>
                                        <div class="info-value">39.735.353-4</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">PIS/PASEP</label>
                                        <div class="info-value">815.3306.386-4</div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">CTPS</label>
                                        <div class="info-value">0010019581981010409</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Processos -->
                        <div class="tab-pane" id="processes">
                            <div class="processes-section">
                                <div class="section-header">
                                    <h3>Processos do Cliente</h3>
                                    <button class="btn-primary">
                                        <i class="fas fa-plus"></i> Novo Processo
                                    </button>
                                </div>
                                <div class="empty-state">
                                    <i class="fas fa-briefcase empty-icon"></i>
                                    <h4>Nenhum processo cadastrado</h4>
                                    <p>Este cliente ainda não possui processos associados.</p>
                                    <button class="btn-primary">
                                        <i class="fas fa-plus"></i> Criar Primeiro Processo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>


        </div>
    </main>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabItems = document.querySelectorAll('.tab-item');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabItems.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all tabs and panes
                    tabItems.forEach(t => t.classList.remove('active'));
                    tabPanes.forEach(p => p.classList.remove('active'));

                    // Add active class to clicked tab and corresponding pane
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });
        })
    </script>

</body>

</html>