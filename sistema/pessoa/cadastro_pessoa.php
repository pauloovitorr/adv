<?php

include_once('../../scripts.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['tipo_pessoa']) && !empty($_POST['nome']) && !empty($_POST['origem']) && !empty($_POST['tipo_parte'])) {


    $token          = bin2hex(random_bytes(64 / 2));
    $nome           = $conexao->escape_string(htmlspecialchars($_POST['nome']));
    $origem         = $conexao->escape_string(htmlspecialchars($_POST['origem']));
    $foto_pessoa    = '';
    $num_doc        = $conexao->escape_string(htmlspecialchars($_POST['num_doc']));
    $rg             = $conexao->escape_string(htmlspecialchars($_POST['rg']));
    $dt_nascimento  = $dt_nascimento = !empty($_POST['dt_nascimento']) ? 
    $conexao->escape_string(htmlspecialchars($_POST['dt_nascimento'])) : null;
    $estado_civil   = $conexao->escape_string(htmlspecialchars($_POST['estado_civil']));
    $profissao      = $conexao->escape_string(htmlspecialchars($_POST['profissao']));
    $pis            = $conexao->escape_string(htmlspecialchars($_POST['pis']));
    $ctps           = $conexao->escape_string(htmlspecialchars($_POST['ctps']));
    $sexo           = $conexao->escape_string(htmlspecialchars($_POST['sexo']));
    $tell_principal = $conexao->escape_string(htmlspecialchars($_POST['tell_principal']));
    $tell_secundario = $conexao->escape_string(htmlspecialchars($_POST['tell_secundario']));
    $celular        = $conexao->escape_string(htmlspecialchars($_POST['celular']));
    $email          = $conexao->escape_string(htmlspecialchars($_POST['e-mail']));
    $email_secundario = $conexao->escape_string(htmlspecialchars($_POST['e-mail_secundario']));
    $cep            = $conexao->escape_string(htmlspecialchars($_POST['cep']));
    $estado         = $conexao->escape_string(htmlspecialchars($_POST['estado']));
    $cidade         = $conexao->escape_string(htmlspecialchars($_POST['cidade']));
    $bairro         = $conexao->escape_string(htmlspecialchars($_POST['bairro']));
    $logradouro     = $conexao->escape_string(htmlspecialchars($_POST['logradouro']));
    $num            = $_POST['num'] ? $conexao->escape_string(htmlspecialchars($_POST['num'])) : null;
    $complemento    = $conexao->escape_string(htmlspecialchars($_POST['complemento']));
    $observacao     = $conexao->escape_string(htmlspecialchars($_POST['observacao']));
    $nome_mae       = $conexao->escape_string(htmlspecialchars($_POST['nome_mae']));

    $tipo_pessoa    = $conexao->escape_string(htmlspecialchars($_POST['tipo_pessoa']));
    $tipo_parte     = $conexao->escape_string(htmlspecialchars($_POST['tipo_parte']));
    $usuario        = $_SESSION['cod'];


    try {

        $conexao->begin_transaction();

        $foto = isset($_FILES['foto']) ? $_FILES['foto'] : '';

        if ($foto['name']) {
            $nomeArquivo = $foto['name'];
            $tmpArquivo = $foto['tmp_name'];
            $tamanhoArquivo = $foto['size'];

            $extensao_arquivo = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

            $novo_nome_arquivo = uniqid() . uniqid() . '.' . $extensao_arquivo;

            if ($tamanhoArquivo > 3 * 1024 * 1024) {

                $res = [
                    'status' => 'erro',
                    'message' => 'Arquivo muito grande! Tamanho máximo permitido de 2MB'
                ];

                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();

                exit;
            } elseif ($foto['error'] !== 0) {
                $res = [
                    'status' => 'erro',
                    'message' => 'Imagem com erro'
                ];

                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();

                exit;
            } else {
                $caminho = '../../img/img_clientes';

                $novo_caminho = $caminho . '/' . $novo_nome_arquivo;

                $retorno_img_movida =   move_uploaded_file($tmpArquivo, $novo_caminho);

                if ($retorno_img_movida) {
                    $foto_pessoa = '/img/img_clientes/' . $novo_nome_arquivo;
                }
            }
        }


        $sql = 'INSERT INTO pessoas (
        tk, nome, origem, dt_cadastro_pessoa, dt_atualizacao_pessoa, foto_pessoa, num_documento, rg, dt_nascimento, 
        estado_civil, profissao, pis, ctps, sexo, telefone_principal, telefone_secundario, celular, email, 
        email_secundario, cep, estado, cidade, bairro, logradouro, numero_casa, complemento, observacao, 
        nome_mae, tipo_pessoa, tipo_parte ,usuario_config_id_usuario_config
    ) VALUES (
        ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )';

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('ssssssssssssssssssssssisssssi', $token, $nome, $origem, $foto_pessoa, $num_doc, $rg, $dt_nascimento, $estado_civil, $profissao, $pis, $ctps, $sexo, $tell_principal, $tell_secundario, $celular, $email, $email_secundario, $cep, $estado, $cidade, $bairro, $logradouro, $num, $complemento, $observacao, $nome_mae, $tipo_pessoa, $tipo_parte, $usuario);

        if ($stmt->execute()) {

            $ip = $_SERVER['REMOTE_ADDR'];
            $id_user = $_SESSION['cod'];

            // $sql_insert_log = "INSERT INTO log (acao_log, ip_log, dt_acao_log, usuario_config_id_usuario_config) VALUES ('Cadastrou Pessoa', ?, NOW(), ? ) ";

            // $stmt = $conexao->prepare($sql_insert_log);
            // $stmt->bind_param('si', $ip, $id);

            if (cadastro_log('Cadastrou Pessoa', $nome, $ip, $id_user)) {

                $conexao->commit();
                $conexao->close();

                $res = [
                    'status' => 'success',
                    'message' => 'Pessoa cadastrada com sucesso!',
                    'token' => $token
                ];
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $conexao->rollback();
                $conexao->close();

                $res = [
                    'status' => 'erro',
                    'message' => 'Erro ao cadastrar pessoa!'
                ];
            }
        }
    } catch (Exception $err) {
        echo "Erro: " . $err->getMessage();
        $conexao->rollback();
        $conexao->close();
    }
}

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pessoas/cadastro_pessoa.css">
    <title>Cadastro</title>
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
                    <div class="num">2º</div>
                    <div class="descricao">Documentos</div>
                </div>

                <div class="separador"></div>

                <div class="etapa">
                    <div class="num">3º</div>
                    <div class="descricao">Finalização</div>
                </div>

            </section>

            <section class="container_cadastro">
                <div class="topo_sessao">
                    <i class="fa-solid fa-user-plus"></i>
                    <p>Nova Pessoa</p>
                </div>

                <hr>

                <div class="container_field_form">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <fieldset>
                            <legend>Dados Pessoais</legend>

                            <div class="bloco-formulario">



                                <div class="container_inputs">
                                    <div class="container_input">
                                        <label for="pessoa">Pessoa <span style="color: red;">*</span></label>
                                        <select name="tipo_pessoa" id="pessoa" required>
                                            <option value="PF">Pessoa Física</option>
                                            <option value="PJ">Pessoa Jurídica</option>
                                        </select>
                                    </div>

                                    <div class="container_input" id="nome">
                                        <label for="nome_pessoa">Nome <span style="color: red;">*</span></label>
                                        <input type="text" name="nome" id="nome_pessoa" placeholder="EX: Paulo Vitor" minlength="4" maxlength="150" required>
                                    </div>

                                    <div class="container_input">
                                        <label for="num_doc">CPF/CNPJ</label>
                                        <input type="text" name="num_doc" id="num_doc" minlength="11" maxlength="20" placeholder="999.999.99-99">
                                    </div>

                                    <div class="container_input" id="container_rg">
                                        <label for="rg">RG</label>
                                        <input type="text" name="rg" id="rg" placeholder="Número do RG" minlength="5" maxlength="25">
                                    </div>


                                </div>

                                <div class="container_inputs">
                                    <div class="container_input" id="container_dt_nascimento">
                                        <label for="dt_nascimento">Data de nascimento</label>
                                        <input type="date" name="dt_nascimento" id="dt_nascimento">
                                    </div>

                                    <div class="container_input">
                                        <label for="profissao">Profissão</label>
                                        <input type="text" name="profissao" id="profissao" minlength="4" maxlength="40" placeholder="EX: Autônomo">
                                    </div>

                                    <div class="container_input" id="container_ctps">
                                        <label for="ctps">CTPS</label>
                                        <input type="text" name="ctps" id="ctps" minlength="4" maxlength="40" placeholder="Carteira de trabalho">
                                    </div>

                                    <div class="container_input" id="container_pis">
                                        <label for="pis">PIS/PASEP</label>
                                        <input type="text" name="pis" id="pis" minlength="11" maxlength="14" placeholder="999.9999.999-9">
                                    </div>

                                    <div class="container_input">
                                        <label for="origem">Origem <span style="color: red;">*</span></label>
                                        <select name="origem" name="origem" id="origem" required>
                                            <option value="">Selecione a origem</option>
                                            <option value="Escritório">Escritório</option>
                                            <option value="Indicação">Indicação</option>
                                            <option value="Anúncio">Anúncio</option>
                                            <option value="Facebook">Facebook</option>
                                        </select>
                                    </div>


                                </div>

                                <div class="container_inputs">
                                    <div class="container_input" id="container_sexo">
                                        <label for="sexo">Sexo</label>
                                        <select name="sexo" id="sexo">
                                            <option value="">Selecione o sexo</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Feminino">Feminino</option>
                                        </select>
                                    </div>

                                    <div class="container_input" id="container_estado_civil">
                                        <label for="estado_civil">Estado civil</label>
                                        <select name="estado_civil" id="estado_civil">
                                            <option value="">Selecione o estado civil</option>
                                            <option value="Casado(a)">Casado(a)</option>
                                            <option value="Divorciado(a)">Divorciado(a)</option>
                                            <option value="Separado(a)">Separado(a)</option>
                                            <option value="Solteiro(a)">Solteiro(a)</option>
                                            <option value="União Estável">União Estável</option>
                                        </select>
                                    </div>

                                    <div class="container_input" id="container_nome_mae">
                                        <label for="nome_mae">Nome da mãe</label>
                                        <input type="text" name="nome_mae" id="nome_mae" minlength="4" maxlength="150" placeholder="EX: Eliete de Sousa">
                                    </div>

                                    <div class="container_input">
                                        <label for="foto">Foto</label>
                                        <input type="file" name="foto" accept=".jpg,.jpeg,.png" id="foto" class="custom-file-input">
                                        <div class="custo_add_arquivo" onclick="document.getElementById('foto').click()">
                                            <p id="nome-arquivo">Selecione o arquivo</p>
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                        </div>
                                    </div>



                                </div>

                                <div class="container_tipo_parte">

                                    <div class="container_tipo_parte_inputs">

                                        <div>
                                            <label for="tipo_parte_cliente">Cliente</label>
                                            <input type="radio" id="tipo_parte_cliente" value="cliente" name="tipo_parte" checked>
                                        </div>

                                        <div>
                                            <label for="tipo_parte_contrario">Contrário</label>
                                            <input type="radio" id="tipo_parte_contrario" value="contrário" name="tipo_parte">
                                        </div>

                                    </div>

                                </div>


                            </div>

                        </fieldset>

                        <fieldset>
                            <legend>Contato</legend>

                            <div class="bloco-formulario">

                                <div class="container_inputs">

                                    <div class="container_input">
                                        <label for="tell_principal">Telefone principal</label>
                                        <input type="tell" name="tell_principal" id="tell_principal" minlength="13" maxlength="14" placeholder="(99) 99999-9999">
                                    </div>

                                    <div class="container_input">
                                        <label for="tell_secundario">Telefone secundário</label>
                                        <input type="tell" name="tell_secundario" id="tell_secundario" minlength="13" maxlength="14" placeholder="(99) 99999-9999">
                                    </div>

                                    <div class="container_input">
                                        <label for="celular">Telefone Fixo</label>
                                        <input type="tell" name="celular" id="celular" minlength="13" maxlength="14" placeholder="(99) 9999-9999">
                                    </div>

                                    <div class="container_input">
                                        <label for="e-mail">E-mail principal</label>
                                        <input type="email" name="e-mail" id="e-mail" minlength="7" maxlength="100" placeholder="Ex: paulo@gmail.com">
                                    </div>

                                    <div class="container_input">
                                        <label for="e-mail_secundario">E-mail secundário</label>
                                        <input type="email" name="e-mail_secundario" id="e-mail_secundario" minlength="7" maxlength="100" placeholder="Ex: paulo@gmail.com">
                                    </div>


                                </div>

                            </div>

                        </fieldset>



                        <fieldset>
                            <legend>Endereço</legend>

                            <div class="bloco-formulario">

                                <div class="container_inputs">
                                    <div class="container_input">
                                        <label for="cep">CEP</label>
                                        <input type="text" name="cep" id="cep" minlength="8" maxlength="9" placeholder="99999-999">
                                    </div>

                                    <div class="container_input" id="logradouro_container">
                                        <label for="logradouro">Logradouro</label>
                                        <input type="text" name="logradouro" id="logradouro" minlength="4" maxlength="150" placeholder="EX: Rua João Goulart">
                                    </div>

                                    <div class="container_input">
                                        <label for="num">Número</label>
                                        <input type="text" name="num" id="num" minlength="1" maxlength="6" placeholder="99">
                                    </div>

                                    <div class="container_input">
                                        <label for="bairro">Bairro</label>
                                        <input type="text" name="bairro" id="bairro" minlength="3" maxlength="100" placeholder="Ex: Centro">
                                    </div>




                                </div>

                            </div>

                            <div class="bloco-formulario">

                                <div class="container_inputs">

                                    <div class="container_input">
                                        <label for="cidade">Cidade</label>
                                        <input type="text" name="cidade" id="cidade" minlength="3" maxlength="150" placeholder="Ex: São Paulo">
                                    </div>

                                    <div class="container_input">
                                        <label for="estado">Estado</label>
                                        <select id="estado" name="estado">
                                            <option value="">Selecione um estado</option>
                                            <option value="AC">Acre</option>
                                            <option value="AL">Alagoas</option>
                                            <option value="AM">Amazonas</option>
                                            <option value="AP">Amapá</option>
                                            <option value="BA">Bahia</option>
                                            <option value="CE">Ceará</option>
                                            <option value="DF">Distrito Federal</option>
                                            <option value="ES">Espírito Santo</option>
                                            <option value="GO">Goiás</option>
                                            <option value="MA">Maranhão</option>
                                            <option value="MG">Minas Gerais</option>
                                            <option value="MS">Mato Grosso do Sul</option>
                                            <option value="MT">Mato Grosso</option>
                                            <option value="PA">Pará</option>
                                            <option value="PB">Paraíba</option>
                                            <option value="PE">Pernambuco</option>
                                            <option value="PI">Piauí</option>
                                            <option value="PR">Paraná</option>
                                            <option value="RJ">Rio de Janeiro</option>
                                            <option value="RN">Rio Grande do Norte</option>
                                            <option value="RO">Rondônia</option>
                                            <option value="RR">Roraima</option>
                                            <option value="RS">Rio Grande do Sul</option>
                                            <option value="SC">Santa Catarina</option>
                                            <option value="SE">Sergipe</option>
                                            <option value="SP">São Paulo</option>
                                            <option value="TO">Tocantins</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="complemento">Complemento</label>
                                        <input type="text" name="complemento" id="complemento" minlength="3" maxlength="150" placeholder="Ex: Próximo ao mercado">
                                    </div>

                                    <div class="container_input" id="observacao_container">
                                        <label for="observacao">Observação</label>
                                        <input type="text" name="observacao" id="observacao" minlength="3" maxlength="150" placeholder="EX: Visitas apenas pela manhã">
                                    </div>


                                </div>

                            </div>

                        </fieldset>


                        <div class="container_btn_submit">
                            <button type="submit" class="btn_cadastrar"> Cadastrar Pessoa </button>
                        </div>


                    </form>


                </div>



            </section>


        </div>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {

            // mascaras campos

            // mascara nome e nome mae
            $('#nome_pessoa').on('input', function() {
                const valor = $(this).val();
                // Filtrar apenas letras e acentos
                const valorFiltrado = valor.replace(/[^a-zA-ZÀ-ÖÙ-öù-ÿ\s]/g, '');
                $(this).val(valorFiltrado);
            });

            $('#nome_mae').on('input', function() {
                const valor = $(this).val();
                // Filtrar apenas letras e acentos
                const valor_digitado = valor.replace(/[^a-zA-ZÀ-ÖÙ-öù-ÿ\s]/g, '');
                $(this).val(valor_digitado);
            });

            $('#cidade').on('input', function() {
                const valor = $(this).val();
                // Filtrar apenas letras e acentos
                const valor_digitado = valor.replace(/[^a-zA-ZÀ-ÖÙ-öù-ÿ\s]/g, '');
                $(this).val(valor_digitado);
            });

            var CpfCnpjMaskBehavior = function(val) {
                var len = val.replace(/\D/g, '').length;
                if (len <= 11) {
                    return '000.000.000-009';
                }
                return '00.000.000/0000-00';
            };

            // CPF/CNPJ
            var cpfCnpjpOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
                },
                onComplete: function(val, e, field, options) {
                    var len = val.replace(/\D/g, '').length;
                    if (len === 11) {
                        $(field).mask('000.000.000-00');
                    } else if (len === 14) {
                        $(field).mask('00.000.000/0000-00');
                    }
                }
            };

            $('#num_doc').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);

            // Remove a máscara antes de enviar o formulário (opcional)
            // $('form').on('submit', function() {
            //     var documento = $('#num_doc').val();
            //     $('#num_doc').val(documento.replace(/[^\d]+/g, ''));
            // });


            // data de nascimento
            const hoje = new Date(); // Data atual
            const ano = hoje.getFullYear();
            const mes = String(hoje.getMonth() + 1).padStart(2, '0'); // Mês atual (ajustado para 0-11)
            const dia = String(hoje.getDate()).padStart(2, '0'); // Dia atual

            const dataMaxima = `${ano}-${mes}-${dia}`;

            // Define o atributo 'max' no campo de data
            $('#dt_nascimento').attr('max', dataMaxima);

            // pis
            $('#pis').mask('999.9999.999-9');

            // valida foto
            // Valida foto
            $('#foto').on('change', function() {
                const file = this.files[0]; // Obtém o primeiro arquivo selecionado
                const fileName = file.name.toLowerCase(); // Nome do arquivo em minúsculas
                const allowedExtensions = ['.jpg', '.jpeg', '.png']; // Extensões permitidas

                // Verifica se a extensão do arquivo está na lista permitida
                const isValid = allowedExtensions.some(ext => fileName.endsWith(ext));

                if (!isValid) {
                    Swal.fire({
                        icon: "error",
                        title: "Arquivo Inválido",
                        text: "Por favor, selecione um arquivo de imagem válido (JPG, JPEG, PNG).",
                    });
                    $(this).val(''); // Reseta o campo de input
                    $('#nome-arquivo').text('Selecione o arquivo'); // Volta para o texto padrão
                    $('.custo_add_arquivo').css('color', 'rgb(153, 153, 153)')
                } else {
                    // Atualiza o texto com o nome do arquivo selecionado
                    $('#nome-arquivo').text(file.name);
                    $('.custo_add_arquivo').css('color', '#141414')
                }
            });


            // telefones
            $('#tell_principal').mask('(99) 99999-9999')
            $('#tell_secundario').mask('(99) 99999-9999')
            $('#celular').mask('(99) 9999-9999')
            $('#cep').mask('99999-999')

            // num casa
            $('#num').on('input', function() {
                // Permite apenas números
                this.value = this.value.replace(/\D/g, '');

                // Limita a 6 caracteres
                if (this.value.length > 6) {
                    this.value = this.value.slice(0, 6);
                }
            });

        });
    </script>


    <!-- Js para quando for PJ -->
    <script>
        $(document).ready(function() {

            $('#pessoa').on('change', function() {
                // PJ
                if (this.value === 'PJ') {
                    $('#container_rg').hide()
                    $('#container_dt_nascimento').hide()
                    $('#container_ctps').hide()
                    $('#container_pis').hide()
                    $('#container_sexo').hide()
                    $('#container_estado_civil').hide()
                    $('#container_nome_mae').hide()
                } else {
                    $('#container_rg').show()
                    $('#container_dt_nascimento').show()
                    $('#container_ctps').show()
                    $('#container_pis').show()
                    $('#container_sexo').show()
                    $('#container_estado_civil').show()
                    $('#container_nome_mae').show()
                }


            })

        })
    </script>



    <!-- Ajax para cadastro de pessoa -->
    <script>
        $(document).ready(function() {
            // Validação ao submeter o formulário
            $('form').on('submit', function(e) {

                $('.btn_cadastrar').attr('disabled', true)

                Swal.fire({
                    title: "Carregando...",
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                e.preventDefault();


                // Ajax para realizar o cadastro
                let dados_form = new FormData(this);
                $.ajax({
                    url: 'cadastro_pessoa.php',
                    type: 'POST',
                    data: dados_form,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(res) {
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
                                    window.location.href = "./docs_pessoa.php?tkn=" + res.token;
                                });
                            }, 300);
                        }


                    },
                    error: function(err) {
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            text: err.message,
                        });
                    }
                })



            });
        })
    </script>


    <!-- Busca do cep -->
    <script>
        $(document).ready(function() {
            let cep = document.querySelector('#cep')
            let pai = document.querySelector('.pai')

            cep.addEventListener('blur', function() {
                let pesquisar = cep.value

                if (pesquisar !== '') {

                    if (pesquisar.length === 9) {
                        fetch(`https://viacep.com.br/ws/${pesquisar}/json/`)

                            .then((res) => {
                                return res.json()
                            })

                            .then((dados) => {


                                if (dados.hasOwnProperty('erro')) {
                                    Swal.fire({
                                        icon: "error",
                                        title: "CEP Não Encontrado",
                                        text: "Adicione o CEP novamente!",

                                    });
                                } else {
                                    
                                    $('#logradouro').val(dados.logradouro)
                                    $('#bairro').val(dados.bairro)
                                    $('#cidade').val(dados.localidade)
                                    $('#estado').val(dados.uf)
                                    $('#complemento').val(dados.complemento)
                                }


                            })

                            .catch((e) => {
                                console.log('Deu erro: ' + e, message)
                            })
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "CEP Inválido",
                            text: "Digite o CEP Completo",

                        });
                    }
                }

            })
        })
    </script>

</body>

</html>