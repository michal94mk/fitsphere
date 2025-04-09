<div>   
    @if($selectedPostId)
        <button wire:click="resetSelectedPost" class="m-4 px-4 py-2 bg-gray-300 rounded">⮜ Powrót do wyników</button>
        <livewire:post-details :post-id="$selectedPostId" />
    @else
        <livewire:search-results :search-query="$searchQuery" />
    @endif
</div>