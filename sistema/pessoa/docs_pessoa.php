<?php

include_once('../../scripts.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['tkn'])) {
    header('location:' . './pessoas.php');
}

$id_user = $_SESSION['cod'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['tkn'])) {
    $token_pessoa = $conexao->escape_string(htmlspecialchars($_GET['tkn']));
    $sql_busca_pessoa_tkn = 'SELECT id_pessoa FROM pessoas where tk = ? and usuario_config_id_usuario_config = ?';
    $stmt = $conexao->prepare($sql_busca_pessoa_tkn);
    $stmt->bind_param('si', $token_pessoa, $id_user);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $res_pessoa_tkn = $result->fetch_assoc();
        $id_pessoa = $res_pessoa_tkn['id_pessoa'];
        $sql_busca_docs = "SELECT * FROM documento where id_pessoa = $id_pessoa and usuario_config_id_usuario_config = $id_user";

        $lista_docs = $conexao->query($sql_busca_docs);
        $conexao->close();
    } else {
        header('location: ./pessoas.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {

    $token_pessoa = $conexao->escape_string(htmlspecialchars($_POST['tkn']));
    $sql_busca_pessoa_tkn = 'SELECT id_pessoa FROM pessoas where tk = ? and usuario_config_id_usuario_config = ?';
    $stmt = $conexao->prepare($sql_busca_pessoa_tkn);
    $stmt->bind_param('si', $token_pessoa, $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $res_pessoa_tkn = $result->fetch_assoc();
        $id_pessoa = $res_pessoa_tkn['id_pessoa'];
    } else {
        $res = [
            'status' => 'erro',
            'message' => 'Pessoa responsável pelo documentato não foi encontrada no sistema!'
        ];
        http_response_code(404);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        $conexao->close();
        exit;
    }


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


                $ip = $_SERVER['REMOTE_ADDR'];

                $sql_docs = "INSERT INTO documento (nome_original, caminho_arquivo, dt_criacao, id_pessoa, usuario_config_id_usuario_config) VALUES (?, ?, NOW(),?,?)";

                $stmt = $conexao->prepare($sql_docs);
                $stmt->bind_param("ssii", $nome_arquivo, $caminho, $id_pessoa, $id_user);

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

                        if (file_exists($caminho)) {
                            unlink($caminho);
                        }

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

        if (file_exists($caminho)) {
            unlink($caminho);
        }

        $res = [
            'status' => 'erro',
            'message' => 'Erro:' . $err->getMessage()
        ];

        http_response_code(500);
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        $conexao->rollback();
        $conexao->close();
        exit;
    }

    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    $id_doc = $data['id'] ?? null;

    if ($id_doc) {
        try {
            // Inicia a transação
            $conexao->begin_transaction();

            // Busca os dados do documento
            $sql_busca_dados = "SELECT nome_original, caminho_arquivo 
                                FROM documento 
                                WHERE id_documento = ?  
                                AND usuario_config_id_usuario_config = ?";

            $stmt = $conexao->prepare($sql_busca_dados);
            $stmt->bind_param('ii', $id_doc, $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            $doc = $result->fetch_assoc();

            if (!$doc) {
                $res = [
                    'status' => 'error',
                    'message' => 'Documento não encontrado.'
                ];
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();
                exit;
            }

            $nome_original = $doc['nome_original'];
            $caminho_server = $doc['caminho_arquivo'];

            // Se existir arquivo, tenta excluir
            if (file_exists($caminho_server)) {
                if (!unlink($caminho_server)) {
                    $res = [
                        'status' => 'error',
                        'message' => 'Erro ao excluir o arquivo do servidor.'
                    ];
                    echo json_encode($res, JSON_UNESCAPED_UNICODE);
                    $conexao->rollback();
                    $conexao->close();
                    exit;
                }
            }

            // Exclui o registro do banco
            $sql_delete_doc = 'DELETE FROM documento WHERE id_documento = ? AND usuario_config_id_usuario_config = ?';
            $stmt = $conexao->prepare($sql_delete_doc);
            $stmt->bind_param('ii', $id_doc, $id_user);

            if (!$stmt->execute()) {
                $res = [
                    'status' => 'error',
                    'message' => 'Erro ao excluir registro no banco de dados.'
                ];
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();
                exit;
            }

            // Registra no log
            $ip = $_SERVER['REMOTE_ADDR'];
            if (!cadastro_log('Excluiu Documento', $nome_original, $ip, $id_user)) {
                $res = [
                    'status' => 'error',
                    'message' => 'Erro ao registrar log.'
                ];
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();
                exit;
            }

            // Se tudo deu certo
            $res = [
                'status' => 'success',
                'message' => 'Documento excluído com sucesso!',
            ];
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            $conexao->commit();
            $conexao->close();
            exit;
        } catch (Exception $err) {
            // Erro inesperado
            $res = [
                'status' => 'error',
                'message' => 'Erro ao excluir documento, tente mais tarde!'
            ];
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            $conexao->rollback();
            $conexao->close();
            exit;
        }
    }
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

                    <?php if ($lista_docs->num_rows > 0):  ?>
                        <div class="lista_arquivos">

                            <?php while ($doc = $lista_docs->fetch_assoc()): ?>

                                <?php $ext = strtolower(pathinfo($doc["caminho_arquivo"], PATHINFO_EXTENSION)); ?>

                                <a href="<?php echo $doc["caminho_arquivo"] ?>" target="__blank">
                                    <div class="doc">
                                        <?php if (in_array($ext, ['png', 'jpg', 'jpeg'])): ?>
                                            <span class="dz-remove remove_documento">X
                                                <input type="hidden" class="token" value="<?php echo $doc['id_documento'] ?>">
                                            </span>
                                            <img class="img_bg_doc" src="<?php echo $doc["caminho_arquivo"] ?>" alt="" srcset="">
                                            <div class="nome_arquivo"><span><?php echo $doc["nome_original"] ?></span></div>

                                        <?php else: ?>
                                            <span class="dz-remove remove_documento">X
                                                <input type="hidden" class="token" value="<?php echo $doc['id_documento'] ?>">
                                            </span>
                                            <i class="fa-regular fa-folder" style="font-size: 30px;"></i>
                                            <div class="nome_arquivo"><span><?php echo $doc["nome_original"] ?></span></div>

                                        <?php endif  ?>
                                    </div>
                                </a>
                            <?php endwhile ?>

                        </div>
                    <?php else: ?>
                        <div class="sem_arquivos">
                            <p>Nenhum arquivo cadastrado</p>
                            <img src="../../img/file.png" alt="Clique para enviar" style="width:80px;" alt="">
                        </div>

                    <?php endif  ?>

                    <div class="upload">
                        <form action="./docs_pessoa.php" enctype="multipart/form-data" class="dropzone" id="my-awesome-dropzone"></form>

                        <div class="container_btns">

                            <button class="btn_cadastrar" id="enviar_arquivos" role="button"><i class="fa-solid fa-arrow-up-from-bracket" style="color: white;"></i> Salvar Arquivos</button>

                            <button class="btn_finalizar" role="button"><i class="fa-solid fa-check"></i> <?php echo $lista_docs->num_rows > 0 ? 'Finalizar' : 'Finalizar Sem Documento' ?> </button>
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
            acceptedFiles: ".png,.jpg,.jpeg,.txt,.pdf,.doc,.docx,.csv,.xlsx",
            dictDefaultMessage: `
                <div style="text-align:center">
                    <img src="../../img/add_arquivo.png" alt="Clique para enviar" style="width:80px;">
                    <p>Clique ou arraste seus documentos aqui</p>
                </div>
            `,
            params: {
                tkn: "<?= $_GET['tkn'] ?? '' ?>"
            },

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

                myDropzone.on("queuecomplete", function() {
                    // Aguarda 1s para mostrar o check e recarrega
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                });

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


    <script>
        $(function() {
            $('.remove_documento').on('click', function(event) {
                // Evita que o clique abra o link
                event.preventDefault();
                event.stopPropagation();

                let id_documento = $(this).find('.token').val();

                Swal.fire({
                    title: "Deseja realmente excluir o documento?",
                    text: "A ação é irreversível!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sim, excluir!",
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: './docs_pessoa.php',
                            type: 'DELETE',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                id: id_documento
                            }),
                            dataType: 'json',
                            success: function(res) {
                                Swal.fire({
                                    title: "Exclusão",
                                    text: "Pessoa excluída com sucesso!",
                                    icon: "success"
                                });

                                setTimeout(() => {
                                    Swal.close();
                                    window.location.reload();
                                }, 500);
                            }
                        });
                    }
                });
            });


            $('.btn_finalizar').on('click', function() {

                Swal.fire({
                    title: "Finalizado",
                    text: "Cadastro finalizado com sucesso!",
                    icon: "success"
                }).then(() => {
                    window.location.href = "./pessoas.php";
                });


            })
        });
    </script>


</body>

</html>