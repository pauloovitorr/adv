<?php


include_once('../../scripts.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {


    $sql_config_modelo = "SELECT * FROM configuracao_modelo WHERE usuario_config_id_usuario_config = $id_user";
    $retorno_sql = $conexao->query($sql_config_modelo);
    $dados_modelo = '';

    if ($retorno_sql->num_rows > 0) {
        $dados_modelo = $retorno_sql->fetch_assoc();
    }

    // var_dump($dados_modelo);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['telefone_whatsapp']) && !empty($_POST['email']) && !empty($_POST['endereco']) && !empty($_POST['frase_chamada_cta']) && !empty($_POST['frase_chamada_cta_secundaria']) && !empty($_POST['sobre'])) {


    if ($_FILES['foto_adv_arquivo']['error'] === 4 || $_FILES['banner_arquivo']['error'] === 4) {

        $sql_verifica = "SELECT banner, foto_adv  FROM configuracao_modelo  WHERE usuario_config_id_usuario_config = $id_user";
        $res = $conexao->query($sql_verifica);

        $dados_verifica = $res->fetch_assoc();

        if ($dados_verifica["banner"] == "" || $dados_verifica["foto_adv"] == "") {

            $conexao->close();

            $res = [
                'status' => 'erro',
                'message' => 'Adicione o banner e a foto do advogado!'
            ];
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    // else{
    //     var_dump($_FILES);
    //     die;
    // }


    $fonte1 = $conexao->escape_string(htmlspecialchars($_POST['fonte1'] ?? ''));
    $fonte2 = $conexao->escape_string(htmlspecialchars($_POST['fonte2'] ?? ''));
    $area_atuacao = $conexao->escape_string(htmlspecialchars($_POST['area_atuacao'] ?? ''));
    $frase_inicial = $conexao->escape_string(htmlspecialchars($_POST['frase_inicial'] ?? ''));
    $frase_secundaria = $conexao->escape_string(htmlspecialchars($_POST['frase_secundaria'] ?? ''));
    $telefone_whatsapp = $conexao->escape_string(htmlspecialchars($_POST['telefone_whatsapp'] ?? ''));
    $email = $conexao->escape_string(htmlspecialchars($_POST['email'] ?? ''));
    $endereco = $conexao->escape_string(htmlspecialchars($_POST['endereco'] ?? ''));
    $frase_chamada_cta = $conexao->escape_string(htmlspecialchars($_POST['frase_chamada_cta'] ?? ''));
    $frase_chamada_cta_secundaria = $conexao->escape_string(htmlspecialchars($_POST['frase_chamada_cta_secundaria'] ?? ''));
    $sobre = $conexao->escape_string(htmlspecialchars($_POST['sobre'] ?? ''));
    $areas_atuacao = $conexao->escape_string(htmlspecialchars($_POST['areas_atuacao'] ?? ''));
    $estilizacao = $_POST['estilizacao'] ?? '';


    $banner_arquivo = $_FILES['banner_arquivo'];
    $foto_adv_arquivo = $_FILES['foto_adv_arquivo'];


    $banner_nome_origem = $banner_arquivo['name'];
    $ftadv_nome_origem = $foto_adv_arquivo['name'];

    try {
        $conexao->begin_transaction();

        if ($banner_arquivo['name']) {
            $nomeArquivo = $banner_arquivo['name'];
            $tmpArquivo = $banner_arquivo['tmp_name'];
            $tamanhoArquivo = $banner_arquivo['size'];

            $extensao_arquivo = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

            $novo_nome_arquivo = uniqid() . uniqid() . '.' . $extensao_arquivo;

            if ($tamanhoArquivo > 5 * 1024 * 1024) {

                // Apesar de aceitar 5 mb eu informo que é até 3mb
                $res = [
                    'status' => "erro",
                    'message' => "Arquivo $nomeArquivo muito grande! Tamanho máximo permitido de 3MB"
                ];

                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();

                exit;
            } elseif ($banner_arquivo['error'] !== 0) {
                $res = [
                    'status' => 'erro',
                    'message' => 'Imagem com erro'
                ];

                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();

                exit;
            } else {
                $caminho = '../geral/docs/site';

                $novo_caminho = $caminho . '/' . $novo_nome_arquivo;

                $retorno_img_movida = move_uploaded_file($tmpArquivo, $novo_caminho);

                if ($retorno_img_movida) {
                    $caminho_banner_adv = '/geral/docs/site/' . $novo_nome_arquivo;
                }
            }
        }

        if ($foto_adv_arquivo['name']) {

            $nomeArquivo = $foto_adv_arquivo['name'];
            $tmpArquivo = $foto_adv_arquivo['tmp_name'];
            $tamanhoArquivo = $foto_adv_arquivo['size'];

            $extensao_arquivo = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

            $novo_nome_arquivo = uniqid() . uniqid() . '.' . $extensao_arquivo;

            // Validação de tamanho — usa 5MB como limite real
            if ($tamanhoArquivo > 5 * 1024 * 1024) {

                $res = [
                    'status' => "erro",
                    'message' => "Arquivo $nomeArquivo muito grande! Tamanho máximo permitido de 3MB"
                ];

                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();
                exit;
            } elseif ($foto_adv_arquivo['error'] !== 0) {

                $res = [
                    'status' => 'erro',
                    'message' => 'Imagem com erro'
                ];

                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();
                exit;
            } else {

                $caminho = '../geral/docs/site';

                $novo_caminho = $caminho . '/' . $novo_nome_arquivo;

                $retorno_img_movida = move_uploaded_file($tmpArquivo, $novo_caminho);

                if ($retorno_img_movida) {

                    $caminho_foto_adv = '/geral/docs/site/' . $novo_nome_arquivo;
                }
            }
        }


        if ($_POST['acao'] == 'cadastrar_configuracao') {
            $sql_configura_modelo = "INSERT INTO configuracao_modelo (
            fonte1,
            fonte2,
            area_atuacao_principal,
            banner,
            nome_origem_banner,
            frase_inicial,
            frase_secundaria,
            telefone_whatsapp,
            email,
            sobre,
            foto_adv,
            nome_origem_ftadv,
            areas_atuacao,
            frase_chamada_cta,
            frase_chamada_cta_secundaria,
            endereco,
            estilizacao,
            usuario_config_id_usuario_config
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";

            $stmt = $conexao->prepare($sql_configura_modelo);

            $stmt->bind_param(
                "sssssssssssssssssi",
                $fonte1,
                $fonte2,
                $area_atuacao,
                $caminho_banner_adv,          // banner
                $banner_nome_origem,
                $frase_inicial,
                $frase_secundaria,
                $telefone_whatsapp,
                $email,
                $sobre,
                $caminho_foto_adv,        // foto_adv
                $ftadv_nome_origem,
                $areas_atuacao,
                $frase_chamada_cta,
                $frase_chamada_cta_secundaria,
                $endereco,
                $estilizacao,
                $id_user
            );
        } elseif ($_POST['acao'] == 'atualizar_configuracao') {

            $sql_configura_modelo = "
        UPDATE configuracao_modelo SET
            fonte1 = ?,
            fonte2 = ?,
            area_atuacao_principal = ?,
            banner = ?,
            nome_origem_banner = ?,
            frase_inicial = ?,
            frase_secundaria = ?,
            telefone_whatsapp = ?,
            email = ?,
            sobre = ?,
            foto_adv = ?,
            nome_origem_ftadv = ?,
            areas_atuacao = ?,
            frase_chamada_cta = ?,
            frase_chamada_cta_secundaria = ?,
            endereco = ?,
            estilizacao = ?,
            dt_atualizacao_modelo = NOW()
        WHERE usuario_config_id_usuario_config = ?
    ";

            $stmt = $conexao->prepare($sql_configura_modelo);

            $stmt->bind_param(
                "sssssssssssssssssi",
                $fonte1,
                $fonte2,
                $area_atuacao,
                $caminho_banner_adv,
                $banner_nome_origem,
                $frase_inicial,
                $frase_secundaria,
                $telefone_whatsapp,
                $email,
                $sobre,
                $caminho_foto_adv,
                $ftadv_nome_origem,
                $areas_atuacao,
                $frase_chamada_cta,
                $frase_chamada_cta_secundaria,
                $endereco,
                $estilizacao,
                $id_user   // WHERE
            );
        }




        if ($stmt->execute()) {

            if (cadastro_log('Configurou modelo ', $identificador_log, $ip, $id_user)) {

                $conexao->commit();
                $conexao->close();

                $res = [
                    'status' => 'success',
                    'message' => 'Modelo configurado com sucesso!',
                ];
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $conexao->rollback();
                $conexao->close();

                $res = [
                    'status' => 'erro',
                    'message' => 'Erro ao configurar modelo!'
                ];
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    } catch (Exception $err) {
        $res = [
            'status' => 'erro',
            'message' => $err->getMessage()
        ];

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        $conexao->rollback();
        $conexao->close();
        exit;
    }
}



// if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['telefone_whatsapp']) && !empty($_POST['email']) && !empty($_POST['endereco']) && !empty($_POST['frase_chamada_cta']) && !empty($_POST['frase_chamada_cta_secundaria']) && !empty($_POST['sobre']) && !empty($_FILES['foto_adv_arquivo']) && !empty($_FILES['banner_arquivo']) && $_POST['acao'] == 'atualizar_configuracao') {

//     // var_dump($_POST);

//     $fonte1                          = $conexao->escape_string(htmlspecialchars($_POST['fonte1'] ?? ''));
//     $fonte2                          = $conexao->escape_string(htmlspecialchars($_POST['fonte2'] ?? ''));
//     $area_atuacao                    = $conexao->escape_string(htmlspecialchars($_POST['area_atuacao'] ?? ''));
//     $frase_inicial                   = $conexao->escape_string(htmlspecialchars($_POST['frase_inicial'] ?? ''));
//     $frase_secundaria                = $conexao->escape_string(htmlspecialchars($_POST['frase_secundaria'] ?? ''));
//     $telefone_whatsapp               = $conexao->escape_string(htmlspecialchars($_POST['telefone_whatsapp'] ?? ''));
//     $email                           = $conexao->escape_string(htmlspecialchars($_POST['email'] ?? ''));
//     $endereco                        = $conexao->escape_string(htmlspecialchars($_POST['endereco'] ?? ''));
//     $frase_chamada_cta               = $conexao->escape_string(htmlspecialchars($_POST['frase_chamada_cta'] ?? ''));
//     $frase_chamada_cta_secundaria    = $conexao->escape_string(htmlspecialchars($_POST['frase_chamada_cta_secundaria'] ?? ''));
//     $sobre                           = $conexao->escape_string(htmlspecialchars($_POST['sobre'] ?? ''));
//     $areas_atuacao                   = $conexao->escape_string(htmlspecialchars($_POST['areas_atuacao'] ?? ''));
//     $estilizacao                     = $_POST['estilizacao'] ?? '';


//     $banner_arquivo       = $_FILES['banner_arquivo'];
//     $foto_adv_arquivo     = $_FILES['foto_adv_arquivo'];


//     $banner_nome_origem = $banner_arquivo['name'];
//     $ftadv_nome_origem = $foto_adv_arquivo['name'];

//     try {
//         $conexao->begin_transaction();

//         if ($banner_arquivo['name']) {
//             $nomeArquivo = $banner_arquivo['name'];
//             $tmpArquivo = $banner_arquivo['tmp_name'];
//             $tamanhoArquivo = $banner_arquivo['size'];

//             $extensao_arquivo = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

//             $novo_nome_arquivo = uniqid() . uniqid() . '.' . $extensao_arquivo;

//             if ($tamanhoArquivo > 5 * 1024 * 1024) {

//                 // Apesar de aceitar 5 mb eu informo que é até 3mb
//                 $res = [
//                     'status' => "erro",
//                     'message' => "Arquivo $nomeArquivo muito grande! Tamanho máximo permitido de 3MB"
//                 ];

//                 echo json_encode($res, JSON_UNESCAPED_UNICODE);
//                 $conexao->rollback();
//                 $conexao->close();

//                 exit;
//             } elseif ($banner_arquivo['error'] !== 0) {
//                 $res = [
//                     'status' => 'erro',
//                     'message' => 'Imagem com erro'
//                 ];

//                 echo json_encode($res, JSON_UNESCAPED_UNICODE);
//                 $conexao->rollback();
//                 $conexao->close();

//                 exit;
//             } else {
//                 $caminho = '../geral/docs/site';

//                 $novo_caminho = $caminho . '/' . $novo_nome_arquivo;

//                 $retorno_img_movida =   move_uploaded_file($tmpArquivo, $novo_caminho);

//                 if ($retorno_img_movida) {
//                     $caminho_banner_adv = '/geral/docs/site/' . $novo_nome_arquivo;
//                 }
//             }
//         }

//         if ($foto_adv_arquivo['name']) {

//             $nomeArquivo = $foto_adv_arquivo['name'];
//             $tmpArquivo = $foto_adv_arquivo['tmp_name'];
//             $tamanhoArquivo = $foto_adv_arquivo['size'];

//             $extensao_arquivo = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

//             $novo_nome_arquivo = uniqid() . uniqid() . '.' . $extensao_arquivo;

//             // Validação de tamanho — usa 5MB como limite real
//             if ($tamanhoArquivo > 5 * 1024 * 1024) {

//                 $res = [
//                     'status' => "erro",
//                     'message' => "Arquivo $nomeArquivo muito grande! Tamanho máximo permitido de 3MB"
//                 ];

//                 echo json_encode($res, JSON_UNESCAPED_UNICODE);
//                 $conexao->rollback();
//                 $conexao->close();
//                 exit;
//             } elseif ($foto_adv_arquivo['error'] !== 0) {

//                 $res = [
//                     'status' => 'erro',
//                     'message' => 'Imagem com erro'
//                 ];

//                 echo json_encode($res, JSON_UNESCAPED_UNICODE);
//                 $conexao->rollback();
//                 $conexao->close();
//                 exit;
//             } else {

//                 $caminho = '../geral/docs/site';

//                 $novo_caminho = $caminho . '/' . $novo_nome_arquivo;

//                 $retorno_img_movida = move_uploaded_file($tmpArquivo, $novo_caminho);

//                 if ($retorno_img_movida) {

//                     $caminho_foto_adv = '/geral/docs/site/' . $novo_nome_arquivo;
//                 }
//             }
//         }



//         $sql_configura_modelo = "INSERT INTO configuracao_modelo (
//             fonte1,
//             fonte2,
//             area_atuacao_principal,
//             banner,
//             nome_origem_banner,
//             frase_inicial,
//             frase_secundaria,
//             telefone_whatsapp,
//             email,
//             sobre,
//             foto_adv,
//             nome_origem_ftadv,
//             areas_atuacao,
//             frase_chamada_cta,
//             frase_chamada_cta_secundaria,
//             endereco,
//             estilizacao,
//             usuario_config_id_usuario_config
//         ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";

//         $stmt = $conexao->prepare($sql_configura_modelo);

//         $stmt->bind_param(
//             "sssssssssssssssssi",
//             $fonte1,
//             $fonte2,
//             $area_atuacao,
//             $caminho_banner_adv,          // banner
//             $banner_nome_origem,
//             $frase_inicial,
//             $frase_secundaria,
//             $telefone_whatsapp,
//             $email,
//             $sobre,
//             $caminho_foto_adv,        // foto_adv
//             $ftadv_nome_origem,
//             $areas_atuacao,
//             $frase_chamada_cta,
//             $frase_chamada_cta_secundaria,
//             $endereco,
//             $estilizacao,
//             $id_user
//         );

//         if ($stmt->execute()) {

//             if (cadastro_log('Configurou modelo ', $identificador_log, $ip, $id_user)) {

//                 $conexao->commit();
//                 $conexao->close();

//                 $res = [
//                     'status' => 'success',
//                     'message' => 'Modelo configurado com sucesso!',
//                 ];
//                 echo json_encode($res, JSON_UNESCAPED_UNICODE);
//                 exit;
//             } else {
//                 $conexao->rollback();
//                 $conexao->close();

//                 $res = [
//                     'status' => 'erro',
//                     'message' => 'Erro ao configurar modelo!'
//                 ];
//                 echo json_encode($res, JSON_UNESCAPED_UNICODE);
//                 exit;
//             }
//         }
//     } catch (Exception $err) {
//         $res = [
//             'status' => 'erro',
//             'message' => $err->getMessage()
//         ];

//         echo json_encode($res, JSON_UNESCAPED_UNICODE);
//         $conexao->rollback();
//         $conexao->close();
//         exit;
//     }
// }


// var_dump($_SESSION);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/modelos/config_modelos.css">
    <title>ADV Conectado</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>

<div class="container_breadcrumb">
    <div class="pai_topo">

        <div class="breadcrumb">
            <a href="./modelos.php" class="breadcrumb-link">Modelos</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Configuração</span>
        </div>
    </div>
</div>




<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <section class="cadastro-modelo">

                <div class="cadastro-modelo__header">
                    <i class="fa-solid fa-display"></i>
                    <p>Informações da Landing Page</p>
                </div>

                <hr>

                <div class="cadastro-modelo__form-wrapper">

                    <form action="" method="POST" enctype="multipart/form-data"
                        id="<?php echo !empty($dados_modelo) ? 'form-atualizacao-modelo' : 'form-configuracao-modelo'; ?>">
                        <fieldset>
                            <legend>Configuração do Modelo</legend>

                            <div class="form-grid">

                                <!-- Linha: fontes e área principal -->
                                <div class="form-row">


                                    <div class="form-field">
                                        <label for="fonte1">Fonte 1 </label>
                                        <select name="fonte1" id="fonte1">
                                            <option value="">Selecione a fonte</option>
                                            <option value="Barlow" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Barlow' ? 'selected' : ''; ?> style="font-family: 'Barlow';">Barlow</option>
                                            <option value="Hind" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Hind' ? 'selected' : ''; ?> style="font-family: 'Hind';">Hind</option>
                                            <option value="IBM Plex Sans" <?php echo ($dados_modelo['fonte1'] ?? '') === 'IBM Plex Sans' ? 'selected' : ''; ?>
                                                style="font-family: 'IBM Plex Sans';">IBM Plex Sans</option>
                                            <option value="Inter" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Inter' ? 'selected' : ''; ?> style="font-family: 'Inter';">Inter</option>
                                            <option value="Lato" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Lato' ? 'selected' : ''; ?> style="font-family: 'Lato';">Lato</option>
                                            <option value="Libre Baskerville" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Libre Baskerville' ? 'selected' : ''; ?>
                                                style="font-family: 'Libre Baskerville';">Libre Baskerville</option>
                                            <option value="Merriweather" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Merriweather' ? 'selected' : ''; ?>
                                                style="font-family: 'Merriweather';">Merriweather</option>
                                            <option value="Montserrat" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Montserrat' ? 'selected' : ''; ?>
                                                style="font-family: 'Montserrat';">Montserrat</option>
                                            <option value="Nunito" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Nunito' ? 'selected' : ''; ?> style="font-family: 'Nunito';">Nunito</option>
                                            <option value="Open Sans" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Open Sans' ? 'selected' : ''; ?> style="font-family: 'Open Sans';">Open Sans
                                            </option>
                                            <option value="Playfair Display" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Playfair Display' ? 'selected' : ''; ?>
                                                style="font-family: 'Playfair Display';">Playfair Display</option>
                                            <option value="Poppins" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Poppins' ? 'selected' : ''; ?> style="font-family: 'Poppins';">
                                                Poppins</option>
                                            <option value="PT Sans" <?php echo ($dados_modelo['fonte1'] ?? '') === 'PT Sans' ? 'selected' : ''; ?> style="font-family: 'PT Sans';">PT Sans
                                            </option>
                                            <option value="Questrial" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Questrial' ? 'selected' : ''; ?>
                                                style="font-family: 'Questrial';">Questrial</option>
                                            <option value="Roboto" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Roboto' ? 'selected' : ''; ?> style="font-family: 'Roboto';">Roboto</option>
                                            <option value="Source Serif Pro" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Source Serif Pro' ? 'selected' : ''; ?>
                                                style="font-family: 'Source Serif Pro';">Source Serif Pro</option>
                                            <option value="Ubuntu" <?php echo ($dados_modelo['fonte1'] ?? '') === 'Ubuntu' ? 'selected' : ''; ?> style="font-family: 'Ubuntu';">Ubuntu</option>
                                        </select>

                                    </div>


                                    <div class="form-field">
                                        <label for="fonte2">Fonte 2</label>
                                        <?php $fonte2 = $dados_modelo['fonte2'] ?? ''; ?>

                                        <select name="fonte2" id="fonte2">
                                            <option value="">Selecione a fonte</option>

                                            <option value="Barlow" <?php echo $fonte2 === 'Barlow' ? 'selected' : ''; ?>
                                                style="font-family: 'Barlow';">Barlow</option>

                                            <option value="Hind" <?php echo $fonte2 === 'Hind' ? 'selected' : ''; ?>
                                                style="font-family: 'Hind';">Hind</option>

                                            <option value="IBM Plex Sans" <?php echo $fonte2 === 'IBM Plex Sans' ? 'selected' : ''; ?> style="font-family: 'IBM Plex Sans';">IBM Plex Sans
                                            </option>

                                            <option value="Inter" <?php echo $fonte2 === 'Inter' ? 'selected' : ''; ?>
                                                style="font-family: 'Inter';">Inter</option>

                                            <option value="Lato" <?php echo $fonte2 === 'Lato' ? 'selected' : ''; ?>
                                                style="font-family: 'Lato';">Lato</option>

                                            <option value="Libre Baskerville" <?php echo $fonte2 === 'Libre Baskerville' ? 'selected' : ''; ?> style="font-family: 'Libre Baskerville';">Libre
                                                Baskerville</option>

                                            <option value="Merriweather" <?php echo $fonte2 === 'Merriweather' ? 'selected' : ''; ?> style="font-family: 'Merriweather';">Merriweather
                                            </option>

                                            <option value="Montserrat" <?php echo $fonte2 === 'Montserrat' ? 'selected' : ''; ?> style="font-family: 'Montserrat';">Montserrat</option>

                                            <option value="Nunito" <?php echo $fonte2 === 'Nunito' ? 'selected' : ''; ?>
                                                style="font-family: 'Nunito';">Nunito</option>

                                            <option value="Open Sans" <?php echo $fonte2 === 'Open Sans' ? 'selected' : ''; ?> style="font-family: 'Open Sans';">Open Sans</option>

                                            <option value="Playfair Display" <?php echo $fonte2 === 'Playfair Display' ? 'selected' : ''; ?> style="font-family: 'Playfair Display';">Playfair
                                                Display</option>

                                            <option value="Poppins" <?php echo $fonte2 === 'Poppins' ? 'selected' : ''; ?>
                                                style="font-family: 'Poppins';">Poppins</option>

                                            <option value="PT Sans" <?php echo $fonte2 === 'PT Sans' ? 'selected' : ''; ?>
                                                style="font-family: 'PT Sans';">PT Sans</option>

                                            <option value="Questrial" <?php echo $fonte2 === 'Questrial' ? 'selected' : ''; ?> style="font-family: 'Questrial';">Questrial</option>

                                            <option value="Roboto" <?php echo $fonte2 === 'Roboto' ? 'selected' : ''; ?>
                                                style="font-family: 'Roboto';">Roboto</option>

                                            <option value="Source Serif Pro" <?php echo $fonte2 === 'Source Serif Pro' ? 'selected' : ''; ?> style="font-family: 'Source Serif Pro';">Source
                                                Serif Pro</option>

                                            <option value="Ubuntu" <?php echo $fonte2 === 'Ubuntu' ? 'selected' : ''; ?>
                                                style="font-family: 'Ubuntu';">Ubuntu</option>
                                        </select>

                                    </div>

                                    <div class="form-field" id="campo-banner">
                                        <label for="banner_arquivo">Banner (imagem) <span
                                                style="color: red;">*</span></label>

                                        <input type="file" name="banner_arquivo" id="banner_arquivo"
                                            accept=".jpg,.jpeg,.png" class="custom-file-input">

                                        <div class="custo_add_arquivo"
                                            onclick="document.getElementById('banner_arquivo').click()">
                                            <p id="nome-arquivo-banner">
                                                <?php
                                                echo !empty($dados_modelo['nome_origem_banner'])
                                                    ? htmlspecialchars($dados_modelo['nome_origem_banner'], ENT_QUOTES, 'UTF-8')
                                                    : 'Selecione o arquivo';
                                                ?>
                                            </p>
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                        </div>
                                    </div>


                                    <div class="form-field" id="campo-foto-advogado">
                                        <label for="foto_adv_arquivo">Foto do advogado <span
                                                style="color: red;">*</span></label>

                                        <input type="file" name="foto_adv_arquivo" id="foto_adv_arquivo"
                                            accept=".jpg,.jpeg,.png" class="custom-file-input">

                                        <div class="custo_add_arquivo"
                                            onclick="document.getElementById('foto_adv_arquivo').click()">
                                            <p id="nome-arquivo-foto-adv">
                                                <?php
                                                echo !empty($dados_modelo['nome_origem_ftadv'])
                                                    ? htmlspecialchars($dados_modelo['nome_origem_ftadv'], ENT_QUOTES, 'UTF-8')
                                                    : 'Selecione o arquivo';
                                                ?>
                                            </p>
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                        </div>
                                    </div>




                                </div>



                                <!-- Linha: frases inicial e secundária -->
                                <div class="form-row">


                                    <div class="form-field">
                                        <label for="area_atuacao">Área principal</label>
                                        <input type="text" name="area_atuacao" id="area_atuacao"
                                            placeholder="EX: Direito Trabalhista" maxlength="50"
                                            value="<?php echo $dados_modelo['area_atuacao_principal'] ?? ''; ?>">

                                    </div>

                                    <div class="form-field" id="container_frase_inicial">
                                        <label for="frase_inicial">Frase inicial</label>
                                        <input type="text" name="frase_inicial" id="frase_inicial"
                                            placeholder="EX: Defesa jurídica com excelência" maxlength="150"
                                            value="<?php echo htmlspecialchars($dados_modelo['frase_inicial'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    </div>

                                    <div class="form-field" id="container_frase_secundaria">
                                        <label for="frase_secundaria">Frase secundária</label>
                                        <input type="text" name="frase_secundaria" id="frase_secundaria"
                                            placeholder="EX: Atuação estratégica em diversas áreas" maxlength="150"
                                            value="<?php echo htmlspecialchars($dados_modelo['frase_secundaria'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    </div>
                                </div>

                                <!-- Linha: contato -->
                                <div class="form-row">
                                    <div class="form-field">
                                        <label for="telefone_whatsapp">WhatsApp <span
                                                style="color: red;">*</span></label>
                                        <input type="text" name="telefone_whatsapp" id="telefone_whatsapp"
                                            placeholder="(99) 99999-9999" minlength="10" maxlength="14" required
                                            value="<?php echo htmlspecialchars($dados_modelo['telefone_whatsapp'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-field">
                                        <label for="email">E-mail <span style="color: red;">*</span></label>
                                        <input type="email" name="email" id="email" placeholder="exemplo@dominio.com"
                                            maxlength="80" required
                                            value="<?php echo htmlspecialchars($dados_modelo['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-field form-field--wide" id="campo-endereco">
                                        <label for="endereco">Endereço <span style="color: red;">*</span></label>
                                        <input type="text" name="endereco" id="endereco" maxlength="200" required
                                            placeholder="Rua, número, bairro, cidade - UF"
                                            value="<?php echo htmlspecialchars($dados_modelo['endereco'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>




                                <!-- Linha: chamadas CTA -->
                                <div class="form-row">

                                    <div class="form-field form-field--wide" id="campo-cta-principal">
                                        <label for="frase_chamada_cta">Chamada principal do CTA <span
                                                style="color: red;">*</span></label>
                                        <input type="text" name="frase_chamada_cta" id="frase_chamada_cta"
                                            maxlength="150" required placeholder="EX: Agende uma consulta agora mesmo"
                                            value="<?php echo htmlspecialchars($dados_modelo['frase_chamada_cta'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-field form-field--wide" id="campo-cta-secundaria">
                                        <label for="frase_chamada_cta_secundaria">Chamada secundária do CTA <span
                                                style="color: red;">*</span></label>
                                        <input type="text" name="frase_chamada_cta_secundaria"
                                            id="frase_chamada_cta_secundaria" maxlength="150" required
                                            placeholder="EX: Atendimento online e presencial"
                                            value="<?php echo htmlspecialchars($dados_modelo['frase_chamada_cta_secundaria'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                </div>



                                <!-- Linha: sobre e áreas de atuação (campos grandes) -->
                                <div class="form-row">

                                    <div class="form-field form-field--wide" id="campo-sobre">
                                        <label for="sobre">Sobre o advogado / escritório <span
                                                style="color: red;">*</span></label>
                                        <textarea name="sobre" id="sobre" rows="3" maxlength="200" required
                                            placeholder="Breve descrição que aparecerá no site"><?php echo htmlspecialchars($dados_modelo['sobre'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>

                                    <div class="form-field form-field--wide" id="campo-areas-atuacao">
                                        <label for="areas_atuacao">Áreas de atuação (Separe por virgula, para ser um
                                            card diferente) <span style="color: red;">*</span></label>
                                        <textarea name="areas_atuacao" id="areas_atuacao" rows="3" maxlength="200"
                                            required
                                            placeholder="EX: Trabalhista, Cível, Previdenciário"><?php echo htmlspecialchars($dados_modelo['areas_atuacao'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>

                                    <input type="hidden" name="acao"
                                        value="<?php echo empty($dados_modelo) ? 'cadastrar_configuracao' : 'atualizar_configuracao'; ?>">

                                </div>


                                <!-- Linha: endereço e estilização -->
                                <div class="form-row">

                                    <div class="form-field form-field--wide" id="campo-estilizacao">
                                        <label for="estilizacao">Estilização (JSON / CSS opcional)</label>
                                        <textarea name="estilizacao" id="estilizacao" rows="3"
                                            placeholder='EX: {"cor_primaria":"#123456","layout":"modelo1"}'><?php echo htmlspecialchars($dados_modelo['estilizacao'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>

                                </div>


                            </div>
                        </fieldset>

                        <div class="cadastro-modelo__submit">
                            <button type="submit" class="btn_cadastrar">Salvar Configuração</button>
                        </div>

                    </form>

                </div>

            </section>


        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <script>
        $(function () {

            $('#telefone_whatsapp').mask('(99) 99999-9999')


            function atualizarNomeArquivo(inputId, textoId) {
                const input = document.getElementById(inputId);
                const texto = document.getElementById(textoId);

                input.addEventListener("change", function () {
                    if (this.files.length > 0) {
                        texto.textContent = this.files[0].name;
                    } else {
                        texto.textContent = "Selecione o arquivo";
                    }
                });
            }

            atualizarNomeArquivo("banner_arquivo", "nome-arquivo-banner");
            atualizarNomeArquivo("foto_adv_arquivo", "nome-arquivo-foto-adv");
        })
    </script>



    <!-- Ajax para cadastro das infos landing pages -->
    <script>
        $(document).ready(function () {

            // Validação ao submeter o formulário
            $('#form-configuracao-modelo').on('submit', function (e) {

                e.preventDefault();

                const foto_adv = $('#foto_adv_arquivo')[0].files.length;
                const banner_adv = $('#banner_arquivo')[0].files.length;

                if (banner_adv === 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Atenção",
                        text: "Você precisa selecionar um banner antes de continuar."
                    });

                    return; // impede o envio
                }


                if (foto_adv === 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Atenção",
                        text: "Você precisa selecionar a foto do advogado antes de continuar."
                    });

                    return; // impede o envio
                }



                $('.btn_cadastrar').prop('disabled', true)

                Swal.fire({
                    title: "Carregando...",
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });



                // Ajax para realizar o cadastro
                if (foto_adv != 0 && banner_adv != 0) {
                    let dados_form = new FormData(this);
                    $.ajax({
                        url: './configuracao_modelo.php',
                        type: 'POST',
                        data: dados_form,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function (res) {
                            if (res.status === 'erro') {

                                Swal.fire({
                                    icon: "error",
                                    title: "Erro",
                                    text: res.message
                                });

                                $('.btn_cadastrar').attr('disabled', false)


                            } else if (res.status === 'success') {
                                Swal.close();

                                setTimeout(() => {
                                    Swal.fire({
                                        title: "Sucesso!",
                                        text: res.message,
                                        icon: "success"
                                    }).then((result) => {
                                        window.location.reload()
                                    });
                                }, 300);
                            }


                        },
                        error: function (err) {
                            Swal.fire({
                                icon: "error",
                                title: "Erro",
                                text: err.message,
                            });
                            $('.btn_cadastrar').attr('disabled', false)
                        }
                    })
                }



            });
        })
    </script>


    <!-- Ajax para cadastro das infos landing pages -->
    <script>
        $(document).ready(function () {


            // Validação ao submeter o formulário
            $('#form-atualizacao-modelo').on('submit', function (e) {

                e.preventDefault();

                const foto_adv = $('#foto_adv_arquivo')[0].files.length;
                const banner_adv = $('#banner_arquivo')[0].files.length;

                let nome_arquivo_ftadv = $('#nome-arquivo-foto-adv').text().trim()
                let nome_arquivo_banner = $('#nome-arquivo-banner').text().trim()

                if (banner_adv === 0 && nome_arquivo_banner === 'Selecione o arquivo') {
                    Swal.fire({
                        icon: "warning",
                        title: "Atenção",
                        text: "Você precisa selecionar um banner antes de continuar."
                    });

                    return; // impede o envio
                }


                if (foto_adv === 0 && nome_arquivo_ftadv === 'Selecione o arquivo') {
                    Swal.fire({
                        icon: "warning",
                        title: "Atenção",
                        text: "Você precisa selecionar a foto do advogado antes de continuar."
                    });

                    return; // impede o envio
                }



                $('.btn_cadastrar').prop('disabled', true)

                Swal.fire({
                    title: "Carregando...",
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });


                // Ajax para realizar o cadastro
                let dados_form = new FormData(this);
                $.ajax({
                    url: './configuracao_modelo.php',
                    type: 'POST',
                    data: dados_form,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (res) {
                        if (res.status === 'erro') {

                            Swal.fire({
                                icon: "error",
                                title: "Erro",
                                text: res.message
                            });

                            $('.btn_cadastrar').attr('disabled', false)


                        } else if (res.status === 'success') {
                            Swal.close();

                            setTimeout(() => {
                                Swal.fire({
                                    title: "Sucesso!",
                                    text: res.message,
                                    icon: "success"
                                }).then((result) => {
                                    window.location.reload()
                                });
                            }, 300);
                        }


                    },
                    error: function (err) {
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            text: err.message,
                        });
                        $('.btn_cadastrar').attr('disabled', false)
                    }
                })

            });
        })
    </script>

</body>

</html>