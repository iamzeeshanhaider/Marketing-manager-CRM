@extends('layouts.main')

@section('content')
    <x-bread-crumb :previous="[
        [
            'name' => 'Companies',
            'route' => route('companies.index'),
        ],
    ]" current="here" />
   {{-- content --}}
@endsection
