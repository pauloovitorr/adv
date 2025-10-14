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
                        title: 'Adicinar Compromisso',
                        html: `
                           oi`,
                        confirmButtonText: 'Fechar',
                        confirmButtonColor: " #06112483"
                    })

                },

                // Callback quando clicar em um dia
                // dateClick: function(info) {
                //     console.log(info)
                //     alert('Data clicada: ' + info.dateStr);
                // },

                // Callback quando clicar em um evento
                eventClick: function(info) {

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