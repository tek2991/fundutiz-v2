<?php

namespace App\Http\Livewire;

use App\Models\Fund;
use App\Models\User;
use App\Models\Office;
use App\Models\Transaction;
use App\Models\FinancialYear;
use Illuminate\Support\Carbon;
use App\Models\TransactionType;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\{Button, Column, Detail, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class TransactionTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public string $sortField = 'approved_at';
    public string $sortDirection = 'desc';

    public string $debitIcon = "
        <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-6 w-6 ml-1 text-red-500\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\">
            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15 12a3 3 0 11-6 0 3 3 0 016 0z\"/>
            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 19a2 2 0 01-2 2H7a2 2 0 01-2-2V7c0-1.1.9-2 2-2h10a2 2 0 012 2v12z\"/>
        </svg>
    ";

    public string $creditIcon = "
        <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-6 w-6 ml-1 text-green-500\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\">
            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M12 6v6m0 0v6m0-6h6m-6 0H6\"/>
        </svg>
    ";

    public $debitTypeId = TransactionType::DEBIT;
    public $creditTypeId = TransactionType::CREDIT;

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
            Detail::make()
                ->view('components.transaction-detail')
                ->showCollapseIcon(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\Transaction>
     */
    public function datasource(): Builder
    {
        $userIsAdmin = auth()->user()->hasRole('administrator');
        $userIsManager = auth()->user()->hasRole('manager');
        $query =  Transaction::query();

        if (!$userIsAdmin && !$userIsManager) {
            $query->where('office_id', auth()->user()->office_id);
        }

        if($userIsManager && !$userIsAdmin) {
            $offices = auth()->user()->managerOfOffices;
            $query->whereIn('office_id', $offices->pluck('id'));
        }

        if($userIsAdmin) {
            // Nothing to do
        }

        return     $query->with(['transactionType', 'financialYear', 'office', 'fund', 'approver', 'createdBy']);
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('transaction_type_id')
            ->addColumn('transaction_type_name', fn (Transaction $model) => $model->transactionType->name)
            ->addColumn('transaction_type_formatted', fn (Transaction $model) => "<span class='inline-flex justify-between'>" . $model->transactionType->name . ($model->transaction_type_id == $this->debitTypeId ? $this->debitIcon : $this->creditIcon) . "</span>")
            ->addColumn('financial_year_id')
            ->addColumn('financial_year_name', fn (Transaction $model) => $model->financialYear->name)
            ->addColumn('office_id')
            ->addColumn('office_name', fn (Transaction $model) => $model->office->name)
            ->addColumn('fund_id')
            ->addColumn('fund_name', fn (Transaction $model) => $model->fund->name)
            ->addColumn('hoa', fn (Transaction $model) => $model->fund->head_of_account)
            ->addColumn('file_number')
            ->addColumn('amount_in_cents')
            ->addColumn('amount_formatted', fn (Transaction $model) => "₹" . number_format($model->amount_in_cents / 100, 2, '.', ','))
            ->addColumn('approver_id')
            ->addColumn('approver_name', fn (Transaction $model) => $model->approver->name)
            ->addColumn('approved_at_formatted', fn (Transaction $model) => Carbon::parse($model->approved_at)->format('d/m/Y'))
            ->addColumn('incurred')
            ->addColumn('incurred_formatted', fn (Transaction $model) => $model->incurred ? 'Yes' : 'No')
            ->addColumn('item')
            ->addColumn('vendor_name')
            ->addColumn('gem_contract_number')
            ->addColumn('gem_non_availability_certificate_number')
            ->addColumn('not_gem_remarks')
            ->addColumn('created_by')
            ->addColumn('created_by_name', fn (Transaction $model) => $model->createdBy->name)
            ->addColumn('created_at_formatted', fn (Transaction $model) => Carbon::parse($model->created_at)->format('d/m/Y'))
            ->addColumn('is_deficit')
            ->addColumn('is_deficit_formatted', fn (Transaction $model) => $model->is_deficit ? 'Yes' : 'No');
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('Type', 'transaction_type_formatted', 'transaction_type_id')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),
            // Excel alternative
            Column::make('Type', 'transaction_type_name', 'transaction_type_id')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Financial year', 'financial_year_name', 'financial_year_id')
                ->sortable()
                ->searchable(),

            Column::make('Office', 'office_name', 'office_id')
                ->sortable()
                ->searchable()
                ->hidden($isHidden = auth()->user()->hasAnyRole(['administrator', 'manager'])!= True),

            Column::make('Fund', 'fund_name', 'fund_id')
                ->sortable()
                ->searchable(),
            // Only for excel
            Column::make('H.O.A', 'hoa')
                ->hidden()
                ->visibleInExport(true),

            Column::make('File number', 'file_number')
                ->sortable()
                ->searchable()
                ->bodyAttribute('text-justify', 'white-space: normal !important;'),

            Column::make('Amount', 'amount_formatted', 'amount_in_cents')
                ->sortable()
                ->searchable(),

            Column::make('Created by', 'created_by_name', 'created_by')
                ->sortable()
                ->searchable(),


            // Columns only for excel
            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Approver', 'approver_name', 'approver_id')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Approved at', 'approved_at_formatted', 'approved_at')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Incurred', 'incurred_formatted', 'incurred')
                ->hidden()
                ->visibleInExport(true),

            Column::make('Item', 'item')
                ->hidden()
                ->searchable()
                ->visibleInExport(true),

            Column::make('Vendor name', 'vendor_name')
                ->hidden()
                ->searchable()
                ->visibleInExport(true),

            Column::make('GEM contract number', 'gem_contract_number')
                ->hidden()
                ->searchable()
                ->visibleInExport(true),

            Column::make('GEM non-availability certificate number', 'gem_non_availability_certificate_number')
                ->hidden()
                ->searchable()
                ->visibleInExport(true),

            Column::make('Not GEM remarks', 'not_gem_remarks')
                ->hidden()
                ->searchable()
                ->visibleInExport(true),

            Column::make('Is deficit', 'is_deficit_formatted', 'is_deficit')
                ->hidden()
                ->visibleInExport(true),
        ];
    }

    /**
     * PowerGrid Filters.
     *
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        return [
            Filter::datetimepicker('created_at'),
            Filter::select('office_id', 'office_id')
                ->dataSource(Office::all())
                ->optionValue('id')
                ->optionLabel('name'),
            Filter::select('fund_id', 'fund_id')
                ->dataSource(Fund::all())
                ->optionValue('id')
                ->optionLabel('name'),
            Filter::select('financial_year_id', 'financial_year_id')
                ->dataSource(FinancialYear::all())
                ->optionValue('id')
                ->optionLabel('name'),
            Filter::select('transaction_type_id', 'transaction_type_id')
                ->dataSource(TransactionType::all())
                ->optionValue('id')
                ->optionLabel('name'),
            Filter::select('created_by', 'created_by')
                ->dataSource(User::all())
                ->optionValue('id')
                ->optionLabel('name'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid Transaction Action Buttons.
     *
     * @return array<int, Button>
     */


    public function actions(): array
    {
        return [
            Button::make('edit', 'Edit')
                ->class('bg-indigo-500 cursor-pointer text-white px-2.5 py-1 m-1 rounded text-sm')
                ->route('transaction.edit', ['transaction' => 'id'])
                ->target(''),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid Transaction Action Rules.
     *
     * @return array<int, RuleActions>
     */


    public function actionRules(): array
    {
        return [

            //Hide button edit for ID 1
            Rule::button('edit')
                ->when(
                    fn ($transaction) =>
                    auth()->user()->cannot('update', $transaction)
                )
                ->hide(),
        ];
    }
}
