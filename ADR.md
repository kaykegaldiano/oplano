# Architecture Decision Records (ADRs)

Este documento registra decisões arquiteturais relevantes, seus contextos, alternativas, consequências e relação com os requisitos da avaliação.

> A avaliação exige: três áreas de uso separadas (menus, páginas, dados e ações), domínio Alunos/Turmas/Matrículas com relacionamento no fluxo via Filament Relation Manager, formulário dependente Estado→Cidade com tratamento de falhas, elemento global via Render Hooks, dashboard com métricas úteis e deploy público com seed.

---

## ADR-001 — Autenticação & Segmentação de Perfis

**Decisão**  
Criar **três painéis independentes no Filament** (`/admin`, `/cs`, `/monitor`), cada um com cores/branding distintos e recursos ajustados. Policies reforçam a segurança no nível de dados.

**Alternativas**
- Um único painel com `shouldRegisterNavigation()` e filtros.
- Multi-guard no Laravel com painéis compartilhados.

**Justificativa**
- Requisito exige **escopos claramente separados** (menus, páginas, dados, ações).
- Painéis independentes simplificam UX e deixam a separação visual explícita.

**Consequências**
- Mais código boilerplate (pasta por painel).
- Clareza máxima para avaliadores e usuários.

---

## ADR-002 — Relacionamento Aluno⇄Turma no Fluxo

**Decisão**  
Usar `enrollments` como pivot enriquecido (status, datas, motivo). Matrículas atribuídas/removidas via **Relation Managers** em Student e Class.

**Justificativa**
- Atende ao requisito de “gestão no fluxo”.
- Evita “gambiarras” fora do Filament.

**Consequências**
- Regras centralizadas no pivot.
- UX consistente para Admin/CS.

---

## ADR-003 — Monitores vinculados a Turmas
**Decisão**  
Criar pivot `class_user` (`class_id`, `user_id`, `role_in_class='monitor'`).  
Painel do monitor lista apenas suas turmas; pode visualizar detalhes (`ViewClass`) e concluir matrículas dessas turmas.

**Justificativa**
- Requisito de **escopo restrito por perfil**.
- Evita acesso indevido a turmas de outros monitores.

**Consequências**
- Queries sempre filtradas por pivot.
- Painel do monitor enxuto, focado no dia a dia.

---

## ADR-004 — Estado → Cidade (dados dependentes)

**Decisão**  
Consumir **API IBGE** para Estados/Cidades, com **cache** de 24h e **fallback local** (seed pré-carregado) em caso de indisponibilidade/timeouts do serviço. UI exibe mensagens claras em falhas.

**Alternativas**
- Somente base local (sem API)
- Outra API de CEP/endereço

**Justificativa**
- Cumpre requisito de integração externa + tratamento de indisponibilidade.
- Dados relevantes ao domínio acadêmico (endereços de alunos).

**Consequências**
- Dependência externa mitigada por cache e fallback.
- Relevância direta ao contexto (endereços de alunos).

---

## ADR-005 — Elemento Global via Render Hooks

**Decisão**  
Botão/modal “Observação Rápida” injetado via **Render Hook** no topo do layout.  
Disponível em todos os painéis.

**Justificativa**
- Requisito de “elemento global reutilizável”.
- Implementação limpa, sem duplicar código.

**Consequências**
- Acesso rápido a anotações, aumenta usabilidade.

---

## ADR-006 — Dashboards Operacionais

**Decisão**  
Cada painel possui widgets diferentes:
- Admin → KPIs gerais e gráfico 30d.
- CS → status das matrículas (pendentes, ativas, concluídas).
- Monitor → “minhas turmas hoje” e pendências.

**Justificativa**
- Requisito de “dashboard com métricas úteis, não decorativas”.
- Dá relevância prática para cada perfil.

**Consequências**
- Implementações diferentes de widgets.
- UX sob medida para cada público.

---
