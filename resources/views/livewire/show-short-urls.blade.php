<div class="container mx-auto p-8">
    <div class="mb-5">
        <form wire:submit="search">
            <input type="search" placeholder="Busque pela URL Original" class="w-full bg-white p-2 rounded-2xl text-black" wire:model="query">
        </form>
    </div>
    <table class="w-full border-collapse rounded bg-zinc-50 dark:bg-zinc-900">
        <thead>
            <tr class="text-left cla">
                <th>URL Original</th>
                <th>URL Encurtada</th>
                <th>Clicks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shortUrls as $shortUrl)
                <tr class="p-8">
                    <td>{{ $shortUrl->original_url }}</td>
                    <td>{{ url($shortUrl->code) }}</td>
                    <td>{{ $shortUrl->clicks }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Nenhum link cadastrado</td>
                </tr>
            @endforelse

        </tbody>
    </table>
    <div class="mt-5">
        {{ $shortUrls->links() }}
    </div>
</div>
