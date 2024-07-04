@extends('layouts.main')

@section('content')
    <section class="">
        {{-- <x-bread-crumb current="Employees" :previous="[
            [
                'name' => 'Companies',
                'route' => route('companies.index'),
            ],
        ]">
            <x-action-button route="{{ route('companies.index') }}" />
        </x-bread-crumb> --}}

        <x-table.datatables :headings="['id', 'title', 'type', 'action']" title="Emails"
            previous_route="{{ isset($email) ? route('email.index') : null }}" subtitle="{{ getTableSubtitle('Emails') }}">
            @if (auth()->user()->hasPermissionTo('add_email') || auth()->user()->hasPermissionTo('all_permissions'))
                <x-slot name="addons">
                    <a href="{{ route('email.create') }}" class="btn btn-success btn-sm">
                        Add Email
                    </a>
                </x-slot>
            @endif
            @foreach ($emails as $index => $email)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        <div>
                            {{ $email->title }}
                        </div>
                    </td>
                    <td>
                        <div class="badge badge-success">
                            {{ $email->type }}
                        </div>
                    </td>

                    <td>
                        <div class="d-flex align-items-center">
                            <x-table.action :index="$index" :items="[
                                [
                                    'name' => 'Edit',
                                    'icon' => 'ft-edit-2',
                                    'route' => route('email.edit', $email->id),
                                    'type' => 'link',
                                ],
                                [
                                    'name' => 'Show',
                                    'icon' => 'ft-eye',
                                    'route' => route('email.show', $email->id),
                                    'type' => 'link',
                                ],
                            ]" />
                            <x-delete-confirmation action="{{ route('email.destroy', $email->id) }}" buttonClass="btn danger"
                                :index="$email->id" title="Are you sure you want to delete this Email?" />
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table.datatables>
    </section>
@endsection
