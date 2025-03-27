<section {{ $attributes }}>
    @forelse($exports as $exportFactory)
        <x-exportify::exportable :$exportFactory />
    @empty
        {{ $empty ?? 'No exports available.' }}
    @endforelse
</section>
