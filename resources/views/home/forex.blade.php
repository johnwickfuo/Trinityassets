@extends('layouts.base')

@section('title', 'Digital Originals')

@section('content')
@include('home.partials.nft-topic', [
    'topic' => 'Digital Originals',
    'headline' => 'Original Ideas. Digital Expression.',
    'intro' => 'Discover the individual works, creative stories and artistic perspectives at the heart of digital art NFTs.',
    'cards' => [
        ['A Distinct Creative Work', 'Digital originals can take the form of illustration, painting, photography or mixed media created for a digital setting.'],
        ['The Artist Behind It', 'Explore the creator\'s body of work, creative process and the ideas that connect a piece to their wider practice.'],
        ['Artwork and Token', 'Read how the token relates to the artwork. A unique token does not by itself establish the authenticity of the underlying work or grant copyright.'],
    ],
    'closingTitle' => 'Look Beyond the First Impression',
    'closing' => 'Consider the artwork, its context and the information available about the creator. An investment decision deserves the same attention as the art itself.',
])
@endsection
