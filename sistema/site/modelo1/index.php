<?php

date_default_timezone_set('America/Sao_Paulo');

require __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$data_base = $_ENV['DB_BASE'];

$conexao = new mysqli($host, $user, $password, $data_base);
$conexao->set_charset("utf8");

// $config_modelo = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['modelo'])) {


    $modelo = $conexao->escape_string(htmlspecialchars($_GET['modelo'] ?? ''));


    $sql_busca_user = "SELECT * FROM usuario_config WHERE tk = '$modelo'";
    $result = $conexao->query($sql_busca_user);

    if ($result->num_rows > 0) {
        $dados_user = $result->fetch_assoc();
        $id_user = $dados_user["id_usuario_config"];

        $sql_busca_config_modelo = "SELECT * FROM configuracao_modelo WHERE usuario_config_id_usuario_config = '$id_user'";
        $result_modelo = $conexao->query($sql_busca_config_modelo);

        if ($result_modelo->num_rows > 0) {
            $config_modelo = $result_modelo->fetch_assoc();
            // var_dump($config_modelo);
        }

    }

}

$banner = (!empty($config_modelo) && !empty($config_modelo['banner']))
    ? '../..' . $config_modelo['banner']
    : './imgs/banners.jpg';


$foto_adv = (!empty($config_modelo) && !empty($config_modelo['foto_adv']))
    ? '../..' . $config_modelo['foto_adv']
    : './imgs/advogada.png';


$telefone_raw = $config_modelo['telefone_whatsapp'] ?? '(00) 00000-0000';
$telefone_limpo = preg_replace('/\D+/', '', $telefone_raw);


$areas_atuacao = $config_modelo['areas_atuacao'] ?? null;

?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dra. Laisla Maria | Advocacia Penal e Civil</title>
    <meta name="description"
        content=" <?php echo $config_modelo['frase_inicial'] ?? 'Defesa Criminal e Direito Civil com excelência e discrição. Atendimento sigiloso, estratégico e personalizado. Fale agora no WhatsApp ou agende uma consulta.'; ?> " />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Playfair+Display:wght@600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css" />
    <meta name="theme-color" content="#121212" />
    <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LegalService",
    "name": "Dra. Laisla Maria | Advocacia Penal e Civil",
    "areaServed": "Brasil",
    "priceRange": "$$$",
    "image": "https://example.com/hero.jpg",
    "url": "https://example.com",
    "telephone": "+55 11 99999-9999",
    "address": { "@type": "PostalAddress", "addressCountry": "BR" },
    "sameAs": ["https://wa.me/5511999999999"]
  }
  </script>


    <style>
        :root {
            --bg-primary:
                <?php echo $config_modelo['cor_primaria'] ?? '#121212' ?>
            ;
            --bg-secondary:
                <?php echo $config_modelo['cor_secundaria'] ?? '#020202ff' ?>
            ;
            --color-gold: #C6A664;
            --color-white: #e8ebe9ff;
            --color-grey: #B5B5B5;
            --color-whatsapp: #25D366;
            --font-serif:
                <?php echo $config_modelo["fonte1"] ?? 'Playfair Display' ?>
            ;
            --font-sans:
                <?php echo $config_modelo["fonte2"] ?? 'Inter' ?>
            ;
            ;
        }

        .hero-section {
            background: url("<?php echo $banner ?>") no-repeat center center/cover;
        }
    </style>
</head>

