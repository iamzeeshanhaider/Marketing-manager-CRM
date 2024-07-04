@extends('layouts.main')

@section('content')
    <section class="">
        <x-bread-crumb current="Permissions" />
        <x-table.datatables :headings="['id', 'name',  'action']" title="Permisions">

            <x-slot name="addons">
                <a href="{{ route('permissions.create') }}"
                    class="btn btn-primary">
                    Add Permission
                </a>
            </x-slot>
            @foreach ($permissions as $index => $permission)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $permission->name }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <x-table.action :index="$index" :items="[
                                [
                                    'name' => 'Edit',
                                    'icon' => 'ft-edit-2',
                                    'route' => route('permissions.edit', [
                                        'permission' => $permission->id,
                                    ]),
                                ],
                            ]" />
                            <x-delete-confirmation
                                action="{{ route('permission.destroy', ['permission' => $permission->id]) }}"
                                buttonClass="btn danger" :index="$permission->id"
                                title="Are you sure you want to delete this Item?"
                                 />
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table.datatables>

    </section>
@endsection
