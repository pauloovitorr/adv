<?php
include_once('../../scripts.php');


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/ia/ia.css">
    <title>ADV Conectado</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>

<div class="container_breadcrumb">
    <div class="pai_topo">
        <div class="breadcrumb">
            <!-- <a href="./pessoas.php" class="breadcrumb-link">Pessoas</a>
            <span class="breadcrumb-separator">/</span> -->
            <span class="breadcrumb-current">IA</span>
            <span class="breadcrumb-separator">/</span>

        </div>
    </div>
</div>


<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <div class="container_chat">
                <aside class="historico">
                    <div class="topo_conversas">
                        <h3>Conversas</h3>
                    </div>
                    <div class="container_conversas">
                        <div class="historico_chat"><span>Como Comprar arroz?</span> <i
                                class="fa-regular fa-trash-can"></i></div>
                    </div>
                </aside>
                <div class="chat">
                    <div class="topo_conversa_atual">
                        <div class="infos">
                            <h3>Seu Assistente de IA</h3>
                            <span>LLM • Neural Engine ativo</span>
                        </div>

                        <div class="modelo_llm">
                            <span class="dot"></span>
                            ChatGPT 5 — Acesso NET
                        </div>

                    </div>

                    <div class="msgs">

                        <div class="msg_padrao">
                            <h2>Comece uma conversa</h2>
                            <p>
                                Explique seu caso ou dúvida jurídica e anexe arquivos se precisar.
                            </p>
                        </div>

                        <!-- <div class="container_msg_usuario">
                            <div class="msg_usuario"><span>Quero saber como é a lei do Brasil Quero saber como é a lei
                                    do Brasil Quero saber como é a lei do Brasil</span></div>
                        </div> -->

                        <!-- <div class="container_msg_ia">
                            <div class="msg_ia"><span>kkkkkkkkkkkkk</span></div>
                            <span class="modelo_resposta"> Perplexity</span>
                        </div> -->

                    </div>


                    <!-- Barra de input IA -->
                    <div class="container_input_ia">
                        <div class="barra-input-ia">
                            <div class="barra-input-conteudo">
                                <div class="grupo-icones-esquerda">
                                    <div class="llm-selector">
                                        <button type="button" class="botao-icone-input btn-llm">
                                            <i class="fa-solid fa-microchip"></i>
                                        </button>

                                        <div class="llm-dropdown">
                                            
                                            <div class="llm-item active" data-model="GPT-5.2">
                                                GPT-5.2 
                                            </div>

                                            <div class="llm-item" data-model="Gemini 3 Pro">
                                                Gemini 3 Pro
                                            </div>

                                            <div class="llm-item" data-model="Grok 4.1">
                                                Grok 4.1
                                            </div>

                                            <div class="llm-item" data-model="Kimi K2 Thinking">
                                                Kimi K2 Thinking
                                            </div>

                                            <div class="llm-footer">
                                                Modelos Para Testes
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="botao-icone-input">
                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="botao-icone-input">
                                        <i class="fa fa-microphone" aria-hidden="true"></i>
                                    </button>
                                </div>

                                <input type="text" class="campo-input-ia"
                                    placeholder="Pergunte qualquer coisa sobre seu processo, prazos ou documentos..." />

                                <button type="button" class="botao-enviar-ia">
                                    <i class="fa fa-arrow-up" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </main>



    <script>
        $(function () {

            function enviarMensagem() {
                let $input = $('.campo-input-ia');
                let texto = $input.val().trim();

                if (texto === '') return;

                $('.msg_padrao').hide()

                let $msg = $(`
                <div class="container_msg_usuario" style="display:none">
                    <div class="msg_usuario">
                        <span>${texto}</span>
                    </div>
                </div>
            `);

                $('.msgs').append($msg);
                $msg.fadeIn(300);

                // limpa o input após enviar
                $input.val('');
            }

            // Clique no botão
            $('.botao-enviar-ia').on('click', function () {
                enviarMensagem();
            });

            // Tecla Enter no input
            $('.campo-input-ia').on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // evita quebra de linha / submit
                    enviarMensagem();
                }
            });






            // Toggle dropdown
            $('.btn-llm').on('click', function (e) {
                e.stopPropagation();
                $('.llm-dropdown').fadeToggle(150);
            });

            // Selecionar modelo
            $('.llm-item').on('click', function () {
                $('.llm-item').removeClass('active');
                $(this).addClass('active');

                let modelo = $(this).data('model');
                console.log('Modelo selecionado:', modelo);
                $('.modelo_llm').html(`<span class="dot"></span> ${modelo}`);


                $('.llm-dropdown').fadeOut(150);
            });

            // Fechar ao clicar fora
            $(document).on('click', function () {
                $('.llm-dropdown').fadeOut(150);
            });

            


        });
    </script>


</body>

</html>