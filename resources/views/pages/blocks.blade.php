@extends('layouts.app')

@section('content')
    @foreach ($page->blocks ?? [] as $block)
        @includeIf('blocks.'.str_replace('_', '-', $block['type']), [
            'data' => $block['data'] ?? [],
            'isHome' => $isHome ?? false,
            'first' => $loop->first,
        ])
    @endforeach
@endsection
