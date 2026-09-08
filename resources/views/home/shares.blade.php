@extends('layouts.base')

@section('title', 'Art Editions')

@section('content')
@include('home.partials.nft-topic', [
    'topic' => 'Art Editions',
    'headline' => 'One Vision. Multiple Editions.',
    'intro' => 'Explore how editions give digital art collections structure and offer a different perspective on ownership.',
    'cards' => [
        ['Edition Size', 'An edition is a defined set of instances associated with a work. Check the stated supply and whether additional editions or related works may be created.'],
        ['Collection Identity', 'Consider how each piece fits into a series. A consistent visual language, theme or story can help explain the artist\'s intention.'],
        ['Rights and Terms', 'An art NFT is not automatically a share in a company or a claim on its profits. Read the terms to understand the ownership interest and rights being described.'],
    ],
    'closingTitle' => 'Understand the Edition',
    'closing' => 'A small supply alone does not establish value. Look at the artwork, creator, collection context and the actual terms together.',
])
@endsection
