<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];


if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['tkn'])) {
}





?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pessoas/ficha_pessoa.css">
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
                    <input type="radio" name="template" value="modelo-1" class="visually-hidden" />
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
                    <input type="radio" name="template" value="modelo-2" class="visually-hidden" />
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
                    <input type="radio" name="template" value="modelo-3" class="visually-hidden" />
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


    <style>
        :root {

            --card: #ffffffff;
            --muted: #96a0af;
            --text: #06112483;
            --primary: #2f81f7;
            --primary-600: #3b6fde;
            --ring: #82aaff;
            --ok: #22c55e;
            --radius: 8px;
            --shadow: box-shadow: rgba(0, 0, 0, 0.04) 0px 3px 5px;
        }



        .page-header {
            padding: 32px 20px 8px;
            max-width: 1120px;
            margin: 0 auto
        }

        .page-header h1 {
            margin: 0 0 6px;
            font-size: 28px;
            font-weight: 600
        }

        .subtitle {
            margin: 0;
            color: var(--muted)
        }

        .container {
            max-width: 1120px;
            margin: 24px auto 56px;
            padding: 0 20px
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 24px;
        }

        @media (max-width:1024px) {
            .card-grid {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        @media (max-width:640px) {
            .card-grid {
                grid-template-columns: 1fr
            }
        }

        .template-card {
            display: flex;
            flex-direction: column;
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid rgba(255, 255, 255, .06);
            box-shadow: var(--shadow);
            cursor: pointer;
            outline: none;
            transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
            position: relative;
        }

        .template-card:focus-visible {
            box-shadow: 0 0 0 4px rgba(130, 170, 255, .35), var(--shadow);
            border-color: var(--ring);
        }

        .template-card:hover {
            transform: translateY(-2px)
        }

        .template-card.selected {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 140, 255, .25), var(--shadow);
        }

        .card-media {
            position: relative;
            aspect-ratio: 16/9;
            overflow: hidden;
            border-top-left-radius: var(--radius);
            border-top-right-radius: var(--radius);
            background: #0a0e13;
        }

        .card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: saturate(1.05) contrast(1.05);
            transition: transform .4s ease;
        }

        .template-card:hover .card-media img {
            transform: scale(1.04)
        }

        .badge {
            position: absolute;
            left: 12px;
            top: 12px;
            background: rgba(10, 14, 20, .75);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, .08);
            color: #dbe7ff;
            padding: 6px 10px;
            border-radius: 10px;
            font-size: 12px;
        }

        .card-body {
            padding: 14px 14px 16px
        }

        .card-body h2 {
            margin: 2px 0 6px;
            font-size: 18px
        }

        .card-body p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.45
        }

        .visually-hidden {
            position: absolute !important;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .actions {
            margin-top: 22px;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .btn {
            appearance: none;
            border: 0;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn.primary {
            background: var(--primary);
            color: white
        }

        .btn.primary:disabled {
            opacity: .4;
            cursor: not-allowed
        }

        .btn.ghost {
            background: #06112483;
            color: white;
            border: 1px solid rgba(255, 255, 255, .12)
        }

        .btn.ghost:hover {
            background: #061124;
        }

        .btn:active {
            transform: translateY(1px)
        }

        .toast {
            position: fixed;
            right: 20px;
            bottom: 20px;
            background: #0f1724;
            color: #e7f0ff;
            border: 1px solid rgba(130, 170, 255, .25);
            padding: 12px 14px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            max-width: 320px;
        }
    </style>

    <script>
        // app.js
        (function() {
            const grid = document.querySelector('.card-grid');
            const radios = grid.querySelectorAll('input[type="radio"][name="template"]');
            const chooseBtn = document.getElementById('chooseBtn');
            const previewBtn = document.getElementById('previewBtn');
            const toastTpl = document.getElementById('toastTpl');

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
                toast(`Pré-visualizando ${val}…`);
                // Navegue para rota de preview, ex.: /site/preview.php?template=val
                // window.location.href = `/site/preview.php?template=${encodeURIComponent(val)}`;
            });

            chooseBtn.addEventListener('click', () => {
                const val = grid.querySelector('input[type="radio"]:checked')?.value;
                if (!val) return;
                toast(`Modelo ${val} selecionado, avançando para personalização.`);
                // Exemplo de post para PHP procedural
                // fetch('salvar-escolha.php', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:`template=${encodeURIComponent(val)}`})
                //   .then(()=> window.location.href = `/site/personalizar.php?template=${encodeURIComponent(val)}`);
            });

            // Toast simples
            let t;

            function toast(msg) {
                clearTimeout(t);
                document.querySelectorAll('.toast').forEach(n => n.remove());
                const node = toastTpl.content.firstElementChild.cloneNode(true);
                node.textContent = msg;
                document.body.appendChild(node);
                t = setTimeout(() => node.remove(), 2400);
            }
        })();
    </script>

</body>

</html>