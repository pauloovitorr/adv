<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['start']) && !empty($_POST['end']) && !empty($_POST['color'])
) {
    $title       = $conexao->real_escape_string(htmlspecialchars($_POST['title']));
    $description = $conexao->real_escape_string(htmlspecialchars($_POST['description']));
    $allDay      = isset($_POST['allDay']) ? 1 : 0;
    $start       = $conexao->real_escape_string(htmlspecialchars($_POST['start']));
    $end         = $conexao->real_escape_string(htmlspecialchars($_POST['end']));
    $color       = $conexao->real_escape_string(htmlspecialchars($_POST['color']));
    

    // Query preparada
    $sql = "INSERT INTO eventos_crm 
                (titulo, descricao, all_day, data_inicio, data_fim, cor, usuario_config_id_usuario_config)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conexao->prepare($sql)) {
        $stmt->bind_param("ssisssi", $title, $description, $allDay, $start, $end, $color, $id_user);

        if ($stmt->execute()) {
            $res = [
                'status' => 'success',
                'msg'    => 'Evento salvo com sucesso!',
                'id'     => $stmt->insert_id
            ];
        } else {
            $res = [
                'status' => 'error',
                'msg'    => 'Erro ao salvar evento: ' . $stmt->error
            ];
        }
    } else {
        $res = [
            'status' => 'error',
            'msg'    => 'Erro ao preparar statement: ' . $conexao->error
        ];
    }

    // Retorna resposta em JSON
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    $conexao->close();
    exit;
}





?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/agenda/agenda.css">
    <title>ADV Conectado</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>
<div class="container_breadcrumb">
    <div class="pai_topo">
        <div class="breadcrumb">
            <span class="breadcrumb-current">Agenda</span>
            <span class="breadcrumb-separator">/</span>
        </div>
    </div>
</div>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">
            <div id='calendar'></div>
        </div>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                // Configurações básicas
                initialView: 'dayGridMonth', // Visualização inicial
                locale: 'pt-br', // Idioma
                timeZone: 'America/Sao_Paulo', // Fuso horário
                themeSystem: 'standard', // Pode usar "bootstrap5" também

                // Configuração do cabeçalho com textos personalizados
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },

                // Textos personalizados dos botões
                buttonText: {
                    today: 'Hoje',
                    month: 'Mês',
                    week: 'Semana',
                    day: 'Dia',
                    list: 'Lista'
                },

                //  Interação
                selectable: true,
                editable: true,
                nowIndicator: true,
                dayMaxEvents: true, // mostra "+x mais" se muitos eventos

                // Eventos
                events: [{
                        id: '1',
                        title: 'Reunião de Equipe',
                        start: '2025-10-15T09:00:00',
                        end: '2025-10-15T10:30:00',
                        color: '#3788d8',
                        extendedProps: {
                            descricao: 'Revisar metas e andamento dos projetos.'
                        }
                    },
                    {
                        id: '2',
                        title: 'Entrega de Projeto',
                        start: '2025-10-20',
                        allDay: true,
                        color: '#d83a3a'
                    },
                    {
                        id: '3',
                        title: 'Aniversário da Maria',
                        start: '2025-10-22',
                        allDay: true,
                        color: '#f6c23e'
                    }
                ],

                select: function(info) {
                    // info.startStr → data inicial
                    // info.endStr → data final (exclusiva — termina no dia seguinte)
                    Swal.fire({
                        html: `
                           <form id="eventForm" method="post">
                <h2>Novo Compromisso</h2>

                <label for="title">Título</label>
                <input type="text" id="title" name="title" maxlength="40" required>

                <label for="description">Descrição</label>
                <textarea id="description" name="description" placeholder="Descreva o compromisso..."></textarea>

                 <div class="checkbox-group">
                    <input type="checkbox" id="allDay" name="allDay">
                    <label for="allDay">Evento o dia todo</label>
                </div>

                <div class="data_evento">
                    <div>
                        <label for="start">Início</label>
                        <input type="datetime-local" id="start" name="start" required>
                    </div>
                    <div>
                        <label for="end">Fim</label>
                        <input type="datetime-local" id="end" name="end" required>
                    </div>
                </div>

                <label>Cor da Etiqueta</label>
                <div class="color-options">
                    <div class="color-choice" data-color="#007bff" style="background-color:#007bff;"></div>
                    <div class="color-choice" data-color="#28a745" style="background-color:#28a745;"></div>
                    <div class="color-choice" data-color="#dc3545" style="background-color:#dc3545;"></div>
                </div>

                <input type="hidden" id="eventColor" name="color" value="#007bff">

                <button type="submit" class="btn">Salvar Evento</button>
            </form>
                           `,
                        confirmButtonText: 'Fechar',
                        confirmButtonColor: " #06112483",
                        didOpen: () => {
                            $(document).ready(function() {

                                const allDayCheckbox = document.getElementById('allDay');
                                const startInput = document.getElementById('start');
                                const endInput = document.getElementById('end');

                                allDayCheckbox.addEventListener('change', () => {
                                    if (allDayCheckbox.checked) {
                                        startInput.type = endInput.type = 'date';
                                    } else {
                                        startInput.type = endInput.type = 'datetime-local';
                                    }
                                });

                                startInput.addEventListener('change', function() {
                                    endInput.setAttribute('min', startInput.value)
                                })



                                const colorChoices = document.querySelectorAll('.color-choice');
                                const colorInput = document.getElementById('eventColor');

                                colorChoices.forEach(choice => {
                                    choice.addEventListener('click', () => {
                                        colorChoices.forEach(c => c.classList.remove('selected'));
                                        choice.classList.add('selected');
                                        colorInput.value = choice.getAttribute('data-color');
                                    });
                                });

                                // Define a primeira cor como selecionada por padrão
                                colorChoices[0].classList.add('selected');




                                // !--Ajax do crud de eventos-- >
                                $('#eventForm').on('submit', function(e) {
                                    e.preventDefault()

                                    $.ajax({
                                        url: './agenda.php',
                                        method: 'POST',
                                        dataType: 'JSON',
                                        data: $(this).serialize(),
                                        success: function(res){
                                            console.log(res)
                                        }
                                    })

                                })
                                // !-- FIM Ajax do crud de eventos-- >




                            })
                        }
                    })

                },

                // Callback quando clicar em um dia
                // dateClick: function(info) {
                //     console.log(info)
                //     alert('Data clicada: ' + info.dateStr);
                // },

                // Callback quando clicar em um evento
                eventClick: function(info) {

                    // console.log(info.el.fcSeg.eventRange.def['publicId'])


                    Swal.fire({
                        title: info.event.title,
                        text: (info.event.extendedProps.descricao || 'Sem descrição.'),
                        confirmButtonText: 'Fechar',
                        confirmButtonColor: " #06112483"
                    });


                },

                // Callback ao arrastar evento
                eventDrop: function(info) {
                    alert(
                        'Evento "' + info.event.title + '" movido para ' +
                        info.event.start.toLocaleString()
                    );
                },

                // Callback ao redimensionar evento
                eventResize: function(info) {
                    alert('Evento redimensionado: ' + info.event.title);
                }
            });

            calendar.render();
        });
    </script>





</body>

</html>