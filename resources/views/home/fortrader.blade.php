@extends('layouts.base')

@section('title', 'NFT Education')

@section('content')
@include('home.partials.nft-topic', [
    'topic' => 'NFT Education',
    'headline' => 'Understand Art, Tokens and Ownership',
    'intro' => 'Start with the essential ideas behind digital art NFT investment and build your understanding at your own pace.',
    'cards' => [
        ['NFT Basics', 'A non-fungible token is a distinct blockchain token. Art NFTs connect this token concept with individual digital works or editions.'],
        ['Digital Art Forms', 'Digital painting, illustration, photography, animation and generative art offer different ways for artists to create and express ideas.'],
        ['Provenance', 'Provenance concerns the origin and history of a work. Blockchain records can help trace a token, but they do not automatically verify the creator or the artwork.'],
        ['Artwork Rights', 'Owning a token does not automatically transfer copyright. Read the licence or associated terms to understand any rights to display, reproduce or use the work.'],
        ['Collection Structure', 'Review whether a work is a single piece, an edition or part of a larger series. Understand how supply and related works are described.'],
        ['Investment Context', 'An artwork\'s asking price and its eventual resale price can differ. Understand the terms and the nature of your participation before committing funds.'],
    ],
    'closingTitle' => 'Keep Learning',
    'closing' => 'Use the NFT FAQs to revisit the basics, or contact our team with questions about the platform.',
])
@endsection
