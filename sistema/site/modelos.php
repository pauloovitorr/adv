<?php
include_once('../../scripts.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['modelo'])){
    
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/modelos/modelos.css">
    <title>ADV Conectado</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>

<div class="container_breadcrumb">
    <div class="pai_topo">
        <div class="breadcrumb">
            <span class="breadcrumb-current">Modelos</span>
            <span class="breadcrumb-separator">/</span>
        </div>
    </div>
</div>

<style>
    .topo_kanban {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 24px;
        margin-bottom: 24px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e0e0e0;
    }

    .topo_kanban h1 {
        font-weight: 600;
        color: #2c3e50;
    }
</style>


<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <div class="topo_kanban">
                <h1>Gestão de Modelos</h1>


            </div>

            <form id="templateForm" class="card-grid" aria-label="Galeria de modelos">
                <!-- Card 1 -->
                <label class="template-card" tabindex="0">
                    <input type="radio" name="template" value="1" class="visually-hidden" />
                    <div class="card-media">
                        <img src="https://picsum.photos/seed/law1/640/360" alt="Prévia do Modelo 1" loading="lazy" />
                        <span class="badge">Modelo 1</span>
                    </div>
                    <div class="card-body">
                        <h2>Clássico Escritório</h2>
                        <p>Layout sóbrio com herói amplo e foco em serviços.</p>
                    </div>
                </label>

                <!-- Card 2 -->
                <label class="template-card" tabindex="0">
                    <input type="radio" name="template" value="2" class="visually-hidden" />
                    <div class="card-media">
                        <img src="https://picsum.photos/seed/law2/640/360" alt="Prévia do Modelo 2" loading="lazy" />
                        <span class="badge">Modelo 2</span>
                    </div>
                    <div class="card-body">
                        <h2>Moderno Minimal</h2>
                        <p>Tipografia marcante e seções modulares de casos.</p>
                    </div>
                </label>

                <!-- Card 3 -->
                <label class="template-card" tabindex="0">
                    <input type="radio" name="template" value="3" class="visually-hidden" />
                    <div class="card-media">
                        <img src="https://picsum.photos/seed/law3/640/360" alt="Prévia do Modelo 3" loading="lazy" />
                        <span class="badge">Modelo 3</span>
                    </div>
                    <div class="card-body">
                        <h2>Autoridade Premium</h2>
                        <p>Hero com vídeo, depoimentos e CTA persistente.</p>
                    </div>
                </label>
            </form>

            <div class="actions">
                <button id="previewBtn" class="btn ghost" type="button" disabled>Pré-visualizar</button>
                <button id="chooseBtn" class="btn primary" type="button" disabled>Escolher modelo</button>
            </div>

        </div>
    </main>




    <script>
        // app.js
        (function() {
            const grid = document.querySelector('.card-grid');
            const radios = grid.querySelectorAll('input[type="radio"][name="template"]');
            const chooseBtn = document.getElementById('chooseBtn');
            const previewBtn = document.getElementById('previewBtn');
            // const toastTpl = document.getElementById('toastTpl');

            // Marca visualmente o card selecionado e habilita ações
            function updateSelection() {
                document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
                const checked = grid.querySelector('input[type="radio"]:checked');
                if (checked) {
                    checked.closest('.template-card').classList.add('selected');
                    chooseBtn.disabled = false;
                    previewBtn.disabled = false;
                } else {
                    chooseBtn.disabled = true;
                    previewBtn.disabled = true;
                }
            }

            // Permite selecionar com Enter/Espaço no label focado (acessível)
            grid.addEventListener('keydown', (e) => {
                if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('template-card')) {
                    e.preventDefault();
                    const radio = e.target.querySelector('input[type="radio"]');
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            });

            radios.forEach(r => r.addEventListener('change', updateSelection));
            updateSelection();

            // Botões de ação
            previewBtn.addEventListener('click', () => {
                const val = grid.querySelector('input[type="radio"]:checked')?.value;

                window.open('./modelo1/index.php', '_blank')

            });

            chooseBtn.addEventListener('click', () => {
                const val = grid.querySelector('input[type="radio"]:checked')?.value;
                if (!val) return;

            });


        })();
    </script>

    <script>
        $(document).ready(function() {
            $('#chooseBtn').on('click', function() {
                let modelo = $('.selected').find('input[type=radio]').val()


                $.ajax({
                    url: './modelos.php',
                    type: 'POST',
                    data: {
                        modelo: modelo,
                    },
                    dataType: 'json',
                    success: function(res) {

                        console.log(res)

                    }
                })

            })

        })
    </script>

</body>

</html>