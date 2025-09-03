<?php

include_once('../../scripts.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    var_dump($_FILES);
}

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pessoas/docs_pessoa.css">
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />



    <title>Documentos</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>

<body>

    <main class="container_principal">

        <div class="pai_conteudo">

            <section class="container_etapa_cadastro">
                <div class="etapa">
                    <div class="num bg_selecionado">1º</div>
                    <div class="descricao color_selecionado">Cadastro</div>
                </div>

                <div class="separador bg_selecionado"></div>

                <div class="etapa">
                    <div class="num bg_selecionado">2º</div>
                    <div class="descricao color_selecionado">Documentos</div>
                </div>

                <div class="separador bg_selecionado"></div>

                <div class="etapa">
                    <div class="num">3º</div>
                    <div class="descricao">Finalização</div>
                </div>

            </section>


            <section class="container_cadastro">

                <div class="docs">
                    <div class="arquivos">
                        <p>Nenhum arquivo cadastrado</p>
                        <img src="../../img/file.png" alt="Clique para enviar" style="width:80px;" alt="">
                    </div>

                    <div class="upload">
                        <form action="./docs_pessoa.php" enctype="multipart/form-data" class="dropzone" id="my-awesome-dropzone"></form>
            
                        <div class="container_btns">
                            <button class="btn_cadastrar" id="enviar_arquivos" role="button"><i class="fa-solid fa-arrow-up-from-bracket" style="color: white;"></i> Enviar Arquivos</button>

                            <button class="btn_finalizar" role="button"><i class="fa-solid fa-check"></i> Apenas Finalizar </button>
                        </div>


                    </div>

                </div>



            </section>

        </div>

    </main>

    <script>
        // Inicialize o Dropzone
        Dropzone.options.myAwesomeDropzone = {
            url: "./docs_pessoa.php", // URL do seu backend
            maxFilesize: 3, // Limite de 3 MB
            addRemoveLinks: true, // Ativa links de remoção
            autoProcessQueue: false, // Desativa upload automático
            dictFileTooBig: "O arquivo é muito grande ({{filesize}} MB). Limite: {{maxFilesize}} MB.",
            dictRemoveFile: "X", // Texto do link (ou '' se usar só ícone)
            dictCancelUpload: "Cancelar upload",
            dictInvalidFileType: "Tipo de arquivo não permitido",
            dictResponseError: "Erro no servidor: {{statusCode}}",
            dictMaxFilesExceeded: "Você excedeu o limite de arquivos",
            dictDefaultMessage: `
                    <div style="text-align:center">
                        <img src="../../img/add_arquivo.png" alt="Clique para enviar" style="width:80px;">
                        <p>Clique ou arraste seus documentos aqui</p>
                    </div>
                `,

            // Evento de inicialização para configurar o botão de envio
            init: function() {
                var myDropzone = this; // Referência ao Dropzone

                // Adicione um listener ao seu botão de envio
                document.getElementById("enviar_arquivos").addEventListener("click", function() {
                    myDropzone.processQueue(); // Envia todos os arquivos na fila
                });
            }
        };
    </script>
</body>

</html>