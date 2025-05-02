{{-- Adiciona wire:poll.1s.visible ao elemento principal do componente --}}
{{-- Isso chamará o método 'getNotifications' a cada 1 segundo, mas SOMENTE quando este div estiver visível na tela --}}
<div wire:poll.1s.visible="getNotifications">
    {{-- Verifica se o status é verdadeiro (se há notificações a serem mostradas) --}}
    @if($this->status && !empty($notifications))
        {{-- Container principal com estilos Tailwind --}}
        <div class="relative rounded-md p-4 mt-1 w-full bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            {{-- Itera sobre cada notificação --}}
            @foreach($notifications as $id => $notification)
                <div class="">
                    {{-- Cabeçalho da notificação (título, progresso, botão de excluir) --}}
                    <div class="mb-2 flex justify-between items-center">
                        <div class="flex items-center">
                            {{-- Título da notificação (usando a chave correta 'title') --}}
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">{{ $notification['title'] }}</h3>
                            {{-- Porcentagem do progresso --}}
                            <span class="text-xs text-green-500 dark:text-green-500 px-2 mx-2">{{ number_format($notification['progress'], 0) }}%</span>
                            
                        </div>
                        {{-- Botão para remover/excluir a notificação --}}
                        <button
                            wire:click="deleteNotification('{{ $id }}')" {{-- Chama o método removeNotification no componente Livewire --}}
                            title="Excluir notificação"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                            {{-- Ícone de 'X' (fechar) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <mcreference link="http://www.w3.org/2000/svg" index="0">0</mcreference>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Barra de progresso --}}
                    <div class="flex w-full h-5 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700"
                        role="progressbar"
                        aria-valuenow="{{ $notification['progress'] }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                        {{-- Parte preenchida da barra de progresso --}}
                        <div class="flex flex-col justify-center rounded-full overflow-hidden bg-primary-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-primary-500"
                            style="width: {{ $notification['progress'] }}%">
                            {{-- Opcional: pode colocar o texto da porcentagem aqui dentro também, se desejar --}}
                        </div>
                    </div>
                    {{-- Nome do usuário (será ajustado no próximo passo) --}}
                    <span class="text-xs text-gray-500 dark:text-gray-400">Iniciado por: {{ $notification['userName'] ?? 'Desconhecido' }}</span>
                </div>
            @endforeach
        </div>
    {{-- Se não houver status ou notificações, não mostra nada (ou pode adicionar uma mensagem aqui se preferir) --}}
    @endif
</div>