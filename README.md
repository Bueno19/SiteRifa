🎟️ Rifa Gamer — Laravel + PostgreSQL

Sistema de rifa online desenvolvido para fins de estudo, portfólio e prática de desenvolvimento full-stack utilizando Laravel.

⚠️ Aviso importante:
Este projeto não é uma plataforma oficial de rifas, não possui integração real de pagamentos e foi criado apenas para fins educacionais, demonstração de portfólio e aprendizado.

📸 Preview

Sistema moderno com:

Landing page
Login e cadastro
Dashboard do usuário
Reserva de números
Área administrativa
Integração com WhatsApp
PostgreSQL
TailwindCSS
🚀 Tecnologias utilizadas
Backend
PHP 8+
Laravel 11
PostgreSQL
Frontend
Blade
TailwindCSS
Vite
JavaScript
⚙️ Funcionalidades
Usuário
Cadastro e login
Escolha de números
Reserva de números
Histórico de reservas
Dashboard personalizado
Integração com WhatsApp
Administrador
Painel administrativo
Visualização de reservas
Confirmação de pagamentos
Cancelamento de reservas
🗄️ Banco de dados

O projeto utiliza PostgreSQL.

Principais tabelas
users
numeros
reservas
reserva_numeros
rifas
📦 Instalação
1. Clone o projeto
git clone https://github.com/SEU-USUARIO/SEU-REPOSITORIO.git
2. Entre na pasta
cd SiteHX
3. Instale as dependências
composer install
npm install
4. Configure o .env

Exemplo:

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
5. Gere a chave
php artisan key:generate
6. Rode as migrations
php artisan migrate:fresh
7. Gere os números da rifa
php artisan tinker --execute="collect(range(1, 4500))->chunk(500)->each(function ($chunk) { \Illuminate\Support\Facades\DB::table('numeros')->insert($chunk->map(fn ($n) => ['numero' => $n, 'status' => 'disponivel', 'created_at' => now(), 'updated_at' => now()])->toArray()); });"
8. Inicie o projeto
Backend
php artisan serve
Frontend
npm run dev
🔐 Usuário administrador

Para transformar um usuário em administrador:

UPDATE users
SET role = 'admin'
WHERE email = 'seuemail@email.com';
📁 Estrutura do projeto
app/
resources/views/
routes/
database/
public/
📌 Observações
Projeto criado apenas para fins educacionais
Não possui gateway de pagamento real
Não utilizar em produção sem melhorias de segurança
Algumas funcionalidades ainda podem estar em desenvolvimento
👨‍💻 Autor

Desenvolvido por Felipe Bueno 🚀

Full-stack Developer
Estudante de ADS
⭐ Objetivo do projeto

Esse projeto foi desenvolvido para:

estudo de Laravel
prática com PostgreSQL
autenticação
manipulação de banco
arquitetura MVC
construção de sistemas web completos
composição de portfólio profissional
