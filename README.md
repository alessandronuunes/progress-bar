# Projeto Barra de Progresso com FilamentPHP e Livewire

Este projeto demonstra a implementação de uma barra de progresso em tempo real dentro de um painel FilamentPHP. Ele utiliza Livewire para atualizações dinâmicas, Redis para armazenar o estado do progresso e Jobs em fila do Laravel para processamento em segundo plano.

## Funcionalidades

*   Painel administrativo construído com FilamentPHP.
*   Ação customizada em um Resource do Filament (`UserResource`) para disparar um job em fila.
*   Job (`RecalculateUserJob`) que simula um processo demorado e atualiza o progresso.
*   Serviço (`ProgressNotificationService`) para gerenciar o estado das notificações de progresso usando Redis.
*   Componente Livewire (`ProgressBarComponent`) que busca e exibe o progresso das notificações ativas usando polling.
*   Exibição do nome do usuário que iniciou o job.
*   Possibilidade de remover/cancelar a visualização de uma notificação de progresso.

## Pré-requisitos

Antes de começar, garanta que você tenha o seguinte instalado em seu ambiente (macOS):

*   PHP (versão compatível com o Laravel usado no projeto, ex: 8.1+)
*   Composer
*   Node.js e npm
*   Servidor Redis
*   Um servidor web local (Ex: Laravel Herd, Valet, ou `php artisan serve`)
*   Um gerenciador de filas configurado (Ex: Supervisor) ou rodar o worker manualmente.

## Instalação

Siga os passos abaixo para configurar o projeto localmente:

1.  **Clonar o repositório:**
    ```bash
    git clone https://github.com/alessandronuunes/progress-bar.git
    cd progress-bar
    ```

2.  **Instalar dependências PHP:**
    ```bash
    composer install
    ```

3.  **Instalar dependências Node.js:**
    ```bash
    npm install
    ```

4.  **Compilar assets:**
    ```bash
    npm run build
    ```
    *(Ou `npm run dev` para desenvolvimento)*

5.  **Configurar ambiente:**
    *   Copie o arquivo de exemplo de ambiente:
        ```bash
        cp .env.example .env
        ```
    *   Gere a chave da aplicação:
        ```bash
        php artisan key:generate
        ```
    *   Edite o arquivo `.env` e configure as variáveis de ambiente, especialmente:
        *   `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (configurações do seu banco de dados)
        *   `REDIS_HOST`, `REDIS_PASSWORD`, `REDIS_PORT` (configurações do seu servidor Redis)
        *   `QUEUE_CONNECTION=redis` (para usar Redis como driver da fila)

6.  **Executar as migrações do banco de dados:**
    ```bash
    php artisan migrate
    ```

7.  **Criar o usuário administrador do Filament:**
    *   Execute o comando abaixo e siga as instruções no terminal para criar seu primeiro usuário:
        ```bash
        php artisan make:filament-user
        ```
    *   Você será solicitado a fornecer nome, e-mail e senha para o novo usuário administrador.

## Executando a Aplicação

1.  **Iniciar o servidor web:**
    *   Se estiver usando Laravel Herd ou Valet, o site deve estar acessível automaticamente.
    *   Caso contrário, use:
        ```bash
        php artisan serve
        ```

2.  **Iniciar o servidor Redis:**
    *   Certifique-se de que seu servidor Redis esteja rodando. Se instalado via Homebrew:
        ```bash
        brew services start redis
        ```

3.  **Iniciar o Queue Worker:**
    *   Para processar os jobs em segundo plano, execute o worker. Para desenvolvimento, você pode rodar:
        ```bash
        php artisan queue:work --queue=default
        ```
    *   **Importante:** Para produção, configure um gerenciador de processos como o Supervisor para manter o worker rodando de forma contínua e confiável.

## Como Usar

1.  Acesse o painel administrativo do Filament (normalmente em `/admin`).
2.  Faça login com um usuário (ex: `test@example.com` / `password` se você rodou o seeder).
3.  Navegue até o recurso "Users" no menu lateral.
4.  Na tabela de usuários, localize a coluna de ações e clique no ícone de gráfico ("Recalcular") para um usuário específico.
5.  Preencha as datas (opcional, já vêm com valores padrão) e confirme a ação.
6.  Uma barra de progresso aparecerá no topo da página, mostrando o andamento do recálculo para aquele usuário, incluindo quem iniciou o processo.
7.  A barra será atualizada automaticamente até atingir 100%.
8.  Você pode clicar no ícone 'X' ao lado da barra de progresso para removê-la da visualização.

## Componentes Chave

*   **`app/Filament/Resources/UserResource.php`**: Contém a definição da tabela de usuários e a ação customizada `recalc`.
*   **`app/Jobs/RecalculateUserJob.php`**: O job que é despachado para a fila e simula o trabalho em segundo plano, atualizando o progresso.
*   **`app/Services/ProgressNotificationService.php`**: Serviço responsável por criar, atualizar e remover as informações de progresso no Redis.
*   **`app/Livewire/ProgressBarComponent.php`**: Componente Livewire que busca periodicamente (`wire:poll`) as notificações no Redis e as renderiza.
*   **`resources/views/livewire/progress-bar-component.blade.php`**: A view do componente Livewire, responsável pela estrutura HTML e estilos (Tailwind CSS) da barra de progresso.
*   **`app/Providers/AppServiceProvider.php`**: Registra o hook do Filament para renderizar o componente Livewire (`PanelsRenderHook::CONTENT_START`).
*   **Redis**: Utilizado como backend para armazenar o estado das notificações de progresso de forma temporária.
*   **Laravel Queues**: Sistema de filas do Laravel para processamento assíncrono dos jobs.
