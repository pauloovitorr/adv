<?php

include_once('../config.php');



if ($_SERVER['REQUEST_METHOD'] === 'POST'   && !empty($_POST['name'])  && !empty($_POST['email']) 
    && !empty($_POST['phone'])  && !empty($_POST['message'])  && !empty($_POST['modelo'])
) {

    $nome_lead     = $conexao->escape_string(htmlspecialchars($_POST['name']));
    $email_lead    = $conexao->escape_string(htmlspecialchars($_POST['email']));
    $telefone_lead = $conexao->escape_string(htmlspecialchars($_POST['phone']));
    $mensagem_lead = $conexao->escape_string(htmlspecialchars($_POST['message']));
    $modelo        = $conexao->escape_string(htmlspecialchars($_POST['modelo'])); // token

    // Busca dados do dono do site
    $sql_usuario = "
        SELECT id_usuario_config, email 
        FROM usuario_config 
        WHERE tk = '$modelo'
        LIMIT 1
    ";

    $res = $conexao->query($sql_usuario);

    if ($res && $res->num_rows > 0) {

        $usuario = $res->fetch_assoc();
        $email_dono_site = $usuario['email'];
        $id_usuario_config = $usuario['id_usuario_config'];

        // Captura dados do cliente
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        // INSERT DO LEAD
        $sql_cadastra_leads = "
            INSERT INTO leads (
                nome,
                email,
                telefone,
                mensagem,
                ip,
                user_agent,
                usuario_config_id_usuario_config
            ) VALUES (
                '$nome_lead',
                '$email_lead',
                '$telefone_lead',
                '$mensagem_lead',
                '$ip',
                '$user_agent',
                $id_usuario_config
            )
        ";

        $conexao->query($sql_cadastra_leads);

        // Envio de e-mail
        if (envia_email($nome_lead, $email_lead, $telefone_lead, $mensagem_lead, $email_dono_site)) {
            $resposta = [
                'status' => 'success',
                'message' => 'Mensagem enviada com sucesso!',
            ];
        } else {
            $resposta = [
                'status' => 'erro',
                'message' => 'Falha ao enviar e-mail, tente pelo WhatsApp!',
            ];
        }

        echo json_encode($resposta, JSON_UNESCAPED_UNICODE);
    }

    $conexao->close();
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['modelo'])) {


    $modelo = $conexao->escape_string(htmlspecialchars($_GET['modelo'] ?? ''));


    $sql_busca_user = "SELECT id_usuario_config FROM usuario_config WHERE tk = '$modelo'";
    $result = $conexao->query($sql_busca_user);

    if ($result->num_rows > 0) {
        $dados_user = $result->fetch_assoc();
        $id_user = $dados_user["id_usuario_config"];

        $sql_busca_config_modelo = "SELECT * FROM configuracao_modelo WHERE usuario_config_id_usuario_config = '$id_user'";
        $result_modelo = $conexao->query($sql_busca_config_modelo);

        if ($result_modelo->num_rows > 0) {
            $config_modelo = $result_modelo->fetch_assoc();
        }


        // Busca os dados referentes aos depoimentos
        $sql_busca_depoimentos = "SELECT nome,texto FROM depoimentos WHERE usuario_config_id_usuario_config = '$id_user'";
        $result_depoimentos = $conexao->query($sql_busca_depoimentos);

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


    <?php

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "LegalService",

        "name" => "Dra. Laisla Maria | Advocacia Penal e Civil",
        "areaServed" => "Brasil",
        "priceRange" => "$$$",

        "image" => ($config_modelo['banner'] ?? '') ?: "https://example.com/hero.jpg",
        "url" => "https://example.com",

        "telephone" => ($config_modelo['telefone_whatsapp'] ?? '') ?: "+55 11 99999-9999",
        "email" => ($config_modelo['email'] ?? '') ?: "email@padrao.com",
        "description" => ($config_modelo['sobre'] ?? '') ?: "Descrição padrão do escritório.",
        "serviceType" => ($config_modelo['areas_atuacao'] ?? '') ?: "Trabalhista, Civil, Penal",

        "address" => [
            "@type" => "PostalAddress",
            "addressCountry" => "BR",
            "streetAddress" => ($config_modelo['endereco'] ?? '') ?: ""
        ],

        "sameAs" => [
            "https://wa.me/" . (
                !empty($config_modelo['telefone_whatsapp'])
                ? preg_replace('/\D/', '', $config_modelo['telefone_whatsapp'])
                : "5511999999999"
            )
        ]
    ];

    echo '<script type="application/ld+json">'
        . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        . '</script>';

    ?>





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

                <?php if (isset($result_depoimentos) && $result_depoimentos->num_rows > 0):
                    while ($depoimento = $result_depoimentos->fetch_assoc()): ?>

                        <div class="testimonial-card active">
                            <p><?php echo $depoimento['texto'] ?></p>
                            <span><?php echo $depoimento['nome'] ?></span>
                        </div>

                    <?php endwhile;
                else: ?>


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

                <?php endif ?>

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
                    <form action="./index.php" id="contact-form" method="post">
                        <input type="text" name="name" placeholder="Nome" required minlength="4" maxlength="150">
                        <input type="email" name="email" placeholder="E-mail" required minlength="4" maxlength="150">
                        <input type="tel" name="phone" placeholder="Telefone / WhatsApp" id="tell" minlength="14"
                            required>
                        <input type="hidden" id="modelo" name="modelo" value="<?php echo $_GET['modelo'] ?? '' ?>">
                        <textarea name="message" placeholder="Mensagem" rows="4" required minlength="4"
                            maxlength="250"></textarea>
                        <button type="submit" class="cta-button" id="enviar_msg">Enviar Mensagem</button>
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
                <p>© Paulo Vitor 2025 | Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Botão WhatsApp Flutuante -->
    <a href="https://wa.me/55<?php echo $telefone_limpo; ?>" target="_blank" class="whatsapp-float">
        <i class="fa-brands fa-whatsapp" style="font-size: 32px;color: white;"></i>
    </a>

    <!-- JS -->
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#tell').mask('(99) 99999-9999')

        $(function () {

            $('#contact-form').on('submit', function (e) {
                e.preventDefault();
                
                Swal.showLoading();

                const $btn = $('#enviar_msg');
                $btn.prop('disabled', true);

                if (!$(this).find('#modelo').val()) {

                    Swal.fire({
                        title: "Sucesso!",
                        text: "Mensagem encaminhada com sucesso (Demonstração)!",
                        icon: "success"
                    });

                    $btn.prop('disabled', false);
                    return;
                }


                let telefone = $('#tell').cleanVal(); 

                if (telefone.length < 11) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Telefone inválido',
                        text: 'Digite um número de WhatsApp válido.',
                        confirmButtonText: 'Ok'
                    });
                    $btn.prop('disabled', false);
                    return
                }




                $.ajax({
                    url: './index.php',
                    method: 'POST',
                    dataType: 'json',
                    data: $(this).serialize(),

                    success: function (res) {
                        if (res.status === 'success') {
                            Swal.fire("Sucesso", res.message, "success");
                        } else {
                            Swal.fire("Falha", res.message, "error");
                        }
                    },

                    error: function () {
                        Swal.fire("Erro", "Erro ao enviar mensagem", "error");
                    },

                    complete: function () {
                        $btn.prop('disabled', false);
                    }
                });
            });
        });


    </script>



    <!--  Puxa os códigos de estilização personalizados -->
    <?php echo $config_modelo["estilizacao"] ?? ''; ?>

</body>




</html>