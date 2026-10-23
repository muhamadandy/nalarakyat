<a href="{{ route('front.details', $article->slug) }}" class="card">
    <div class="flex flex-col gap-4 p-[26px_20px] transition-all duration-300 ring-1 ring-[#EEF0F7] hover:ring-2 hover:ring-[#FF6B18] rounded-[20px] overflow-hidden bg-white">
        <div class="thumbnail-container h-[200px] relative rounded-[20px] overflow-hidden">
            <div class="badge absolute left-5 top-5 bottom-auto right-auto flex p-[8px_18px] bg-white rounded-[50px]">
                <p class="text-xs leading-[18px] font-bold">{{ strtoupper($article->category?->name ?? '') }}</p>
            </div>
            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->name }}" class="w-full h-full object-cover" />
        </div>
        <div class="flex flex-col gap-[6px]">
            <h3 class="text-lg leading-[27px] font-bold">{{ $article->name }}</h3>
            <p class="text-sm leading-[21px] text-[#A3A6AE]">{{ $article->created_at->translatedFormat('d M Y') }}</p>
        </div>
    </div>
</a>
