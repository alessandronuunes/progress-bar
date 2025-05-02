<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ProgressNotificationService;
use Livewire\Attributes\On;

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
