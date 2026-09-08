@extends('layouts.base')

@section('title', 'Collection Research')

@section('content')
@include('home.partials.nft-topic', [
    'topic' => 'Collection Research',
    'headline' => 'Look at the Whole Collection',
    'intro' => 'Bring together the artistic vision, ownership history and market context behind a digital art NFT collection.',
    'cards' => [
        ['Creative Consistency', 'Explore how individual works connect to the collection\'s theme, visual style and artistic purpose.'],
        ['Provenance and Details', 'Review the creator\'s official sources, token identifiers and available ownership records. Distinguish documented information from promotional claims.'],
        ['Market Context', 'Consider the difference between asking prices and completed sales. A listed price does not establish what another buyer will pay.'],
    ],
    'closingTitle' => 'Build a Rounded View',
    'closing' => 'Collection research connects the artwork with its wider context. Take time to understand what is known and what remains uncertain.',
])
@endsection
