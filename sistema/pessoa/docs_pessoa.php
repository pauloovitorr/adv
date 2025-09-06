<?php

include_once('../../scripts.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {

    $arquivo = $_FILES['file'];

    $nome_arquivo = $arquivo["name"];
    $extensao_arquivo = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));
    $temp_name = $arquivo["tmp_name"];
    $tamanhoArquivo = $arquivo['size'];
    $novo_nome_arquivo = uniqid() . date('now') . '.' . $extensao_arquivo;

    $caminho = 'docs/' . $novo_nome_arquivo;

    $conexao->begin_transaction();

    try {
        if ($tamanhoArquivo > 3 * 1024 * 1024) {
            $res = [
                'status' => 'erro',
                'message' => 'Arquivo muito grande! Tamanho máximo permitido de 2MB'
            ];

            http_response_code(413);
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            $conexao->rollback();
            $conexao->close();

            exit;
        } elseif ($arquivo['error'] !== 0) {
            $res = [
                'status' => 'erro',
                'message' => 'Imagem com erro'
            ];

            http_response_code(415);
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            $conexao->rollback();
            $conexao->close();
            exit;
        } else {

            if (move_uploaded_file($temp_name, $caminho)) {

                $id_user = $_SESSION['cod'];
                $ip = $_SERVER['REMOTE_ADDR'];

                $sql_docs = "INSERT INTO documento (nome_original, caminho_arquivo, dt_criacao, usuario_config_id_usuario_config) VALUES (?, ?, NOW(), ?)";

                $stmt = $conexao->prepare($sql_docs);
                $stmt->bind_param("ssi", $nome_arquivo, $caminho, $id_user);

                if ($stmt->execute()) {

                    if (cadastro_log('Cadastrou Documento', $nome_arquivo, $ip, $id_user)) {

                        $res = [
                            'status' => 'success',
                            'message' => 'Documento cadastrado com sucesso!',
                        ];

                        http_response_code(200);
                        echo json_encode($res, JSON_UNESCAPED_UNICODE);
                        $conexao->commit();
                        $conexao->close();
                        exit;
                    } else {

                        unlink($caminho);

                        $res = [
                            'status' => 'erro',
                            'message' => 'Erro ao cadastrar imagem, tente novamente em alguns minutos!'
                        ];

                        http_response_code(500);
                        echo json_encode($res, JSON_UNESCAPED_UNICODE);
                        $conexao->rollback();
                        $conexao->close();
                        exit;
                    }
                }
            } else {
                $res = [
                    'status' => 'erro',
                    'message' => 'Erro ao cadastrar imagem!'
                ];

                http_response_code(500);
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();
                exit;
            }
        }

        exit;
    } catch (Exception $err) {
        echo "Erro: " . $err->getMessage();
        $conexao->rollback();
        $conexao->close();
    }

    exit;
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
            url: "./docs_pessoa.php", // URL do backend
            maxFilesize: 3, // Limite de 3 MB
            maxFiles: 30, // permitir até 10
            parallelUploads: 30, // manda vários de uma vez
            uploadMultiple: false,
            addRemoveLinks: true, // Ativa links de remoção
            autoProcessQueue: false, // Desativa upload automático
            dictFileTooBig: "O arquivo é muito grande ({{filesize}} MB). Limite: {{maxFilesize}} MB.",
            dictRemoveFile: "X", // Texto do link (ou '' se usar só ícone)
            dictCancelUpload: "",
            dictInvalidFileType: "Tipo de arquivo não permitido",
            dictResponseError: "Erro no servidor",
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

                // Listener no botão de envio
                document.getElementById("enviar_arquivos").addEventListener("click", function(e) {
                    e.preventDefault(); // Evita comportamento padrão do botão

                    // Verifica se já tem arquivos na fila
                    if (myDropzone.getAcceptedFiles().length === 0) {
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            text: "Nenhum arquivo foi adicionado",
                        });
                        return;
                    }

                    // Se houver arquivos, processa a fila
                    myDropzone.processQueue();
                });

                // myDropzone.on("queuecomplete", function() {
                //     // Aguarda 1s para mostrar o check e recarrega
                //     setTimeout(function() {
                //         location.reload();
                //     }, 2000);
                // });

                // Captura resposta de sucesso do backend
                myDropzone.on("success", function(file, response) {
                    console.log("Sucesso:", response);
                });

                // Captura erros (ex.: servidor retornou 500, 404 ou JSON inválido)
                // myDropzone.on("error", function(file, errorMessage, xhr) {
                //     console.error("Erro:", errorMessage);
                //     Swal.fire({
                //         icon: "error",
                //         title: "Erro",
                //         text: "Nenhum ao enviar arquivo",
                //     });
                // });
            }
        };
    </script>

</body>

</html>