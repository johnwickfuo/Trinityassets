@extends('layouts.base')

@section('title', 'NFT Research')

@section('content')
@include('home.partials.nft-topic', [
    'topic' => 'NFT Research',
    'headline' => 'A Clear Research Process',
    'intro' => 'Build a consistent way to explore digital art, collection details and investment terms.',
    'cards' => [
        ['Begin with the Creator', 'Find the artist\'s official sources and learn about their previous work. Check that collection information points back to those sources.'],
        ['Read the Token Details', 'Review the token identifier, network, edition size and associated artwork information. Look for clarity about where the media is stored.'],
        ['Bring the Details Together', 'Compare your understanding of the artwork with the participation terms, costs and conditions. Ask the team about any platform details you need explained.'],
    ],
    'closingTitle' => 'Turn Information into Understanding',
    'closing' => 'A repeatable research process helps you see beyond headlines. Give equal attention to the creative work and the terms attached to an opportunity.',
])
@endsection
