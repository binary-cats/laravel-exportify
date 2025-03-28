<section {{ $attributes }}>
    @forelse($exportables as $exportable)
        <x-exportify::exportable :$exportable />
    @empty
        {{ $empty ?? 'No exports available.' }}
    @endforelse
</section>
