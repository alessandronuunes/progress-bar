<div >
    @if($this->status)
    <div x-data="{ currentVal: 20 ,minVal: 0 ,maxVal: 100, calcPercentage(min, max, val){return (((val-min)/(max-min))*100).toFixed(0)} }" 
        class="w-full mt-2 p-2 dark:bg-gray-900">
        <div class="mb-1 flex items-end justify-between gap-2 text-on-surface dark:text-on-surface-dark">   
            <span>Progress</span>
            <span x-text="`${calcPercentage(minVal, maxVal, currentVal)}%`"></span>
        </div> 
        <div class="flex h-3 w-full overflow-hidden rounded-md bg-white dark:bg-gray-800" role="progressbar" aria-label="default progress bar" x-bind:aria-valuenow="currentVal" x-bind:aria-valuemin="minVal" x-bind:aria-valuemax="maxVal">
            <div class="h-full rounded-md bg-primary dark:bg-primary-500" x-bind:style="`width: ${calcPercentage(minVal, maxVal, currentVal)}%`">
            </div>
        </div>
    </div>
    @endif
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
</div>
