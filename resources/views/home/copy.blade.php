@extends('layouts.base')

@section('title', 'Collection Strategy')

@section('content')
@include('home.partials.nft-topic', [
    'topic' => 'Collection Strategy',
    'headline' => 'Develop Your Own Art Perspective',
    'intro' => 'Explore digital art collections with a clear sense of your interests and investment goals.',
    'cards' => [
        ['Define Your Interests', 'Consider whether you are drawn to individual artists, a visual style, a creative movement or a particular form of digital work.'],
        ['Compare Thoughtfully', 'Compare the creator, artwork, edition structure and terms across collections. Popularity alone is not a substitute for understanding.'],
        ['Review Your Perspective', 'Keep a record of the information that shaped your decision. Revisit it as you learn more about the work and its context.'],
    ],
    'closingTitle' => 'A Collection with a Clear Rationale',
    'closing' => 'A thoughtful approach begins with your own understanding of the art. Other collectors\' choices can provide context, but they do not determine what is suitable for you.',
])
@endsection
