# Painel de Gestão Acadêmica (Laravel 12 + Livewire 3 + Filament 4)

Sistema acadêmico com **três painéis separados** para perfis distintos:
- **Admin Global**: acesso total, CRUD completo.
- **Customer Success (CS)**: gestão de alunos, turmas e matrículas, sem exclusões críticas.
- **Monitor**: acesso somente às suas turmas atribuídas (via pivot `class_user`), podendo visualizar aulas e concluir matrículas dessas turmas.

## 🚀 Tecnologias
- PHP 8.3+, Laravel 12
- Livewire 3, Filament 4
- MySQL 8+ (ou compatível)
- Redis (cache recomendado)

---

## ⚙️ Setup Local
```bash
git clone <repo_url>
cd <repo_dir>
cp .env.example .env
composer install
php artisan key:generate
npm install
```

## Configure o `.env` com DB/Redis. Exemplo mínimo:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=oplano_app
DB_USERNAME=root
DB_PASSWORD=
```

## Rodar migrations e seeds:
```bash
php artisan migrate --seed
```

## Iniciar servidor:
```bash
composer run dev
```

# Acessar os painéis:
- Admin: http://localhost:8000/admin
- CS: http://localhost:8000/cs
- Monitor: http://localhost:8000/monitor

## 🔑 Credenciais de Teste (seeders)
- Admin → `admin@test.com` / `password`
- CS → `cs@test.com` / `password`
- Monitor → `monitor@test.com` / `password`

Cada usuário recebe o papel correspondente via Spatie Permission.

## 📚 Domínio Essencial
- Alunos (students), Turmas (classes), Matrículas (enrollments)
- Monitores atribuídos às turmas via pivot class_user.
- Matrículas gerenciadas no fluxo (Relation Managers em Aluno/Turma).
- Views específicas para monitores (ex.: detalhes da turma com alunos e status das matrículas).

## 📝 Funcionalidades
- Form dependente Estado→Cidade: integração com API IBGE, cache de 24h e fallback local.
- Elemento Global: Observação Rápida disponível em todos os painéis (Render Hook).
- Dashboards distintos:
  - Admin → visão macro (alunos ativos, turmas, matrículas, gráfico 30d).
  - CS → foco em pendências, ativas e concluídas.
  - Monitor → visão das próprias turmas do dia e pendências de matrícula.
- Customização visual: cores distintas por painel (branding leve).
- Gerenciamento de roles e permissions: via Spatie, com granularidade para ações específicas (ex.: concluir matrícula).

## 🛠️ Qualidade
- Migrations, seeders e factories organizados.
- `.env.example` incluso.
- Spatie Permission para autorização e segmentação de escopos.
- Policies integradas a roles/permissions.
- Índices em colunas críticas, validações server-side.
- Soft Deletes e auditoria básica (`created_by`, `updated_by`).

## 🌐 Deploy
Na entrega, serão disponibilizados:
- URL de produção pública
- Credenciais de cada perfil
- Base populada via seeders
