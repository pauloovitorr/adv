<?php include_once('../../scripts.php');

$id_user = $_SESSION['cod'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sql_verifica_primeiro_acesso = "SELECT primeiro_acesso from usuario_config where id_usuario_config = $id_user";
    $res = $conexao->query($sql_verifica_primeiro_acesso);
    $res = $res->fetch_assoc();

    if ($res["primeiro_acesso"] == 'sim') {

        $etapas_padrao_crm = [
            "Análise do Caso",
            "Negociação",
            "Aguardando Documentos",
            "Proposta",
            "Ação Protocolada",
            "Aguardando Audiência",
            "Aguardando Julgamento",
            "Desenvolvendo Recurso",
            "Fechamento"
        ];

        try {
            $conexao->begin_transaction();

            foreach ($etapas_padrao_crm as $indice => $etapa) {
                $ordem = $indice + 1;
                $sql_insert_etapas = "INSERT INTO etapas_crm (ordem, nome, usuario_config_id_usuario_config) 
                              VALUES ($ordem, '{$conexao->real_escape_string($etapa)}', $id_user)";

                if (!$conexao->query($sql_insert_etapas)) {
                    throw new Exception("Erro ao inserir etapa: " . $conexao->error);
                }
            }

            $sql_remove_primeiro_acesso = "UPDATE usuario_config 
                                   SET primeiro_acesso = 'nao' 
                                   WHERE id_usuario_config = $id_user";

            if (!$conexao->query($sql_remove_primeiro_acesso)) {
                throw new Exception("Erro ao atualizar primeiro acesso: " . $conexao->error);
            }

            $conexao->commit();
        } catch (Exception $erro) {
            $conexao->rollback();
            echo "Falha: " . $erro->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- <link rel="stylesheet" href="./css/geral.css"> -->
</head>

<?php
include_once('./menu_lat.php');
include_once('./topo.php');
?>

<body>
    <main class="container_principal">
        <h1>Desenvolvimento</h1>
    </main>
</body>

</html>