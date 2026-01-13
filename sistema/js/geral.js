document.addEventListener("DOMContentLoaded", () => {
  // IDs ajustados para minúsculas e sem acentuação
  const ids = [
    "painel",
    "crm",
    "pessoas",
    "processos",
    "agenda",
    "site",
    "leads",
    "ia",
    "financeiro",
    "relatorios",
    "configuracoes",
  ];

  // Adicionando tippy para cada ID
  ids.forEach((id) => {
    tippy(`#${id}`, {
      content: id.toUpperCase(),
      placement: "right",
    });
  });

  tippy(`.whatsapp`, {
    content: "Abrir Whatsapp Web",
    placement: "right",
  });

  tippy(`#visualizar_site`, {
    content: "Necessário ter configurado um modelo!",
    placement: "left",
  });
});

$(".search-input").on("input", function () {
  // Pelo menos 3 caracteres
  if ($(this).val().length > 1) {
    let valor = $(this).val();

    $.ajax({
      url: "/adv/sistema/geral/filtro_pesquisa_topo.php",
      method: "POST",
      dataType: "JSON",
      data: {
        valor: valor,
        acao: "pesquisar_dados",
      },
      success: function (res) {
        let container = $(".container_resultados");
        let ul = container.find("ul");

        ul.empty(); // limpa resultados anteriores

        if (res.status === "success") {
          $.each(res.dados, function (index, item) {
            let link = "#";
            let li = "";

            // Define o link conforme o tipo
            if (item.tipo_resultado === "pessoa") {
              link = "/adv/sistema/pessoa/ficha_pessoa.php?tkn" + item.tk;

              li = `
                        <li class="resultado ${item.tipo_resultado}">
                            <a href="${link}">
                                ${item.nome}
                                <span class="tipo">(${item.tipo_parte})</span>
                            </a>
                        </li>
                    `;
            } else if (item.tipo_resultado === "processo") {
              link = "/adv/sistema/processo/ficha_processo.php?tkn" + item.tk;

              li = `
                        <li class="resultado ${item.tipo_resultado}">
                            <a href="${link}">
                                ${item.referencia}
                                <span class="tipo">(${item.tipo_acao})</span>
                            </a>
                        </li>
                    `;
            }

            ul.append(li);
          });

          container.show();
        } else {
          ul.append(
            '<li class="sem_resultado">Nenhum resultado encontrado</li>'
          );
          container.show();
        }
      },
    });
  }
});