<body>

    <!-- Header Fixo -->
    <header class="header">
        <div class="container">
            <a href="#inicio" class="logo">
                <?php echo $config_modelo["area_atuacao_principal"] ?? 'Advocacia Penal e Civil'; ?> </a>
            <nav class="main-nav">
                <ul>
                    <li><a href="#inicio">Início</a></li>
                    <li><a href="#sobre">Sobre</a></li>
                    <li><a href="#atuacao">Áreas de Atuação</a></li>
                    <li><a href="#depoimentos">Depoimentos</a></li>
                    <li><a href="#contato">Contato</a></li>
                </ul>
            </nav>

        </div>
    </header>

    <!-- Seção Hero -->
    <section id="inicio" class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h1 class="animate-on-scroll">
                <?php echo $config_modelo['frase_inicial'] ?? 'Defesa Criminal e Direito Civil com Excelência e Discrição.'; ?>
            </h1>
            <h2 class="animate-on-scroll">
                <?php echo $config_modelo['frase_secundaria'] ?? 'Atuação estratégica e sigilosa em casos complexos do Direito Penal e Civil.'; ?>
            </h2>
            <div class="hero-ctas animate-on-scroll">
                <a href="https://wa.me/55<?php echo $telefone_limpo; ?>" target="_blank" class="cta-button primary">Fale
                    Agora no
                    WhatsApp</a>
                <a href="#contato" class="cta-button secondary">Agendar Consulta</a>
            </div>
        </div>
    </section>

    <!-- Sobre o Advogado -->
    <section id="sobre" class="about-section">
        <div class="container two-columns">
            <div class="about-image animate-on-scroll">
                <img src="<?php echo $foto_adv ?>">
            </div>
            <div class="about-content animate-on-scroll">
                <h3>Sobre Mim</h3>
                <p><?php echo $config_modelo['sobre'] ?? 'Advogado especializado em Direito Penal e Civil, com ampla experiência em defesas criminais, ações
                    indenizatórias e consultoria preventiva. Comprometido com ética, estratégia e resultados.'; ?></p>

                <a href="#contato" class="cta-button">Solicitar Atendimento</a>
            </div>
        </div>
    </section>

    <!-- Áreas de Atuação -->
    <section id="atuacao" class="services-section">
        <div class="container">
            <h3 class="section-title">Áreas de Atuação</h3>
            <div class="services-grid">

                <?php if ($areas_atuacao):

                    $areas_atuacao = explode(',', trim($areas_atuacao));
                    $areas_atuacao = array_map('trim', $areas_atuacao);

                    foreach ($areas_atuacao as $area): ?>

                        <div class="service-card animate-on-scroll">
                            <div class="service-icon"><?php echo substr($area, 0, 1) ?></div>
                            <h4><?php echo $area ?></h4>
                        </div>

                    <?php endforeach ?>

                <?php else: ?>

                    <div class="service-card animate-on-scroll">
                        <div class="service-icon">PC</div>
                        <h4>Defesa em Processos Criminais</h4>
                    </div>
                    <div class="service-card animate-on-scroll">
                        <div class="service-icon">EE</div>
                        <h4>Crimes Econômicos e Empresariais</h4>
                    </div>
                    <div class="service-card animate-on-scroll">
                        <div class="service-icon">TJ</div>
                        <h4>Tribunal do Júri</h4>
                    </div>
                    <div class="service-card animate-on-scroll">
                        <div class="service-icon">CC</div>
                        <h4>Direito Civil e Contratos</h4>
                    </div>
                    <div class="service-card animate-on-scroll">
                        <div class="service-icon">AI</div>
                        <h4>Ações Indenizatórias</h4>
                    </div>
                    <div class="service-card animate-on-scroll">
                        <div class="service-icon">CP</div>
                        <h4>Consultoria Preventiva</h4>
                    </div>

                <?php endif ?>



            </div>
        </div>
    </section>

    <!-- Depoimentos -->
    <section id="depoimentos" class="testimonials-section">
        <div class="container">
            <h3 class="section-title">Prova Social</h3>
            <div class="testimonial-slider">
                <div class="testimonial-card active">
                    <p>"Profissional exemplar, conduziu meu caso com total sigilo e excelência."</p>
                    <span>— Cliente Satisfeito</span>
                </div>
                <div class="testimonial-card">
                    <p>"Atendimento rápido, humano e muito eficiente. Recomendo fortemente o Dra. Paulo."</p>
                    <span>— J.C., Empresário</span>
                </div>
                <div class="testimonial-card">
                    <p>"Sua estratégia de defesa foi crucial para o resultado positivo do meu processo."</p>
                    <span>— M.A., Cliente</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Sessão CTA Direta -->
    <section class="direct-cta-section">
        <div class="container">
            <h3>
                <?php echo $config_modelo['frase_chamada_cta']
                    ?? 'Precisa de um advogado criminalista de confiança?'; ?>
            </h3>

            <p>
                <?php echo $config_modelo['frase_chamada_cta_secundaria']
                    ?? 'Atendimento 100% sigiloso e estratégico.'; ?>
            </p>

            <a href="https://wa.me/55<?php echo $telefone_limpo; ?>" target="_blank" class="cta-button">
                Solicitar Atendimento
            </a>

        </div>
    </section>

    <!-- Contato e Footer -->
    <footer id="contato" class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="contact-form-wrapper">
                    <h4>Envie sua Mensagem</h4>
                    <form id="contact-form">
                        <input type="text" name="name" placeholder="Nome" required>
                        <input type="email" name="email" placeholder="E-mail" required>
                        <input type="tel" name="phone" placeholder="Telefone / WhatsApp" required>
                        <textarea name="message" placeholder="Mensagem" rows="4" required></textarea>
                        <button type="submit" class="cta-button">Enviar Mensagem</button>
                    </form>
                </div>
                <div class="contact-info">
                    <h4>Contatos Diretos</h4>




                    <p>
                        <a href="https://wa.me/55<?php echo $telefone_limpo; ?>" target="_blank">
                            <strong>WhatsApp:</strong>
                            <?php echo $telefone_raw; ?>
                        </a>
                    </p>

                    <p>
                        <a href="mailto:<?php echo $config_modelo['email'] ?? 'contato@dr.com'; ?>">
                            <strong>E-mail:</strong>
                            <?php echo $config_modelo['email'] ?? 'contato@dr.com'; ?>
                        </a>
                    </p>

                    <p>
                        <strong>Endereço:</strong>
                        <?php echo $config_modelo['endereco']
                            ?? 'Av. Principal, 123, Sala 45, Cidade-UF'; ?>
                    </p>
                </div>

            </div>
            <div class="footer-bottom">
                <p>© 2025 Dra. Laisla Maria – OAB [UF/00000] | Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Botão WhatsApp Flutuante -->
    <a href="https://wa.me/5500000000000" target="_blank" class="whatsapp-float">
        <i class="fa-brands fa-whatsapp" style="font-size: 32px;color: white;"></i>
    </a>

    <!-- JS -->
    <script src="script.js"></script>
</body>




</html>