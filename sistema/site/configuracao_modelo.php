<?php
include_once('../../scripts.php');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
}

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
                    <i class="fa-solid fa-user-plus"></i>
                    <p>Nova Pessoa</p>
                </div>

                <hr>

                <div class="cadastro-modelo__form-wrapper">

                    <form action="" method="POST" enctype="multipart/form-data" id="form-configuracao-modelo">
                        <fieldset>
                            <legend>Configuração do Modelo</legend>

                            <div class="form-grid">

                                <!-- Linha: fontes e área principal -->
                                <div class="form-row">
                                    <div class="form-field">
                                        <label for="fonte1">Fonte 1</label>
                                        <select name="fonte1" id="fonte1">
                                            <option value="">Selecione a fonte</option>
                                            <option value="Roboto" style="font-family: Roboto;">Roboto</option>
                                            <option value="Open Sans" style="font-family: 'Open Sans';">Open Sans</option>
                                            <option value="Lato" style="font-family: Lato;">Lato</option>
                                            <option value="Montserrat" style="font-family: Montserrat;">Montserrat</option>
                                        </select>
                                    </div>

                                    <div class="form-field">
                                        <label for="fonte2">Fonte 2</label>
                                        <select name="fonte2" id="fonte2">
                                            <option value="">Selecione a fonte</option>
                                            <option value="Roboto" style="font-family: Roboto;">Roboto</option>
                                            <option value="Open Sans" style="font-family: 'Open Sans';">Open Sans</option>
                                            <option value="Lato" style="font-family: Lato;">Lato</option>
                                            <option value="Montserrat" style="font-family: Montserrat;">Montserrat</option>
                                        </select>
                                    </div>

                                    <div class="form-field">
                                        <label for="area_atuacao">Área principal</label>
                                        <input
                                            type="text"
                                            name="area_atuacao"
                                            id="area_atuacao"
                                            placeholder="EX: Direito Trabalhista"
                                            maxlength="50">
                                    </div>

                                    <div class="form-field" id="campo-banner">
                                        <label for="banner_arquivo">Banner (imagem)</label>
                                        <input
                                            type="file"
                                            name="banner_arquivo"
                                            id="banner_arquivo"
                                            accept=".jpg,.jpeg,.png">
                                    </div>

                                    <div class="form-field" id="campo-foto-advogado">
                                        <label for="foto_adv_arquivo">Foto do advogado</label>
                                        <input
                                            type="file"
                                            name="foto_adv_arquivo"
                                            id="foto_adv_arquivo"
                                            accept=".jpg,.jpeg,.png">
                                    </div>
                                </div>

                        

                                <!-- Linha: frases inicial e secundária -->
                                <div class="form-row">
                                    <div class="form-field">
                                        <label for="frase_inicial">Frase inicial</label>
                                        <input
                                            type="text"
                                            name="frase_inicial"
                                            id="frase_inicial"
                                            placeholder="EX: Defesa jurídica com excelência"
                                            maxlength="150">
                                    </div>

                                    <div class="form-field">
                                        <label for="frase_secundaria">Frase secundária</label>
                                        <input
                                            type="text"
                                            name="frase_secundaria"
                                            id="frase_secundaria"
                                            placeholder="EX: Atuação estratégica em diversas áreas"
                                            maxlength="150">
                                    </div>
                                </div>

                                <!-- Linha: contato -->
                                <div class="form-row">
                                    <div class="form-field">
                                        <label for="telefone_whatsapp">WhatsApp <span style="color: red;">*</span></label>
                                        <input
                                            type="text"
                                            name="telefone_whatsapp"
                                            id="telefone_whatsapp"
                                            placeholder="(99) 99999-9999"
                                            minlength="10"
                                            maxlength="14"
                                            required>
                                    </div>

                                    <div class="form-field">
                                        <label for="email">E-mail <span style="color: red;">*</span></label>
                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            placeholder="exemplo@dominio.com"
                                            maxlength="80"
                                            required>
                                    </div>
                                </div>

                                <!-- Linha: sobre e áreas de atuação (campos grandes) -->
                                <div class="form-row">
                                    <div class="form-field form-field--wide" id="campo-sobre">
                                        <label for="sobre">Sobre o advogado / escritório <span style="color: red;">*</span></label>
                                        <textarea
                                            name="sobre"
                                            id="sobre"
                                            rows="4"
                                            maxlength="200"
                                            required
                                            placeholder="Breve descrição que aparecerá no site"></textarea>
                                    </div>

                                    <div class="form-field form-field--wide" id="campo-areas-atuacao">
                                        <label for="areas_atuacao">Áreas de atuação (resumo) <span style="color: red;">*</span></label>
                                        <textarea
                                            name="areas_atuacao"
                                            id="areas_atuacao"
                                            rows="3"
                                            maxlength="200"
                                            required
                                            placeholder="EX: Trabalhista, Cível, Previdenciário"></textarea>
                                    </div>
                                </div>

                                <!-- Linha: chamadas CTA -->
                                <div class="form-row">
                                    <div class="form-field form-field--wide" id="campo-cta-principal">
                                        <label for="frase_chamada_cta">Chamada principal do CTA <span style="color: red;">*</span></label>
                                        <input
                                            type="text"
                                            name="frase_chamada_cta"
                                            id="frase_chamada_cta"
                                            maxlength="150"
                                            required
                                            placeholder="EX: Agende uma consulta agora mesmo">
                                    </div>

                                    <div class="form-field form-field--wide" id="campo-cta-secundaria">
                                        <label for="frase_chamada_cta_secundaria">Chamada secundária do CTA <span style="color: red;">*</span></label>
                                        <input
                                            type="text"
                                            name="frase_chamada_cta_secundaria"
                                            id="frase_chamada_cta_secundaria"
                                            maxlength="150"
                                            required
                                            placeholder="EX: Atendimento online e presencial">
                                    </div>
                                </div>

                                <!-- Linha: endereço e estilização -->
                                <div class="form-row">
                                    <div class="form-field form-field--wide" id="campo-endereco">
                                        <label for="endereco">Endereço <span style="color: red;">*</span></label>
                                        <textarea
                                            name="endereco"
                                            id="endereco"
                                            rows="3"
                                            maxlength="200"
                                            required
                                            placeholder="Rua, número, bairro, cidade - UF"></textarea>
                                    </div>

                                    <div class="form-field form-field--wide" id="campo-estilizacao">
                                        <label for="estilizacao">Estilização (JSON / CSS opcional)</label>
                                        <textarea
                                            name="estilizacao"
                                            id="estilizacao"
                                            rows="4"
                                            placeholder='EX: {"cor_primaria":"#123456","layout":"modelo1"}'></textarea>
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




</body>

</html>