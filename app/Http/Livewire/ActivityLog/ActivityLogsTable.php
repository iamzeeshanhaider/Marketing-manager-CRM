<?php

namespace App\Http\Livewire\ActivityLog;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\GeneralStatus;

class ActivityLogsTable extends DataTableComponent
{
    public $userID = 'all';
    public string $tableName = 'activity_logs';
    public array $logs = [];

    public $columnSearch = [
        'action' => null,
        'old_values' => null,
        'new_values' => null,
    ];

    public function mount($userID): void {}

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setPerPageAccepted([10, 25, 50, 100])
            ->setFiltersStatus(false)
            ->setFiltersVisibilityStatus(true)
            ->setFilterLayout('slide-down')
            ->setColumnSelectDisabled()
            ->setEmptyMessage('No results found')
            ->setHideBulkActionsWhenEmptyEnabled();
    }


    public function columns(): array
    {
        return [
            Column::make(trans('ID'), 'id')->hideIf(true),
            Column::make(trans('Model'), 'module_name')->hideIf(true),
            Column::make(trans('User'), 'user.name'),
            Column::make(trans('IP'), 'ip_address'),
            Column::make(trans('Action'), 'action')
            ->format(
                fn($value, $row, Column $column) => '<span" >'.$row->action. ' ' .$row->module_name.'</span>'
            )
            ->html(),

            Column::make(trans('Date'), 'created_at'),
            Column::make(trans(''), 'id')->view('activity_logs.partials.action'),
        ];
    }

    public function filters(): array
    {
        return [
            //
        ];
    }

    public function builder(): Builder
    {
        return ActivityLog::query()
            ->when($this->userID !== 'all', fn ($query) => $query->where('user_id', $this->userID))
            ->latest('activity_logs.created_at');
    }




}
