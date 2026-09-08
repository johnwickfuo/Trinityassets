@extends('layouts.base')

@section('title', 'Crypto & NFTs')

@section('content')
@include('home.partials.nft-topic', [
    'topic' => 'Crypto & NFTs',
    'headline' => 'Understand the Digital Asset Context',
    'intro' => 'Explore the relationship between blockchain networks, digital currencies and art NFTs.',
    'cards' => [
        ['Tokens and Currencies', 'Cryptocurrency units of the same type are generally interchangeable. NFTs have distinct identifiers, allowing individual tokens to be associated with particular works or editions.'],
        ['Blockchain Records', 'A blockchain can record token transfers and ownership information. The artwork itself may be stored separately, so read the details of how a collection is maintained.'],
        ['Prices and Costs', 'NFT prices may be expressed in a cryptocurrency. Its exchange rate, network costs and any other applicable fees can affect the total cost of participating.'],
    ],
    'closingTitle' => 'Keep the Distinctions Clear',
    'closing' => 'A cryptocurrency price chart gives context about that currency. It does not show the value or performance of a particular artwork or NFT collection.',
])
@endsection
