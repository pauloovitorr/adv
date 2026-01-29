
# Escritório Digital — CRM Jurídico + Site + IA (MVP)

Este projeto é um MVP (Minimum Viable Product) desenvolvido com o objetivo de consolidar habilidades práticas em desenvolvimento web full stack, aplicando regras de negócio reais, integração entre módulos e foco em experiência do usuário.

A proposta foi criar um ecossistema digital para advogados, unificando em um único sistema:

- Gestão de processos (CRM em Kanban),
- Cadastro de pessoas e documentos,
- Agenda de compromissos,
- Captação e qualificação de leads,
- Site institucional personalizável,
- IA com chat multi-modelos.

O foco deste repositório é demonstrar minhas habilidades práticas de desenvolvimento (backend + frontend), entrega de funcionalidades de ponta a ponta e integração com bibliotecas/APIs reais.

## Visão Geral do Sistema

### 🔐 Tela de Login
![Login](https://i.ibb.co/yBQ5gW5n/login.png)

### 📝 Tela de Cadastro
![Cadastro](https://i.ibb.co/fG4L1t9H/criarconta.png)

### 📊 Dashboard
![Dashboard](https://i.ibb.co/PZtxKr6C/dash.png)

### 🔄 CRM
![CRM](https://i.ibb.co/b5Wbb6gz/crm.png)

### 👥 Pessoas
![Pessoas](https://i.ibb.co/Mk2PTMXR/pessoas.png)

### ⚖️ Processos
![Processos](https://i.ibb.co/psb5qpD/processos.png)

### 📅 Agenda
![Agenda](https://i.ibb.co/XZpXGbVy/agenda.png)

### 🌐 Gestão de Modelos
![Gestão de Modelos](https://i.ibb.co/nsxybgQ3/gestaomodelos.png)

### 🎨 Configuração de Modelo
![Configuração de Modelo](https://i.ibb.co/d4QrXypc/configuracaomodelo.png)

### 🎯 Leads
![Leads](https://i.ibb.co/XkvT9y58/leads.png)

### 🤖 Inteligência Artificial
![IA](https://i.ibb.co/4ZwwFKNb/chat-IA.png)

## Por que esse projeto existe?

Muitos advogados acabam usando ferramentas separadas: planilhas para contatos, anotações soltas para processos, agenda fora do sistema e um site sem integração com o atendimento. O Escritório Digital nasce como uma prova de conceito para unificar tudo isso em uma experiência simples:

- Captação de leads via site → qualificação → vira pessoa → vira processo → entra no Kanban.
- Documentos organizados por pessoa e por processo.
- Agenda integrada para compromissos do dia a dia.
- Assistente de IA dentro do painel para dúvidas, rascunhos e consultas (com opção de modelos com acesso à web e retorno de fontes).


## Objetivo do projeto

- Demonstrar domínio prático de PHP puro + MySQL
- Aplicar conceitos reais de CRUD, controle de estado, integrações e UX
- Simular um sistema usado no dia a dia de um profissional
- Mostrar capacidade de pensar produto, não apenas código


## Funcionalidades

**1) Autenticação (login/cadastro/recuperação)
Fluxo completo de login, cadastro e recuperação de senha.**

- Validações de formulário no front (e-mail e critérios de senha) + validações no backend.

- Proteções de MVP: verificação de e-mail duplicado, uso de transações e prepared statements, e limitação de criação de conta e recuperação de senha por IP.


**2) Dashboard (Painel)**

Visão gerencial com gráficos/indicadores, como:

- Novos processos por período.
- Atividades do mês.
- Honorários mensais.
- Distribuições por área de atuação, etapa do CRM e resultado de processos.

**3) CRM — Kanban de Processos**

O módulo de CRM é o coração do sistema, baseado em um quadro Kanban, ideal para controle visual e priorização de tarefas.

- Cards com informações essenciais:
    - Referência do contrato/processo
    - Tipo de ação
    - Nome do cliente
    - Valor da causa e honorários
    - Probabilidade de sucesso 

- Ações rápidas:
    - Acessar ficha do processo
    - Adicionar anotações
    - Encerrar processo

**Diferencial**

- As etapas do Kanban são totalmente configuráveis:
    - Adicionar ou remover etapas
    - Reordenar colunas via drag-and-drop

Com isso, cada cliente adapta o sistema kanban para o seu processo de atendimento ao cliente.

**4) Pessoas (clientes e parte contrária)**
- Contadores (ativos / clientes / contrários).
- Busca, filtro e ordenação.
- Ações por pessoa: ficha, documentos, editar, excluir.
- Atalho de WhatsApp: se houver número cadastrado, um clique leva direto à conversa.

**5) Fichas e Documentos (Pessoa e Processo)**

- Ficha da pessoa: dados pessoais, contato, foto, endereço e documentos em abas.
- Ficha do processo: dados do caso, números, valores, contingenciamento, etapa do Kanban, observações e documentos.
- Upload de documentos com drag-and-drop, listagem/galeria, abertura (PDF/imagem) e exclusão.
- Vinculação de documentos a pessoa ou ao processo


**6) Processos (cadastro + listagem)**

- Indicadores por chance de sucesso (alta/média/baixa).
- Busca por tipo ou grupo de ação, com filtros e ordenações.
- Cada processo pode: abrir ficha, gerenciar documentos, encerrar/reativar, editar e excluir.
- No cadastro, o processo já nasce vinculado a uma etapa do Kanban (coluna inicial).

**7) Agenda (compromissos)**

- Visualizações (mês/semana/dia/lista).
- CRUD de compromissos com título, descrição e opção de dia inteiro ou horário definido.
- Etiquetas por cor (azul/amarelo/vermelho) para organização visual.
- Modal de detalhes do compromisso, com ações de edição e remoção conforme o fluxo.


**8) Site (landing page) + Depoimentos**

O “lado público” do escritório é gerado e configurado pelo próprio sistema.

Seleção de modelos (no MVP, 1 modelo funcional).

Configuração dinâmica de conteúdo:
- Fontes
- Banner
- Foto
- Frases
- CTAs
- Contatos
- Áreas de atuação
- Seção “sobre”

Personalização visual:
- Cor primária
- Cor secundária

Campo avançado para estilização extra via CSS e JS (modo “power user”).

Depoimentos:
- CRUD de depoimentos exibidos na landing page.

**9) Leads (qualificação antes do CRM)**

Leads entram via formulário do site.

O advogado visualiza:
- Nome
- Contato
- E-mail
- Mensagem

Ações disponíveis:
- Criar pessoa (qualifica o lead e adiciona à base)
- Excluir lead

Notificação por e-mail quando um novo lead chega, evitando dependência de acesso ao painel.


**10) Módulo IA (chat multi-modelos)**

Módulo de IA com foco em produtividade jurídica.

Chat com múltiplos provedores:
- OpenAI
- Groq
- Perplexity

Troca de modelos dentro da mesma conversa, mantendo o contexto.

Histórico de conversas:
- Criar
- Acessar
- Excluir

Modelos disponíveis:

- **Groq**
  - Llama-3.3-70B
  - Kimi K2 Instruct (kimi-k2-instruct-0905)
  - GPT-OSS-120B (Reasoning)
  - Compound-mini (Acesso à Web)

- **OpenAI**
  - GPT-5-nano (Acesso à Web)

- **Perplexity**
  - Sonar (Pesquisa jurídica)

Modelos com:
- Acesso à internet
- Pesquisa aprofundada (ex: Perplexity)
- Exibição de fontes quando disponível

Entrada por texto e áudio, com transcrição via API nativa do navegador.



## Tecnologias e bibliotecas

### Backend
- PHP (puro)
- MySQL

### Frontend
- HTML
- CSS
- JavaScript
- jQuery (interações e integrações com plugins)

### Bibliotecas / Plugins
- Sortable.js (drag-and-drop para ordenar etapas e colunas do Kanban)
- Dropzone.js (upload de documentos com drag-and-drop)
- Select2 (selects dinâmicos e pesquisáveis no cadastro)
- FullCalendar (agenda e compromissos)
- SweetAlert (feedback visual e confirmações)
- canvas-confetti (efeito de boas-vindas 1x/dia via localStorage)
- Lucide Icons + Font Awesome (ícones)

---

## UX e padrões do sistema

- Busca global no topo: pesquisa por pessoa e/ou referência do processo.
- Breadcrumbs para orientação de navegação.
- Feedback consistente em ações críticas (salvar, excluir, encerrar, etc.).
- Fluxos em etapas (ex.: cadastro com passos) para reduzir atrito e aumentar taxa de conclusão.


## Observações importantes (MVP)

Por se tratar de um MVP:

- Não foi implementado o padrão MVC.
- As telas não foram totalmente responsivas.
- Hash de senha e camadas avançadas de segurança ficaram para uma próxima etapa.

Essas decisões foram conscientes, priorizando:
- Entrega funcional
- Validação da ideia
- Consolidação de lógica e fluxo

---

## 🚀 Próximos passos (Roadmap)

- Refatoração para MVC
- Implementação de hash de senha
- Responsividade completa
- Controle de permissões
- Métricas avançadas
- Novos modelos de site
- Evolução do módulo de IA

---

## 👨‍💻 Autor

Projeto desenvolvido por Paulo Vitor.
Desenvolvedor com foco em backend e lógica de negócio, especializado na construção de sistemas reais orientados à resolução de problemas práticos.
