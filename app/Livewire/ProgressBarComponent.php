<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Filament\Notifications\Notification;
use App\Services\ProgressNotificationService;

class ProgressBarComponent extends Component
{
    public bool $status = false;
    public array $notifications = [];
    // public string|array $notificationId = '';

     // Método executado quando o componente é montado
     public function mount()
     {
        $this->getNotifications();
        // se houver notificaçoes e se ela for maior que zero então mostra a barra de progresso
        if (!empty($this->notifications) && count($this->notifications) > 0) {
            $this->status = true;
        }
     }
    // Método para mostrar a barra de progresso
    #[On('showProgressBar')]
    public function showProgressBar()
    {
        $this->getNotifications();
        $this->status = true;
        // $this->notificationId = $notificationId;
        
    }

    // Método para ocultar a barra de progresso
    public function hideProgressBar()
    {
        $this->status = false;
    }

    // Método para excluir uma notificação específica
    public function deleteNotification($notificationId)
    {
        // antes de excluir eu verifico se a progresso esta em 100%
        if ($this->notifications[$notificationId]['progress'] != 100) {
            // exclui a notificação
            // disparo uma notificao que nao pode ser excluida pois ainda nao acabou a tarefa
            Notification::make()
                ->title('Não é possível excluir a notificação, ainda não acabou a tarefa!')
                ->warning()
                ->send();
            return;
        }
        $progressService = app(ProgressNotificationService::class);
        $progressService->deleteNotification($notificationId);
        $this->getNotifications();
        
        // Se não houver mais notificações, ocultar o componente
        if (empty($this->notifications)) {
            $this->status = false;
        }
    }

    // Método para buscar notificações do Redis
    public function getNotifications()
    {
        $progressService = app(ProgressNotificationService::class);
        $this->notifications = $progressService->getUserNotifications(auth()->id() ?? 0);
    }

    public function render()
    {
        return view('livewire.progress-bar-component');
    }
}
