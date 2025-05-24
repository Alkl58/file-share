<!-- Progress -->
<div class="flex items-center gap-x-3 whitespace-nowrap">
    <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700" role="progressbar" aria-valuenow="{{auth()->user()->getUserUsedSpacePercentage()}}" aria-valuemin="0" aria-valuemax="{{auth()->user()->space_limit}}">
        <div class="flex flex-col justify-center rounded-full overflow-hidden bg-zinc-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-zinc-500" style="width: {{auth()->user()->getUserUsedSpacePercentage()}}%"></div>
    </div>
    <div class="w-10 text-end">
        <span class="text-sm text-gray-800 dark:text-white">{{auth()->user()->getUserUsedSpacePercentage()}}%</span>
    </div>
</div>