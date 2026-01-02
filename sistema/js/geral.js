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
