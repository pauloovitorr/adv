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
                        <h3>Seu Assistente de IA</h3>
                        <span>LLM • Neural Engine ativo</span>
                    </div>

                    <div class="msgs">

                        <h2>Comece uma conversa</h2>
                        <p>
                            Explique seu caso ou dúvida jurídica e anexe arquivos se precisar.
                        </p>

                    </div>


                    <!-- Barra de input IA -->
                    <div class="container_input_ia">
                        <div class="barra-input-ia">
                            <div class="barra-input-conteudo">
                                <div class="grupo-icones-esquerda">
                                    <button type="button" class="botao-icone-input">
                                        <i class="fa-solid fa-microchip"></i>
                                    </button>
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

    </script>
</body>

</html>