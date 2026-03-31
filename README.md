🚀 Base Laravel: CRUD com Autenticação (Guia de Consulta)
Este projeto é um Gerenciador de Tarefas construído em Laravel. Este arquivo serve como um guia passo a passo para recriar projetos semelhantes, com foco em um CRUD completo, autenticação nativa e configuração no Arch Linux com MariaDB.

🛠️ 1. Preparando o Ambiente (Específico para Arch Linux)
Antes de criar o projeto, garanta que o ambiente base está configurado corretamente, pois o Arch Linux exige configurações manuais.

Extensões do PHP
Edite o arquivo /etc/php/php.ini e certifique-se de que as seguintes extensões estão descomentadas (sem o ; no início):

Ini, TOML
extension=pdo_mysql
extension=zip
extension=xml
extension=mbstring
extension=curl
extension=fileinfo
Inicializando o MariaDB
Se for a primeira vez rodando o MariaDB no sistema:

Bash
sudo pacman -S mariadb
sudo mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
sudo systemctl enable --now mariadb
📦 2. Criando o Projeto e Banco de Dados
Crie o projeto Laravel via Composer:

Bash
composer create-project laravel/laravel nome-do-projeto
cd nome-do-projeto
Crie o banco de dados no terminal do MariaDB (sudo mariadb -u root):

SQL
CREATE DATABASE nome_do_banco;
EXIT;
Configure o arquivo .env na raiz do projeto:

Snippet de código
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=root
DB_PASSWORD=
🏗️ 3. Criando a Estrutura Base (Model, Migration, Controller)
Use o comando mágico do Laravel que cria o Model, a Migration e um Controller já com todos os métodos do CRUD (index, create, store, edit, update, destroy):

Bash
php artisan make:model Tarefa -mcr
Configurando a Migration
(Local: database/migrations/...create_tarefas_table.php)

Adicione as colunas da tabela, incluindo a chave estrangeira para o usuário dono do registro:

PHP
public function up() {
    Schema::create('tarefas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relacionamento com usuário
        $table->string('nome');
        $table->text('descricao')->nullable();
        $table->enum('status', ['incompleta', 'em andamento', 'concluida'])->default('incompleta');
        $table->date('data_finalizacao')->nullable();
        $table->timestamps();
    });
}
Rode a migration para criar a tabela no banco:

Bash
php artisan migrate
Configurando o Model
(Local: app/Models/Tarefa.php)

Libere os campos para inserção em massa (Mass Assignment):

PHP
protected $fillable = ['nome', 'descricao', 'status', 'data_finalizacao', 'user_id'];
🔐 4. Configurando a Autenticação Nativa
Para não depender de pacotes que engessam o layout, crie um controller de autenticação próprio:

Bash
php artisan make:controller AuthController
Métodos essenciais no AuthController:
login(Request $request): Usa Auth::attempt($credenciais) para logar.

register(Request $request): Cria o User::create(...) fazendo hash da senha com Hash::make(), e logo depois loga o usuário com Auth::login($user).

logout(Request $request): Usa Auth::logout() e invalida a sessão.

Importante: Sempre importe a Facade correta no topo dos controllers. Cuidado com erros de digitação (Illuminate tem dois Ls!).

PHP
use Illuminate\Support\Facades\Auth;
🛣️ 5. Protegendo Rotas e Isolando Dados (routes/web.php)
Separe as rotas públicas das rotas protegidas usando Middlewares. Use o Route::resource para gerar todas as rotas do CRUD automaticamente.

PHP
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarefasController;
use App\Http\Controllers\AuthController;

// Rotas de Visitantes (Não logados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/cadastro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/cadastro', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas Protegidas (Só acessa logado)
Route::middleware('auth')->group(function () {
    Route::resource('tarefas', TarefasController::class);
});
Isolamento no Controller (TarefasController.php)
Garanta que o usuário veja e edite apenas o que é dele:

PHP
// No método index() (Listar):
$tarefas = Tarefa::where('user_id', Auth::id())->latest()->get();

// No método store() (Criar):
$dados['user_id'] = Auth::id();
Tarefa::create($dados);

// Nos métodos edit(), update() e destroy() (Proteger):
if ($tarefa->user_id !== Auth::id()) abort(403, 'Acesso negado.');
🎨 6. Trabalhando com Views (Blade)
Use Layouts para não repetir código HTML, CSS e scripts:

Crie resources/views/layouts/app.blade.php.

Use @yield('content') onde o conteúdo deve aparecer.

Nas outras telas, use @extends('layouts.app') no topo e @section('content') ... @endsection ao redor do código.

Formulários no Laravel (Regras de Ouro):
Sempre inclua @csrf dentro da tag <form> para evitar erro 419 (Page Expired).

Formulários HTML só aceitam GET e POST. Para edição (update) e exclusão (destroy), use <form method="POST"> e adicione a diretiva @method('PUT') ou @method('DELETE') logo abaixo do @csrf.

Use old('nome_do_campo') nos inputs para não apagar o que o usuário digitou se a validação falhar.

🚀 7. Rodando a Aplicação (O Bypass do Arch Linux)
No Arch Linux, o comando padrão php artisan serve pode conflitar com o Xdebug ativado e "crashar" silenciosamente (inicia e para sozinho).

Para rodar o projeto sem problemas, inicie o servidor embutido do PHP diretamente apontando para a pasta public:

Bash
php -S 127.0.0.1:8000 -t public/
Acesse no navegador: http://127.0.0.1:8000/login

🆘 Troubleshooting (Resolução de Problemas Comuns)
Comandos Artisan morrendo em silêncio (ex: route:list): O Xdebug está quebrando o terminal. Rode o comando desativando ele:
php -d xdebug.mode=off artisan route:list.

Erro Class "App\Http\Controllers\Auth" not found: Esqueceu de importar a Facade do Auth no topo do Controller, ou importou com erro de digitação (Iluminate com um "L" em vez de Illuminate).

Erro de PDO ou Driver SQL não encontrado: O php.ini do sistema está com as extensões comentadas.

Ver logs de erro ocultos:
tail -n 20 storage/logs/laravel.log.
