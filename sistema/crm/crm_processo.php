<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sql_busca_etapas_crm = "SELECT * FROM etapas_crm WHERE usuario_config_id_usuario_config = $id_user ORDER BY ordem ASC";
    $etapas = $conexao->query($sql_busca_etapas_crm);
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
                <div class="kanban-column" data-id="1">
                    <h2>Análise do Caso</h2>
                    <div class="kanban-cards" id="column1">
                        <div class="kanban-card">
                            <div class="badge medium"><span>Média prioridade</span></div>
                            <p class="card-title">Verificar pagamento</p>
                            <div class="card-footer">
                                <img src="https://i.pravatar.cc/30?img=2" alt="avatar" class="avatar">
                            </div>
                        </div>
                        <div class="kanban-card">
                            <div class="badge high"><span>Alta prioridade</span></div>
                            <p class="card-title">Confirmar entrega</p>
                            <div class="card-footer">
                                <img src="https://i.pravatar.cc/30?img=6" alt="avatar" class="avatar">
                            </div>
                        </div>

                        <div class="kanban-card">
                            <div class="badge low"><span>Alta prioridade</span></div>
                            <p class="card-title">Confirmar entrega</p>
                            <div class="card-footer">
                                <img src="https://i.pravatar.cc/30?img=6" alt="avatar" class="avatar">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kanban-column" data-id="2">
                    <h2>Negociação</h2>
                    <div class="kanban-cards" id="column2"></div>
                </div>

                <div class="kanban-column" data-id="3">
                    <h2>Aguardando Documentos</h2>
                    <div class="kanban-cards" id="column3"></div>
                </div>

                <div class="kanban-column" data-id="4">
                    <h2>Proposta</h2>
                    <div class="kanban-cards" id="column4"></div>
                </div>

                <div class="kanban-column" data-id="5">
                    <h2>Ação Protocolada</h2>
                    <div class="kanban-cards" id="column5"></div>
                </div>

                <div class="kanban-column" data-id="6">
                    <h2>Aguardando Audiência</h2>
                    <div class="kanban-cards" id="column6"></div>
                </div>

                <div class="kanban-column" data-id="7">
                    <h2>Aguardando Julgamento</h2>
                    <div class="kanban-cards" id="column7"></div>
                </div>

                <div class="kanban-column" data-id="8">
                    <h2>Desenvolvendo Recurso</h2>
                    <div class="kanban-cards" id="column8"></div>
                </div>

                <div class="kanban-column" data-id="9">
                    <h2>Fechamento</h2>
                    <div class="kanban-cards" id="column9"></div>
                </div>
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
                  <table>
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Ações</th>
                      </tr>
                    </thead>
                    <tbody id="sortable-steps">
                    
                    <?php while($etapa = $etapas->fetch_assoc()) ?>
                      <tr>
                        <td>1</td>
                        <td>Análise do Caso</td>
                        <td>
                          <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
                        </td>
                      </tr>



                      <tr>
                        <td>2</td>
                        <td>Negociação</td>
                        <td>
                          <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
                        </td>
                      </tr>
                      <tr>
                        <td>3</td>
                        <td>Aguardando Documentos</td>
                        <td>
                          <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
                        </td>
                      </tr>
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
                            ghostClass: 'drag-highlight',
                            onEnd: function(evt) {
                                console.log('Nova ordem:', evt.oldIndex, '->', evt.newIndex);
                            }
                        });

                        // Captura envio do formulário dentro do Swal
                        $("#form-add-etapa").on("submit", function(e) {
                            e.preventDefault();
                            const nome = $("#etapa-nome").val();
                            if (nome.trim() !== "") {
                                $("#sortable-steps").append(`
                          <tr>
                            <td>-</td>
                            <td>${nome}</td>
                            <td>

                              <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
                            </td>
                          </tr>
                        `);
                                $("#etapa-nome").val("");
                            }
                        });
                    },
                    preConfirm: () => {
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
                        console.log("Etapas salvas:", result.value.etapas);
                        Swal.fire('Configuração salva!', '', 'success');
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
                        saveKanbanState();
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