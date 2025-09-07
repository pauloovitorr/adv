document.addEventListener("DOMContentLoaded", () => {
  // IDs ajustados para minúsculas e sem acentuação
  const ids = [
    "painel",
    "crm",
    "pessoas",
    "processos",
    "atividades",
    "site",
    "leads",
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
});
