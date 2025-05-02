<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Models\User;

class ProgressNotificationService
{
    /**
     * Chave Redis para armazenar as notificações
     */
    const NOTIFICATION_KEY = 'progress::notifications';

    /**
     * Adiciona uma nova notificação de progresso
     * 
     * @param string $title Título da notificação
     * @param int $userId ID do usuário
     * @param int $progress Valor do progresso (0-100)
     * @return string ID da notificação
     */
    public function addNotification(string $title, int $userId, int $progress = 0)
    {
        $notificationId = uniqid('notification_');
        
        $notification = [
            'id' => $notificationId,
            'title' => $title,
            'user_id' => $userId,
            'progress' => $progress,
            'created_at' => now()->timestamp
        ];
        
        $notifications = $this->getNotifications();
        $notifications[$notificationId] = $notification;
        
        Redis::set(self::NOTIFICATION_KEY, json_encode($notifications));
        
        return $notificationId;
    }
    
    /**
     * Atualiza o progresso de uma notificação
     * 
     * @param string $notificationId ID da notificação
     * @param int $progress Novo valor do progresso (0-100)
     * @return bool
     */
    public function updateProgress(string $notificationId, int $progress)
    {
        $notifications = $this->getNotifications();
        
        if (!isset($notifications[$notificationId])) {
            return false;
        }
        
        $notifications[$notificationId]['progress'] = min(100, max(0, $progress));
        Redis::set(self::NOTIFICATION_KEY, json_encode($notifications));
        
        return true;
    }
    
    /**
     * Remove uma notificação
     * 
     * @param string $notificationId ID da notificação
     * @return bool
     */
    public function removeNotification(string $notificationId)
    {
        $notifications = $this->getNotifications();
        
        if (!isset($notifications[$notificationId])) {
            return false;
        }
        
        unset($notifications[$notificationId]);
        Redis::set(self::NOTIFICATION_KEY, json_encode($notifications));
        
        return true;
    }
    
    /**
     * Obtém todas as notificações
     * 
     * @return array
     */
    public function getNotifications()
    {
        $notifications = Redis::get(self::NOTIFICATION_KEY);
        
        if (!$notifications) {
            return [];
        }
        
        return json_decode($notifications, true);
    }
    
    /**
     * Obtém notificações para um usuário específico
     * 
     * @param int $userId ID do usuário
     * @return array
     */
    public function getUserNotifications(int $userId)
    {
        $allNotifications = $this->getNotifications();
        
        return array_filter($allNotifications, function ($notification) use ($userId) {
            return $notification['user_id'] == $userId;
        });
    }
}