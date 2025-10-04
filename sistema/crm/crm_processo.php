<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sql_busca_etapas_crm = "SELECT * FROM etapas_crm WHERE usuario_config_id_usuario_config = $id_user ORDER BY ordem ASC";
    $etapas = $conexao->query($sql_busca_etapas_crm);


    $lista_etapas_kanban = [];
    while ($row = $etapas->fetch_assoc()) {
        $lista_etapas_kanban[] = $row;
    }

    // $conexao->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nova_etapa'])) {
    $noma_etapa = $conexao->escape_string(htmlspecialchars($_POST['nova_etapa']));

    $ultima_etapa = "SELECT ordem FROM etapas_crm WHERE usuario_config_id_usuario_config = $id_user ORDER BY 'ordem' DESC LIMIT 1";
    $ultimo = $conexao->query($ultima_etapa);
    $ultimo = $ultimo->fetch_assoc();

    $ordem_nova_etapa = intval($ultimo['ordem']) + 1;
    $sql_cadastra_etapa = "INSERT INTO etapas_crm (ordem,nome, usuario_config_id_usuario_config) 
    VALUES ($ordem_nova_etapa, '$noma_etapa', $id_user)";

    if ($conexao->query($sql_cadastra_etapa)) {
        $res = [
            'status' => 'success',
            'message' => 'Etapa cadastrada com sucesso!',
            'data' => [
                'id' => $conexao->insert_id,
                'nome' => $noma_etapa
            ]
        ];
    } else {
        $res = [
            'status' => 'erro',
            'message' => 'Etapa cadastrada com sucesso!',
        ];
    }


    echo json_encode($res, JSON_UNESCAPED_UNICODE);

    $conexao->close();
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['ordem'] as $indice => $valor) {

        $ordem = $indice + 1;
        $sql_atualiza_ordem = "UPDATE etapas_crm SET ordem = $ordem where id_etapas_crm = $valor ";
        if ($conexao->query($sql_atualiza_ordem)) {
            echo json_encode(['status' => 'sucesso']);
        }
    }

    $conexao->close();
    exit;
}




// Função para mapear contingenciamento para classe CSS
function getBadgeClass($contingenciamento)
{
    $contingenciamento = strtolower($contingenciamento);

    if (strpos($contingenciamento, 'provável') !== false) {
        return 'low';
    } elseif (strpos($contingenciamento, 'possível') !== false) {
        return 'medium';
    } elseif (strpos($contingenciamento, 'remota') !== false) {
        return 'high';
    } else {
        return 'medium'; // padrão
    }
}


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/kanban/crm.css">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

    <title>ADV Conectado</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>
<div class="container_breadcrumb">
    <div class="pai_topo">
        <div class="breadcrumb">
            <span class="breadcrumb-current">CRM</span>
            <span class="breadcrumb-separator">/</span>
        </div>
    </div>
