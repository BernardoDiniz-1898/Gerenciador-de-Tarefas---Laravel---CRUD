🚀 Base Laravel: CRUD com Autenticação (Guia de Consulta)

Este projeto é um Gerenciador de Tarefas construído em Laravel. Este arquivo serve como um guia passo a passo para recriar projetos semelhantes, com foco em um CRUD completo, autenticação nativa e configuração no Arch Linux com MariaDB.

🛠️ 1. Preparando o Ambiente (Específico para Arch Linux)
Antes de criar o projeto, garanta que o ambiente base está configurado corretamente, pois o Arch Linux exige configurações manuais.

Extensões do PHP
Edite o arquivo /etc/php/php.ini e certifique-se de que as seguintes extensões estão descomentadas (sem o ; no início):

<pre>
extension=pdo_mysql
extension=zip
extension=xml
extension=mbstring
extension=curl
extension=fileinfo'''
</pre>

Inicializando o MariaDB
Se for a primeira vez rodando o MariaDB no sistema:

<pre>
sudo pacman -S mariadb
sudo mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
sudo systemctl enable --now mariadb
</pre>	

📦 2. Criando o Projeto e Banco de Dados
Crie o projeto Laravel via Composer:
<pre>
composer create-project laravel/laravel nome-do-projeto
cd nome-do-projeto
</pre>
Crie o banco de dados no terminal do MariaDB (sudo mariadb -u root):
<pre>
CREATE DATABASE nome_do_banco;
EXIT;
</pre>

Configure o arquivo .env na raiz do projeto:
<pre>
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=root
DB_PASSWORD=
</pre>

🏗️ 3. Criando a Estrutura Base (Model, Migration, Controller)
Use o comando mágico do Laravel que cria o Model, a Migration e um Controller já com todos os métodos do CRUD (index, create, store, edit, update, destroy)

<pre>php artisan make:model Tarefa -mcr</pre>

Configurando a Migration

(Local: database/migrations/...create_tarefas_table.php)

Adicione as colunas da tabela, incluindo a chave estrangeira para o usuário dono do registro:

<pre>
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
</pre>

Rode a migration para criar a tabela no banco:

<pre>php artisan migrate</pre>

Configurando o Model
(Local: app/Models/Tarefa.php)

Libere os campos para inserção em massa (Mass Assignment):
<pre>protected $fillable = ['nome', 'descricao', 'status', 'data_finalizacao', 'user_id'];</pre>

4. Configurando a Autenticação Nativa
Para não depender de pacotes que engessam o layout, crie um controller de autenticação próprio:

<pre>php artisan make:controller AuthController</pre>
