@php
    use App\Services\ProgressNotificationService;
    
    // Obtém as notificações do usuário atual
    $progressService = app(ProgressNotificationService::class);
    $notifications = $progressService->getUserNotifications(auth()->id() ?? 0);
@endphp

@if(count($notifications) > 0)
    @foreach($notifications as $notification)
        <div class="relative rounded-md p-4 mt-1 w-full bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <button
                style="
                    position: absolute;
                    top: 0.75rem;
                    right: 0.75rem;" 
                title="fechar" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
            <div class="mb-2 flex justify-between items-center ">
                <div class="flex items-center">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">{{ $notification['title'] }}</h3> 
                    <span class="text-xs text-green-500 dark:text-green-500 px-2 mx-2">{{ $notification['progress'] }}%</span>
                </div>
            </div>
            
            <div class="flex w-full h-5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700" role="progressbar" aria-valuenow="{{ $notification['progress'] }}" aria-valuemin="0" aria-valuemax="100">
              <div class="flex flex-col justify-center rounded-full overflow-hidden bg-primary-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-primary-500" style="width: {{ $notification['progress'] }}%"></div>
            </div>
        </div>
    @endforeach

@endif