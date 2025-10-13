<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];

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
        document.addEventListener("DOMContentLoaded", function() {
            var calendarEl = document.getElementById("calendar");
            var calendar = new FullCalendar.Calendar(calendarEl, {
                // Configuração de idioma
                locale: 'pt-br',

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

                // Vista inicial
                initialView: "dayGridMonth",

                // Configurações de data e hora brasileiras
                firstDay: 0, // Domingo como primeiro dia da semana
                weekNumbers: true,
                weekNumberFormat: {
                    week: 'numeric'
                },

                // Formato de data brasileiro
                dayHeaderFormat: {
                    weekday: 'short'
                },

                // Configurações de horário
                slotMinTime: '07:00:00',
                slotMaxTime: '22:00:00',
                slotDuration: '00:30:00',

                // Configurações de navegação
                navLinks: true,

                // Permitir seleção
                selectable: true,
                selectMirror: true,

                // Permitir arrastar eventos
                editable: true,
                dayMaxEvents: true,

                // Textos em português
                allDayText: 'Todo o dia',
                noEventsText: 'Nenhum evento para exibir',

                // Eventos de exemplo
                events: [{
                        title: 'Reunião de Equipe',
                        start: '2025-10-15T09:00:00',
                        end: '2025-10-15T10:30:00',
                        color: '#3788d8'
                    }
                ],

                // Callbacks para interação
                select: function(arg) {
                    var title = prompt('Título do evento:');
                    if (title) {
                        calendar.addEvent({
                            title: title,
                            start: arg.start,
                            end: arg.end,
                            allDay: arg.allDay
                        });
                    }
                    calendar.unselect();
                },

                eventClick: function(arg) {
                    if (confirm('Deseja excluir este evento: "' + arg.event.title + '"?')) {
                        arg.event.remove();
                    }
                },

                // Formatação customizada para datas
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false // Formato 24h
                },

                // Formatação de título de data
                titleFormat: {
                    year: 'numeric',
                    month: 'long'
                },

                // Configurações de hora em português
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                },

                // Tooltip nos eventos
                eventMouseEnter: function(info) {
                    var startTime = info.event.start ? info.event.start.toLocaleString('pt-BR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '';

                    var endTime = info.event.end ? info.event.end.toLocaleString('pt-BR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : '';

                    info.el.title = info.event.title +
                        (startTime ? '\nInício: ' + startTime : '') +
                        (endTime ? '\nFim: ' + endTime : '');
                }
            });

            calendar.render();
        });
    </script>
</body>

</html>