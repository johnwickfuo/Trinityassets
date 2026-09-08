@extends('layouts.base')

@section('title', 'Digital Art NFTs')

@section('content')
@include('home.partials.nft-topic', [
    'topic' => 'Digital Art NFTs',
    'headline' => 'Creativity Meets Digital Ownership',
    'intro' => 'Explore the world of digital art NFT investment: the artwork, the people who create it and the collections that give it context.',
    'cards' => [
        ['What Is an Art NFT?', 'An NFT is a distinct token recorded on a blockchain. In digital art, it can be associated with an individual artwork or an edition within a collection.'],
        ['The Artwork Comes First', 'Digital originals, illustrations, animation and generative pieces offer different forms of creative expression. Understanding the work is the starting point for understanding the collection.'],
        ['An Investment Perspective', 'Interest in art NFTs can combine an appreciation for the work with an investment objective. Review the information and terms of an opportunity, including what your participation represents.'],
    ],
    'closingTitle' => 'Explore the Art Behind the Token',
    'closing' => 'Look at the creator, edition size and ownership history. Read the associated rights and terms before making a decision.',
])
@endsection
