# 🎟️ Rifa Gamer — Laravel + PostgreSQL

Sistema de rifa online desenvolvido para fins de estudo, portfólio e prática de desenvolvimento full-stack utilizando Laravel.

⚠️ AVISO IMPORTANTE:
Este projeto NÃO é uma plataforma oficial de rifas.
Não possui integração real de pagamentos e foi criado apenas para fins educacionais, demonstração de portfólio e aprendizado.

------------------------------------------------------------
📸 PREVIEW
------------------------------------------------------------

Sistema moderno com:
- Landing page
- Login e cadastro
- Dashboard do usuário
- Reserva de números
- Área administrativa
- Integração com WhatsApp
- PostgreSQL
- TailwindCSS

------------------------------------------------------------
🚀 TECNOLOGIAS UTILIZADAS
------------------------------------------------------------

BACKEND
- PHP 8+
- Laravel 11
- PostgreSQL

FRONTEND
- Blade
- TailwindCSS
- Vite
- JavaScript

------------------------------------------------------------
⚙️ FUNCIONALIDADES
------------------------------------------------------------

USUÁRIO
- Cadastro e login
- Escolha de números
- Reserva de números
- Histórico de reservas
- Dashboard personalizado
- Integração com WhatsApp

ADMINISTRADOR
- Painel administrativo
- Visualização de reservas
- Confirmação de pagamentos
- Cancelamento de reservas

------------------------------------------------------------
🗄️ BANCO DE DADOS
------------------------------------------------------------

O projeto utiliza PostgreSQL.

Principais tabelas:
- users
- numeros
- reservas
- reserva_numeros
- rifas

------------------------------------------------------------
📦 INSTALAÇÃO
------------------------------------------------------------

1. Clone o projeto

git clone https://github.com/SEU-USUARIO/SEU-REPOSITORIO.git

2. Entre na pasta

cd SiteHX

3. Instale as dependências

composer install
npm install

------------------------------------------------------------
⚙️ CONFIGURAÇÃO .ENV
------------------------------------------------------------

APP_NAME=SiteHX
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=siteHX
DB_USERNAME=postgres
DB_PASSWORD=sua_senha

RAFFLE_WHATSAPP=5511999999999

------------------------------------------------------------
🔑 GERAR APP KEY
------------------------------------------------------------

php artisan key:generate

------------------------------------------------------------
🧱 MIGRATIONS
------------------------------------------------------------

php artisan migrate:fresh

------------------------------------------------------------
🎟️ GERAR NÚMEROS DA RIFA
------------------------------------------------------------

php artisan tinker --execute="collect(range(1, 4500))->chunk(500)->each(function ($chunk) { \Illuminate\Support\Facades\DB::table('numeros')->insert($chunk->map(fn ($n) => ['numero' => $n, 'status' => 'disponivel', 'created_at' => now(), 'updated_at' => now()])->toArray()); });"

------------------------------------------------------------
🚀 INICIAR PROJETO
------------------------------------------------------------

BACKEND
php artisan serve

FRONTEND
npm run dev

------------------------------------------------------------
🔐 CRIAR ADMINISTRADOR
------------------------------------------------------------

UPDATE users
SET role = 'admin'
WHERE email = 'seuemail@email.com';

------------------------------------------------------------
📁 ESTRUTURA DO PROJETO
------------------------------------------------------------

app/
resources/views/
routes/
database/
public/

------------------------------------------------------------
📌 OBSERVAÇÕES
------------------------------------------------------------

- Projeto criado apenas para fins educacionais
- Não possui gateway de pagamento real
- Não utilizar em produção sem melhorias de segurança
- Algumas funcionalidades ainda podem estar em desenvolvimento

------------------------------------------------------------
👨‍💻 AUTOR
------------------------------------------------------------

Desenvolvido por Felipe Bueno 🚀

- Full-stack Developer
- Estudante de ADS

------------------------------------------------------------
⭐ OBJETIVO DO PROJETO
------------------------------------------------------------

Esse projeto foi desenvolvido para:
- estudo de Laravel
- prática com PostgreSQL
- autenticação
- manipulação de banco
- arquitetura MVC
- construção de sistemas web completos
- composição de portfólio profissional
