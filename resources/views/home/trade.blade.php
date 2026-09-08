@extends('layouts.base')

@section('title', 'NFT Investment')

@section('content')
@include('home.partials.nft-topic', [
    'topic' => 'NFT Investment',
    'headline' => 'A Considered Approach to Digital Art',
    'intro' => 'Explore the artwork and understand the investment context before choosing your next step.',
    'cards' => [
        ['Discover the Work', 'Start with the style, story and creator. Learn what gives the artwork its character and how it fits into a collection.'],
        ['Understand Participation', 'Read the specific terms to establish what an investment represents. Participation in a plan does not by itself confirm ownership of an individual NFT.'],
        ['Review the Details', 'Consider the amount involved, applicable costs, duration and any conditions for withdrawal or transfer. Ask questions where the information is unclear.'],
    ],
    'closingTitle' => 'Connect Your Interests with Your Goals',
    'closing' => 'An interest in digital art can be a starting point for research. Let the details of the opportunity inform your decision.',
])
@endsection
