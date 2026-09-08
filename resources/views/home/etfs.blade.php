@extends('layouts.base')

@section('title', 'Generative Art')

@section('content')
@include('home.partials.nft-topic', [
    'topic' => 'Generative Art',
    'headline' => 'Where Code Becomes Art',
    'intro' => 'Discover digital works created through an artist\'s rules, systems and creative choices.',
    'cards' => [
        ['The Creative System', 'Generative art uses a defined process to produce an artwork. The artist shapes the rules, inputs and aesthetic direction.'],
        ['Variation Within a Collection', 'A shared system can produce many distinct outputs. Explore how colour, composition and other properties vary across the series.'],
        ['Understanding the Work', 'Look at the system behind the image as well as the image itself. Review how the artwork is generated, stored and connected to its token.'],
    ],
    'closingTitle' => 'Explore the Artist\'s Process',
    'closing' => 'Generative collections offer a meeting point between creative expression and computation. Understanding the process adds depth to the experience of the work.',
])
@endsection
