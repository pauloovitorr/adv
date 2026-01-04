<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['start']) && !empty($_GET['end'])) {

    // Converte as datas de GET para formato datetime do MySQL
    $dt_start = date('Y-m-d H:i:s', strtotime($_GET['start']));
    $dt_end = date('Y-m-d H:i:s', strtotime($_GET['end']));

    // Consulta os eventos do usuário no intervalo
    $sql_busca_eventos = "
        SELECT * 
        FROM eventos_crm 
        WHERE data_inicio <= '$dt_end'  
          AND data_fim >= '$dt_start' 
          AND usuario_config_id_usuario_config = $id_user
    ";

    $datas_eventos = $conexao->query($sql_busca_eventos);

    $eventos = [];

    if ($datas_eventos && $datas_eventos->num_rows > 0) {
        while ($evento = $datas_eventos->fetch_assoc()) {

            // Ajusta o end para eventos all-day (FullCalendar trata end como exclusivo)
            $end = $evento["data_fim"];
            if (strtolower($evento["all_day"]) === 'sim') {
                // Adiciona 1 dia para que o último dia seja exibido corretamente
                $end = date('Y-m-d', strtotime($end . ' +1 day'));
            }

            $eventos[] = [
                "id" => $evento["id_evento_crm"],
                "title" => $evento["titulo"],
                "start" => str_replace(' ', 'T', $evento["data_inicio"]),
                "end" => str_replace(' ', 'T', $end),
                "allDay" => strtolower($evento["all_day"]) === 'sim',
                "color" => $evento["cor"],
                "extendedProps" => [
                    "descricao" => $evento["descricao"]
                ]
            ];
        }
    }

    // Retorna JSON para o FullCalendar
    echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
    $conexao->close();
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['start']) && !empty($_POST['end']) && !empty($_POST['color'])) {
    $title = $conexao->real_escape_string(htmlspecialchars($_POST['title']));
    $description = $conexao->real_escape_string(htmlspecialchars($_POST['description']));
    $allDay = $conexao->real_escape_string(htmlspecialchars($_POST['allDay']));
    $start = $conexao->real_escape_string(htmlspecialchars($_POST['start']));
    $end = $conexao->real_escape_string(htmlspecialchars($_POST['end']));
    $color = $conexao->real_escape_string(htmlspecialchars($_POST['color']));



    // Query preparada
    $sql = "INSERT INTO eventos_crm 
                (titulo, descricao, all_day, data_inicio, data_fim, cor, usuario_config_id_usuario_config)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conexao->prepare($sql)) {
        $stmt->bind_param("ssssssi", $title, $description, $allDay, $start, $end, $color, $id_user);

        if ($stmt->execute()) {
            $res = [
                'status' => 'success',
                'message' => 'Evento salvo com sucesso!',
                'id' => $stmt->insert_id
            ];
        } else {
            $res = [
                'status' => 'error',
                'message' => 'Erro ao salvar evento: ' . $stmt->error
            ];
        }
    } else {
        $res = [
            'status' => 'error',
            'message' => 'Erro ao preparar statement: ' . $conexao->error
        ];
    }

    // Retorna resposta em JSON
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    $conexao->close();
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_compromisso']) && !empty($_POST['start']) && !empty($_POST['end']) && isset($_POST['all_day'])) {

    $id_compromisso = (int) $_POST['id_compromisso'];
    $allDay = ($_POST['all_day'] == 'true') ? 'sim' : 'nao';
    $start = $conexao->real_escape_string($_POST['start']);
    $end = $conexao->real_escape_string($_POST['end']);

    // Converte para o formato correto do MySQL

    if ($allDay == 'sim') {
        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = date('Y-m-d H:i:s', strtotime($end . ' -1 day'));
    } else {
        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = date('Y-m-d H:i:s', strtotime($end));
    }

    $sql = "
        UPDATE eventos_crm
        SET 
            data_inicio = '$start',
            data_fim = '$end'
        WHERE id_evento_crm = $id_compromisso
    ";

    if ($conexao->query($sql)) {
        $res = [
            'status' => 'success',
            'message' => 'Evento atualizado com sucesso!'
        ];
    } else {
        $res = [
            'status' => 'error',
            'message' => 'Erro ao atualizar evento: '
        ];
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    $conexao->close();
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_compromisso']) && !empty($_POST['acao']) && $_POST['acao'] == 'excluir') {

    $id_compromisso = $conexao->real_escape_string($_POST['id_compromisso']);
    $sql_delete_compromisso = "DELETE FROM eventos_crm WHERE id_evento_crm = $id_compromisso AND usuario_config_id_usuario_config = $id_user";

    if ($conexao->query($sql_delete_compromisso)) {
        $res = [
            'status' => 'success',
            'message' => 'Evento excluído com sucesso!'
        ];
    } else {
        $res = [
            'status' => 'error',
            'message' => 'Erro ao excluir evento: '
        ];
    }

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
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                // Configurações básicas
                initialView: 'dayGridMonth', // Visualização inicial
                locale: 'pt-br', // Idioma
                timeZone: 'America/Sao_Paulo', // Fuso horário
                themeSystem: 'standard',

                // Configuração do cabeçalho com textos personalizados
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                views: {
                    listWeek: {
                        allDayText: 'Dia inteiro'
                    }
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
                events: './agenda.php',

                select: function (info) {

                    Swal.fire({
                        html: `
                           <form id="eventForm" method="post">
                <h2>Novo Compromisso</h2>

                <label for="title">Título</label>
                <input type="text" id="title" name="title" maxlength="40" required>

                <label for="description">Descrição</label>
                <textarea id="description" name="description" placeholder="Descreva o compromisso..."></textarea>

                 <div class="checkbox-group">
                    <div>
                        <input type="radio" id="allDay" value="sim" name="allDay" checked>
                        <label for="allDay">Evento o dia todo</label>
                    </div>
                    
                    <div>
                        <input type="radio" id="partDay" value="nao" name="allDay" >
                        <label for="partDay">Considerar horário</label>
                    </div>
                </div>

                <div class="data_evento">
                    <div>
                        <label for="start">Início</label>
                        <input type="date" id="start" name="start" required>
                    </div>
                    <div>
                        <label for="end">Fim</label>
                        <input type="date" id="end" name="end"  required>
                    </div>
                </div>

                <label>Cor da Etiqueta</label>
                <div class="color-options">
                    <div class="color-choice" data-color="#007bff" style="background-color:#007bff;"></div>
                    <div class="color-choice" data-color="#f6c23e" style="background-color:#f6c23e;"></div>
                    <div class="color-choice" data-color="#dc3545" style="background-color:#dc3545;"></div>
                </div>

                <input type="hidden" id="eventColor" name="color" value="#007bff">

                <button type="submit" class="btn" id="salvarEvento">Salvar Evento</button>
            </form>
                           `,
                        confirmButtonText: 'Fechar',
                        confirmButtonColor: " #06112483",
                        didOpen: () => {
                            $(document).ready(function () {

                                const allDayRadio = document.getElementById('allDay');
                                const partDayRadio = document.getElementById('partDay');
                                const startInput = document.getElementById('start');
                                const endInput = document.getElementById('end');

                                const startDate = new Date(info.startStr);
                                const endDate = new Date(info.endStr);
                                const displayEndDate = new Date(endDate);
                                displayEndDate.setDate(endDate.getDate() - 1);

                                // Funções de formatação
                                const formatDate = (d) => d.toISOString().slice(0, 10); // YYYY-MM-DD
                                const formatDateTime = (d) => {
                                    const pad = (n) => n.toString().padStart(2, '0');
                                    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
                                }

                                // Preenche os inputs inicialmente como date
                                startInput.type = endInput.type = 'date';
                                startInput.value = formatDate(startDate);
                                endInput.value = formatDate(displayEndDate);

                                // Função para alternar tipo mantendo valor
                                function toggleInputType(isAllDay) {
                                    if (isAllDay) {
                                        // Muda para date mantendo somente a data
                                        const start = new Date(startInput.value);
                                        const end = new Date(endInput.value);
                                        startInput.type = endInput.type = 'date';
                                        startInput.value = formatDate(start);
                                        endInput.value = formatDate(end);
                                    } else {
                                        // Muda para datetime-local mantendo a hora
                                        const parseDateTime = (val) => {
                                            const [year, month, day] = val.split('-').map(Number);
                                            return new Date(year, month - 1, day, 0, 0);
                                        };
                                        const start = parseDateTime(startInput.value);
                                        const end = parseDateTime(endInput.value);
                                        startInput.type = endInput.type = 'datetime-local';
                                        startInput.value = formatDateTime(start);
                                        endInput.value = formatDateTime(end);
                                    }
                                }

                                // Listeners
                                allDayRadio.addEventListener('change', () => toggleInputType(allDayRadio.checked));
                                partDayRadio.addEventListener('change', () => toggleInputType(!partDayRadio.checked));


                                startInput.addEventListener('change', function () {
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
                                $('#eventForm').on('submit', function (e) {
                                    e.preventDefault()

                                    $('#salvarEvento').attr('disabled', 'true')

                                    $.ajax({
                                        url: './agenda.php',
                                        method: 'POST',
                                        dataType: 'JSON',
                                        data: $(this).serialize(),
                                        success: function (res) {
                                            if (res.status == 'error') {
                                                Swal.fire({
                                                    icon: "error",
                                                    title: "Erro ao cadastrar evento!",
                                                    text: res.message
                                                });
                                            } else {
                                                Swal.fire({
                                                    title: "Sucesso",
                                                    text: "Evento cadastrado com sucesso!",
                                                    icon: "success"
                                                });
                                            }

                                            setTimeout(() => {
                                                window.location.reload()
                                            }, 1500)

                                        }
                                    })


                                })
                                // !-- FIM Ajax do crud de eventos-- >




                            })
                        }
                    })

                }

                ,

                // Callback quando clicar em um dia
                // dateClick: function(info) {
                //     console.log(info)
                //     alert('Data clicada: ' + info.dateStr);
                // },

                // Callback quando clicar em um evento
                eventClick: function (info) {
                    const evento = info.event;

                    let id_evento = evento._def.publicId

                    const descricao = evento.extendedProps.descricao || 'Sem descrição.';

                    let detalhes = '';

                    if (evento.allDay) {

                        const inicioDate = new Date(evento.start.getUTCFullYear(), evento.start.getUTCMonth(), evento.start.getUTCDate());

                        let fimDate;
                        if (evento.end) {
                            fimDate = new Date(evento.end.getUTCFullYear(), evento.end.getUTCMonth(), evento.end.getUTCDate() - 1);
                        } else {
                            fimDate = inicioDate;
                        }

                        const inicio = inicioDate.toLocaleDateString('pt-BR', {
                            weekday: 'long',
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });

                        const fim = fimDate.toLocaleDateString('pt-BR', {
                            weekday: 'long',
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });

                        detalhes = `
        <div class="detalhe-linha"><span class="icone">📅</span> <strong>Início:</strong> ${inicio}</div>
        <div class="detalhe-linha"><span class="icone">📅</span> <strong>Fim:</strong> ${fim}</div>
    `;
                    } else {

                        const inicioDate = new Date(evento.start.getTime() + evento.start.getTimezoneOffset() * 60000);
                        let fimDate = evento.end ? new Date(evento.end.getTime() + evento.end.getTimezoneOffset() * 60000) : null;

                        const inicio = inicioDate.toLocaleString('pt-BR', {
                            weekday: 'long',
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        const fim = fimDate ? fimDate.toLocaleString('pt-BR', {
                            weekday: 'long',
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        }) : '—';

                        detalhes = `
        <div class="detalhe-linha"><span class="icone">🕒</span> <strong>Início:</strong> ${inicio}</div>
        <div class="detalhe-linha"><span class="icone">🕒</span> <strong>Fim:</strong> ${fim}</div>
    `;
                    }


                    Swal.fire({
                        title: `<strong>${evento.title}</strong>`,
                        html: `
            <div style="
                text-align: left;
                font-size: 15px;
                background: #ffffff;
                border-radius: 12px;
                padding: 15px 20px;
                box-shadow: 0 1px 1px rgba(0,0,0,0.08);
                color: #1e293b;
                line-height: 1.6;
                position: relative
            ">
            <a class="dz-remove" href="javascript:undefined;" data-dz-remove="">X
                <input type="hidden" class="codigo_evento" value="${id_evento}">
            </a>
                <p style="
                    margin-bottom: 12px;
                    font-size: 15px;
                    background: #ffffffff;
                    padding: 10px 12px;
                    border-radius: 8px;
                ">
                    ${descricao}
                </p>
                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 12px 0;">
                <div style="font-size: 14px;">
                    ${detalhes}
                </div>
            </div>
        `,
                        confirmButtonText: 'Fechar',
                        confirmButtonColor: '#06112483',
                        background: '#f8fafc',
                        width: 450,
                        customClass: {
                            popup: 'rounded-2xl shadow-xl'
                        },
                        didOpen: () => {

                            tippy(`.dz-remove`, {
                                content: "Excluir Compromisso!",
                                placement: "right",
                            });

                            $(document).ready(function () {
                                $('.dz-remove').on('click', function () {
                                    dados = {
                                        id_compromisso: $(this).find('.codigo_evento').val(),
                                        acao: 'excluir'
                                    }

                                    $.ajax({
                                        url: './agenda.php',
                                        method: 'POST',
                                        dataType: 'json',
                                        data: dados,
                                        success: function (res) {
                                            if (res.status == 'error') {
                                                Swal.fire({
                                                    icon: "error",
                                                    title: "Erro na exclusão",
                                                    text: res.message
                                                });
                                            } else {
                                                Swal.fire({
                                                    title: "Sucesso",
                                                    text: "Evento excluído",
                                                    icon: "success"
                                                });
                                            }

                                            setTimeout(() => {
                                                window.location.reload()
                                            }, 1500)

                                        }

                                    })

                                })
                            })

                        }
                    });
                },

                // Callback ao arrastar evento
                eventDrop: function (info) {
                    const evento = info.event;

                    // Captura os dados formatados no padrão do backend (Y-m-d H:i:s)
                    const formatDateTime = (date) => {
                        return date.toISOString().slice(0, 19).replace('T', ' ');
                    };


                    const dados = {
                        id_compromisso: evento.id,
                        start: formatDateTime(evento.start),
                        end: evento.end ? formatDateTime(evento.end) : formatDateTime(evento.start),
                        all_day: evento.allDay ? 'true' : 'false'
                    };

                    $.ajax({
                        url: './agenda.php',
                        method: 'POST',
                        dataType: 'JSON',
                        data: dados,

                        success: function (res) {

                            console.log(res)

                            // setTimeout(() => {
                            //     window.location.reload()
                            // }, 1500)
                        }


                    })
                }

                ,

            });

            calendar.render();
        });
    </script>





</body>

</html>