</div>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <div class="topo_kanban">
                <h1>Gestão CRM</h1>
                <button id="config_crm"><i class="fa-solid fa-gear"></i> Configurar CRM</button>
            </div>

            <div class="kanban">
                <?php foreach ($lista_etapas_kanban as $index => $etapa): ?>

                    <?php
                    $id_etapa =  $etapa['id_etapas_crm'];

                    $sql_busca_processo_etapa = "SELECT 
                                p.id_processo,
                                p.tk,
                                p.grupo_acao,
                                p.tipo_acao,
                                p.referencia,
                                p.valor_causa,
                                p.valor_honorarios,
                                p.etapa_kanban,
                                p.contingenciamento,                         

                                -- Dados do cliente
                                c.id_pessoa   AS cliente_id,
                                c.nome        AS cliente_nome,
                                c.foto_pessoa AS cliente_foto

                            FROM processo p
                            LEFT JOIN pessoas c     ON p.cliente_id         = c.id_pessoa
                            where p.etapa_kanban = $id_etapa and p.usuario_config_id_usuario_config = $id_user";

                    $cards_etapa = $conexao->query($sql_busca_processo_etapa);



                    ?>

                    <div class="kanban-column" data-id="<?php echo $index + 1; ?>">
                        <h2><?php echo $etapa['ordem'] . ' - ' . $etapa['nome']; ?></h2>
                        <div class="kanban-cards" id="column<?php echo $index + 1; ?>">

                            <?php while ($card = $cards_etapa->fetch_assoc()):
                                $badgeClass = getBadgeClass($card['contingenciamento']);
                            ?>

                                <div class="kanban-card" data-id="<?php echo $card['id_processo']; ?>">
                                    <div class="header_card">
                                        <p class="ref_card">ref: <?php echo $card['referencia']; ?></p>
                                        <div class="badge <?php echo $badgeClass; ?>">
                                            <span><?php echo ucfirst($card['contingenciamento']); ?></span>
                                        </div>
                                    </div>

                                    <div class="container_card">
                                        <div class="dados_processo">
                                            <p class="tipo_acao"><?php echo $card['tipo_acao']; ?></p>
                                            <p class="grupo_acao"><?php echo $card['grupo_acao']; ?></p>
                                        </div>

                                        <div class="dados_cliente">
                                            <p class="card-subtitle"><?php echo $card['cliente_nome']; ?></p>
                                            <img src="../..<?php echo $card['cliente_foto']; ?>" alt="" srcset="">
                                        </div>

                                    </div>

                                    <div class="card-footer">
                                        <?php if (!empty($card['valor_causa'])): ?>
                                            <span>Causa: <?php echo $card['valor_causa']; ?></span>
                                        <?php endif; ?>

                                        <?php if (!empty($card['valor_honorarios'])): ?>
                                            <span>Comissão: <?php echo $card['valor_honorarios']; ?></span>
                                        <?php endif; ?>
                                    </div>

                                    
                                </div>



                            <?php endwhile; ?>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


        </div>
    </main>




    <script>
        $(document).ready(function() {
            $("#config_crm").on("click", function() {
                Swal.fire({
                    title: 'Configuração do CRM',
                    html: `
              <div class="crm-config">
                <!-- Lista de etapas -->
                <div class="crm-steps">
                  <h3>Etapas CRM</h3>
                  <span>Arraste para ordenar as etapas. A lista está na ordem atual.</span>
                  <table>
                    <thead>
                      <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                      </tr>
                    </thead>

                    <tbody id="sortable-steps">
                        <?php foreach ($lista_etapas_kanban as $etapa): ?>
                            <tr class="linha_etapa" data-id="<?php echo $etapa['id_etapas_crm'] ?>">
                                <td><?php echo $etapa['nome'] ?></td>
                                <td>
                                    <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                  </table>
                </div>

                <!-- Adicionar nova etapa -->
                <div class="crm-add-step">
                  <h3>Adicionar Etapa</h3>
                  <form id="form-add-etapa">
                    <label for="etapa-nome">Nome</label>
                    <input type="text" id="etapa-nome" placeholder="Digite o nome da etapa">
                    <button type="submit" class="add-btn">
                      <i class="fa-solid fa-plus"></i> Adicionar
                    </button>
                  </form>
                </div>
              </div>
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Salvar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: " #3085d6",
                    cancelButtonColor: "#d33",
                    didOpen: () => {
                        // Inicializa sortable apenas depois do SweetAlert abrir
                        new Sortable(document.getElementById('sortable-steps'), {
                            animation: 150,
                            ghostClass: 'drag-highlight'
                        });

                        $('.delete').on('click', function() {
                            let id_etapa = $(this).closest('.linha_etapa').attr('data-id')
                        })

                        // Captura envio do formulário dentro do Swal
                        $("#form-add-etapa").on("submit", function(e) {
                            e.preventDefault();
                            const nome = $("#etapa-nome").val();

                            if (nome.trim() !== "") {
                                $("#etapa-nome").val("");
                            }

                            $.ajax({
                                url: './crm_processo.php',
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    nova_etapa: nome
                                },
                                success: function(resposta) {

                                    if (resposta.status == 'success') {
                                        $("#sortable-steps").append(`
                                    <tr class="linha_etapa" data-id="${resposta.data.id}">
                                        <td>${nome}</td>
                                        <td>
                                            <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                `);
                                    }


                                }
                            })

                        });

                    },
                    preConfirm: () => {

                        const ordemAtual = [...document.querySelectorAll("#sortable-steps tr")]
                            .map(el => el.dataset.id);

                        $.ajax({
                            url: './crm_processo.php',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                ordem: ordemAtual
                            },
                            success: function(resposta) {
                                console.log("Servidor respondeu:", resposta);
                            }
                        })


                        // coleta dados da tabela
                        const etapas = [];
                        $("#sortable-steps tr").each(function() {
                            const nome = $(this).find("td:nth-child(2)").text();
                            etapas.push(nome);
                        });
                        return {
                            etapas
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Configuração salva!', '', 'success');
                        setTimeout(() => {
                            window.location.reload()
                        }, 1000)
                    }
                });
            });
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Sortable para cada coluna
            const columns = document.querySelectorAll('.kanban-cards');

            columns.forEach(column => {
                new Sortable(column, {
                    group: 'kanban', // Define um grupo para permitir arrastar entre colunas
                    animation: 150, // Duração da animação em ms
                    ghostClass: 'sortable-ghost', // Classe para o elemento fantasma
                    chosenClass: 'sortable-chosen', // Classe para o elemento escolhido
                    dragClass: 'sortable-drag', // Classe para o elemento sendo arrastado

                    // Evento chamado quando um elemento é movido
                    onEnd: function(evt) {
                        // Aqui você pode adicionar lógica para salvar o estado
                        // Por exemplo, enviar para um backend ou salvar no localStorage
                        // saveKanbanState();
                    }
                });
            });

            // Função para salvar o estado do Kanban
            function saveKanbanState() {
                const kanbanState = {};

                columns.forEach(column => {
                    const columnId = column.id;
                    const cards = [];

                    column.querySelectorAll('.kanban-card').forEach(card => {
                        cards.push({
                            title: card.querySelector('.card-title').textContent,
                            priority: card.querySelector('.badge').classList.contains('high') ? 'high' : card.querySelector('.badge').classList.contains('medium') ? 'medium' : 'low',
                            avatar: card.querySelector('.avatar').src
                        });
                    });

                    kanbanState[columnId] = cards;
                });

                localStorage.setItem('kanbanState', JSON.stringify(kanbanState));
                console.log('Kanban state saved:', kanbanState);
            }

            // Função para carregar o estado do Kanban (se existir)
            function loadKanbanState() {
                const savedState = localStorage.getItem('kanbanState');
                if (savedState) {
                    const kanbanState = JSON.parse(savedState);

                    for (const columnId in kanbanState) {
                        const column = document.getElementById(columnId);
                        if (column) {
                            // Limpar a coluna
                            column.innerHTML = '';

                            // Adicionar os cards salvos
                            kanbanState[columnId].forEach(cardData => {
                                const card = document.createElement('div');
                                card.className = 'kanban-card';

                                card.innerHTML = `
                            <div class="badge ${cardData.priority}"><span>${getPriorityText(cardData.priority)}</span></div>
                            <p class="card-title">${cardData.title}</p>
                            <div class="card-footer">
                                <img src="${cardData.avatar}" alt="avatar" class="avatar">
                            </div>
                        `;

                                column.appendChild(card);
                            });
                        }
                    }
                }
            }

            function getPriorityText(priority) {
                switch (priority) {
                    case 'high':
                        return 'Alta prioridade';
                    case 'medium':
                        return 'Média prioridade';
                    case 'low':
                        return 'Baixa prioridade';
                    default:
                        return 'Média prioridade';
                }
            }

            // Carregar o estado salvo (se existir)
            // loadKanbanState();

            // // Modal para adicionar novo card
            // const modal = document.getElementById('add-card-modal');
            // const addCardBtn = document.getElementById('add-card-btn');
            // const closeBtn = document.querySelector('.close');
            // const addCardForm = document.getElementById('add-card-form');

            // // Abrir modal
            // addCardBtn.addEventListener('click', function() {
            //     modal.style.display = 'block';
            // });

            // // Fechar modal
            // closeBtn.addEventListener('click', function() {
            //     modal.style.display = 'none';
            // });

            // // Fechar modal ao clicar fora dele
            // window.addEventListener('click', function(event) {
            //     if (event.target === modal) {
            //         modal.style.display = 'none';
            //     }
            // });

            // Adicionar novo card
            // addCardForm.addEventListener('submit', function(e) {
            //     e.preventDefault();

            //     const title = document.getElementById('card-title').value;
            //     const columnId = document.getElementById('card-column').value;
            //     const priority = document.getElementById('card-priority').value;

            //     // Gerar um avatar aleatório
            //     const avatarId = Math.floor(Math.random() * 70) + 1;

            //     addNewCard(columnId, title, priority, avatarId);

            //     // Fechar modal e limpar formulário
            //     modal.style.display = 'none';
            //     addCardForm.reset();

            //     // Salvar o estado atualizado
            //     saveKanbanState();
            // });

            //     // Função para adicionar novos cards
            //     function addNewCard(columnId, title, priority, avatarId) {
            //         const column = document.getElementById(`column${columnId}`);
            //         const newCard = document.createElement('div');
            //         newCard.className = 'kanban-card';

            //         let priorityText = getPriorityText(priority);

            //         newCard.innerHTML = `
            //     <div class="badge ${priority}"><span>${priorityText}</span></div>
            //     <p class="card-title">${title}</p>
            //     <div class="card-footer">
            //         <img src="https://i.pravatar.cc/30?img=${avatarId}" alt="avatar" class="avatar">
            //     </div>
            // `;

            //         column.appendChild(newCard);
            //     }

            //     window.addNewCard = addNewCard;
        });
    </script>


    <script>
        const kanban = document.querySelector(".kanban");

        let isDown = false;
        let startX;
        let scrollLeft;

        kanban.addEventListener("mousedown", (e) => {
            // 🔹 só ativa o drag-to-scroll se não clicou em um card
            if (e.target.closest(".kanban-card")) return;

            isDown = true;
            // kanban.classList.add("dragging");
            startX = e.pageX;
            scrollLeft = kanban.scrollLeft;
        });

        kanban.addEventListener("mouseup", () => {
            isDown = false;
            // kanban.classList.remove("dragging");
        });

        kanban.addEventListener("mouseleave", () => {
            isDown = false;
            // kanban.classList.remove("dragging");
        });

        kanban.addEventListener("mousemove", (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX;
            const walk = (x - startX);
            kanban.scrollLeft = scrollLeft - walk;
        });
    </script>
</body>

</html>