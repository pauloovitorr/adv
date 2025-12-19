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

                                    <label for="arquivos" class="botao-icone-input">
                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                    </label>

                                    <input type="file" id="arquivos" hidden>


                                    <button type="button" class="botao-icone-input" id="microfone">
                                        <i class="fa fa-microphone" aria-hidden="true"></i>
                                        <i class="fa-solid fa-stop" style="display:none"></i>
                                    </button>
                                </div>

                                <textarea class="campo-input-ia" rol="1"
                                    placeholder="Pergunte qualquer coisa sobre seu processo, prazos ou documentos..."></textarea>


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




            let recognition = null;
            let textoFinal = '';

            const textarea = document.querySelector('.campo-input-ia');

            function manterFocoNoFinal(el) {
                el.focus();
                el.scrollTop = el.scrollHeight;
                el.setSelectionRange(el.value.length, el.value.length);
            }

            $('#microfone').on('click', function () {

                $(this).toggleClass('transcrevendo_audio');

                const mic = $(this).find('.fa-microphone');
                const stop = $(this).find('.fa-stop');

                if (mic.is(':visible')) {
                    mic.fadeOut(50);
                    stop.fadeIn(300);

                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    if (!SpeechRecognition) {
                        alert("Seu navegador não suporta Web Speech API");
                        return;
                    }

                    recognition = new SpeechRecognition();
                    recognition.lang = "pt-BR";
                    recognition.continuous = true;
                    recognition.interimResults = true;

                    recognition.onresult = (event) => {
                        let textoInterim = '';

                        for (let i = event.resultIndex; i < event.results.length; i++) {
                            const resultado = event.results[i][0].transcript;

                            if (event.results[i].isFinal) {
                                textoFinal += resultado + ' ';
                            } else {
                                textoInterim += resultado;
                            }
                        }

                        textarea.value = textoFinal + textoInterim;
                        manterFocoNoFinal(textarea);
                    };

                    $('.botao-enviar-ia')
                        .prop('disabled', true)
                        .addClass('desabilitado');


                    recognition.start();

                } else {
                    stop.fadeOut(50);
                    mic.fadeIn(300);

                    $('.botao-enviar-ia')
                        .prop('disabled', false)
                        .removeClass('desabilitado');


                    if (recognition) {
                        recognition.stop();
                        recognition = null;
                    }
                }
            });






        });
    </script>


    <!-- <script>
    const SpeechRecognition =
      window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
      alert("Seu navegador não suporta Web Speech API");
    } else {
      const recognition = new SpeechRecognition();

      recognition.lang = "pt-BR";
      recognition.continuous = true;
      recognition.interimResults = true;

      const output = document.getElementById("output");

      recognition.onstart = () => {
        console.log("🎙️ Escutando...");
      };

      recognition.onerror = event => {
        console.error("Erro:", event.error);
      };

      recognition.onend = () => {
        console.log("🛑 Parou");
      };

      recognition.onresult = event => {
        let texto = "";

        for (let i = event.resultIndex; i < event.results.length; i++) {
          texto += event.results[i][0].transcript;
        }

        output.innerText = texto;
      };

      document.getElementById("start").onclick = () => {
        recognition.start();
      };

      document.getElementById("stop").onclick = () => {
        recognition.stop();
      };
    }
  </script> -->

</body>

</html>