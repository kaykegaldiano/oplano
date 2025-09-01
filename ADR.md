# Architecture Decision Records (ADRs)

Este documento registra decisões arquiteturais relevantes, seus contextos, alternativas, consequências e relação com os requisitos da avaliação.

> A avaliação exige: três áreas de uso separadas (menus, páginas, dados e ações), domínio Alunos/Turmas/Matrículas com relacionamento no fluxo via Filament Relation Manager, formulário dependente Estado→Cidade com tratamento de falhas, elemento global via Render Hooks, dashboard com métricas úteis e deploy público com seed.

---

## ADR-001 — Autenticação & Segmentação de Perfis

**Decisão**  
Usar **Spatie/laravel-permission** para gerenciamento de **roles** e **permissions**.  
Criar **três painéis independentes no Filament** (`/admin`, `/cs`, Criar três painéis independentes (`/admin`, `/cs`, `/monitor`), cada um com middleware `role:*` e branding distinto.   
Policies reforçam a segurança no nível de dados.

**Alternativas**
- Guardar papel em campo `users.role` (enum/string).
- Um painel único com filtros condicionais.

**Justificativa**
- Spatie Permission é padrão de mercado no ecossistema Laravel.
- Facilita granularidade (roles + permissions específicas).
- Requisito exige escopos claramente separados (menus, páginas, dados, ações).

**Consequências**
- Requer seed inicial de roles/permissions.
- Middleware `role:*` simplifica controle de acesso a painéis.
- Permite extensibilidade futura (novos papéis/ações sem mudar schema).

---

## ADR-002 — Relacionamento Aluno⇄Turma no Fluxo

**Decisão**  
Usar `enrollments` como pivot enriquecido (status, datas, motivo). Matrículas atribuídas/removidas via **Relation Managers** em Student e Class.

**Justificativa**
- Atende ao requisito de “gestão no fluxo”.
- Evita “gambiarras” fora do Filament.

**Consequências**
- Regras centralizadas no pivot.
- Admin/CS têm controle total; Monitor apenas conclui (permission dedicada).

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
Botão/modal “Observação Rápida” via **Render Hook**, disponível em todos os painéis.  
Registros ficam na tabela `observations` e podem ser visualizados no `ObservationResource`.

**Justificativa**
- Requisito de “elemento global reutilizável”.
- Implementação limpa, sem duplicar código.

**Consequências**
- Acesso rápido a anotações, aumenta usabilidade.
- Observações isoladas por usuário (segurança garantida por Policy).

---

## ADR-006 — Dashboards Operacionais

**Decisão**  
Widgets distintos por painel:
- Admin → visão macro.
- CS → pendências/ativos/concluídos.
- Monitor → “minhas turmas hoje” e pendências.

**Justificativa**
- Requisito de “dashboard com métricas úteis, não decorativas”.
- Dá relevância prática para cada perfil.

**Consequências**
- Implementação específica por painel.
- Flexível para evoluir (novos indicadores).

---
