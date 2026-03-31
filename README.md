🚀 Gerenciador de Tarefas com Laravel

Aplicação web desenvolvida em Laravel para gerenciamento de tarefas (To-Do List), com suporte a:

CRUD completo (Create, Read, Update, Delete)
Autenticação de usuários
Isolamento de dados por usuário
Integração com MariaDB
Ambiente configurado para Arch Linux

Este projeto serve como base reutilizável para sistemas similares.

📌 Tecnologias Utilizadas
PHP
Laravel
MariaDB
Blade (Template Engine)
Composer
⚙️ 1. Configuração do Ambiente (Arch Linux)
🔧 Instalação do MariaDB
sudo pacman -S mariadb
sudo mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
sudo systemctl enable --now mariadb
🧩 Extensões do PHP

Edite o arquivo:

/etc/php/php.ini

E descomente:

extension=pdo_mysql
extension=zip
extension=xml
extension=mbstring
extension=curl
extension=fileinfo
📦 2. Instalação do Projeto
composer create-project laravel/laravel nome-do-projeto
cd nome-do-projeto
🗄️ Banco de Dados

Acesse o MariaDB:

sudo mariadb -u root

Crie o banco:

CREATE DATABASE nome_do_banco;
EXIT;
🔑 Configuração do .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=root
DB_PASSWORD=
🏗️ 3. Estrutura Base (MVC)
Criando Model + Migration + Controller
php artisan make:model Tarefa -mcr
🧱 Migration (Tabela tarefas)
public function up() {
    Schema::create('tarefas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('nome');
        $table->text('descricao')->nullable();
        $table->enum('status', ['incompleta', 'em andamento', 'concluida'])->default('incompleta');
        $table->date('data_finalizacao')->nullable();
        $table->timestamps();
    });
}
▶️ Rodar Migration
php artisan migrate
🧠 Model (Mass Assignment)
protected $fillable = [
    'nome',
    'descricao',
    'status',
    'data_finalizacao',
    'user_id'
];
🔐 4. Autenticação
Criar Controller
php artisan make:controller AuthController
Métodos principais
login() → autentica com Auth::attempt
register() → cria usuário com Hash::make
logout() → encerra sessão
⚠️ Importante
use Illuminate\Support\Facades\Auth;
🛣️ 5. Rotas
use App\Http\Controllers\TarefasController;
use App\Http\Controllers\AuthController;
👤 Rotas públicas
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/cadastro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/cadastro', [AuthController::class, 'register'])->name('register.post');
});
🔒 Rotas protegidas
Route::middleware('auth')->group(function () {
    Route::resource('tarefas', TarefasController::class);
});
🚪 Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
🔐 6. Segurança e Isolamento de Dados
Listagem
$tarefas = Tarefa::where('user_id', Auth::id())->latest()->get();
Criação
$dados['user_id'] = Auth::id();
Tarefa::create($dados);
Proteção (edit, update, delete)
if ($tarefa->user_id !== Auth::id()) {
    abort(403, 'Acesso negado.');
}
🎨 7. Views com Blade
Layout Base

resources/views/layouts/app.blade.php

@yield('content')
Uso nas páginas
@extends('layouts.app')

@section('content')
    <!-- Conteúdo -->
@endsection
📌 Boas práticas em formulários
Sempre usar:
@csrf
Para PUT/DELETE:
@method('PUT')
@method('DELETE')
Preservar dados:
old('campo')
🚀 8. Executando o Projeto (Arch Linux)

Evite:

php artisan serve

Use:

php -S 127.0.0.1:8000 -t public/
🌐 Acesso
http://127.0.0.1:8000/login
📚 Estrutura do Projeto
app/
 ├── Models/
 ├── Http/Controllers/
database/
 ├── migrations/
resources/
 ├── views/
routes/
 ├── web.php
✅ Funcionalidades
Cadastro e login de usuários
CRUD de tarefas
Controle de acesso por usuário
Validação de formulários
Proteção contra CSRF
📈 Possíveis Melhorias
Implementar validação com Form Requests
Adicionar paginação
Criar API REST (routes/api.php)
Adicionar testes automatizados
Implementar frontend com Vue ou React
👨‍💻 Autor

Projeto desenvolvido para fins de estudo e prática com Laravel.
