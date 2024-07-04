@extends('layouts.main')

@section('content')
    <section class="">
        <x-bread-crumb current="Items" />
        <x-table.datatables :headings="['id', 'name','price',  'action']" title="Items">

            <x-slot name="addons">
                <a href="{{ route('items.create') }}"
                    class="btn btn-primary">
                    Add Item
                </a>
            </x-slot>
            @foreach ($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->name }}
                    </td>
                    <td>
                        ${{ $item->price }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <x-table.action :index="$index" :items="[
                                [
                                    'name' => 'Edit',
                                    'icon' => 'ft-edit-2',
                                    'route' => route('items.edit', [
                                        'item' => $item->id,
                                    ]),
                                ],
                            ]" />
                            <x-delete-confirmation
                                action="{{ route('items.destroy', ['item' => $item->id]) }}"
                                buttonClass="btn danger" :index="$item->id"
                                title="Are you sure you want to delete this Item?"
                                 />
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table.datatables>

    </section>
@endsection
