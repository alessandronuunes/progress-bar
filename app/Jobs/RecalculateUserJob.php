<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\ProgressNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $dateStart;
    protected $dateEnd;
    protected $userId;
    protected $notificationId;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $dateStart, $dateEnd, $userId, $notificationId)
    {
        $this->user = $user;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->userId = $userId;
        $this->notificationId = $notificationId;
    }

    /**
     * Execute the job.
     */
    public function handle(ProgressNotificationService $progressService): void
    {
        // Simulação do processo de recálculo com atualizações de progresso
        $totalSteps = 10;
        
        for ($step = 1; $step <= $totalSteps; $step++) {
            // Calcula a porcentagem de progresso
            $progress = ($step / $totalSteps) * 100;
            
            // Atualiza o progresso no Redis
            $progressService->updateProgress($this->notificationId, $progress);
            
            // Simula um processamento que leva tempo
            sleep(2);
        }
    }
}