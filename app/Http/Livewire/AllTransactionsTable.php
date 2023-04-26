<?php

namespace App\Http\Livewire;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class AllTransactionsTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

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
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
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
        return Transaction::query();
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
            ->addColumn('financial_year_id')
            ->addColumn('office_id')
            ->addColumn('fund_id')
            ->addColumn('file_number')

           /** Example of custom column using a closure **/
            ->addColumn('file_number_lower', fn (Transaction $model) => strtolower(e($model->file_number)))

            ->addColumn('amount_in_cents')
            ->addColumn('approver_id')
            ->addColumn('approved_at_formatted', fn (Transaction $model) => Carbon::parse($model->approved_at)->format('d/m/Y H:i:s'))
            ->addColumn('incurred')
            ->addColumn('item')
            ->addColumn('vendor_name')
            ->addColumn('gem_contract_number')
            ->addColumn('gem_non_availability_certificate_number')
            ->addColumn('not_gem_remarks')
            ->addColumn('created_by')
            ->addColumn('created_at_formatted', fn (Transaction $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
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
            Column::make('Id', 'id'),
            Column::make('Transaction type id', 'transaction_type_id'),
            Column::make('Financial year id', 'financial_year_id'),
            Column::make('Office id', 'office_id'),
            Column::make('Fund id', 'fund_id'),
            Column::make('File number', 'file_number')
                ->sortable()
                ->searchable(),

            Column::make('Amount in cents', 'amount_in_cents'),
            Column::make('Approver id', 'approver_id'),
            Column::make('Approved at', 'approved_at_formatted', 'approved_at')
                ->sortable(),

            Column::make('Incurred', 'incurred')
                ->toggleable(),

            Column::make('Item', 'item')
                ->sortable()
                ->searchable(),

            Column::make('Vendor name', 'vendor_name')
                ->sortable()
                ->searchable(),

            Column::make('Gem contract number', 'gem_contract_number')
                ->sortable()
                ->searchable(),

            Column::make('Gem non availability certificate number', 'gem_non_availability_certificate_number')
                ->sortable()
                ->searchable(),

            Column::make('Not gem remarks', 'not_gem_remarks')
                ->sortable()
                ->searchable(),

            Column::make('Created by', 'created_by'),
            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

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
            Filter::inputText('file_number')->operators(['contains']),
            Filter::datetimepicker('approved_at'),
            Filter::boolean('incurred'),
            Filter::inputText('item')->operators(['contains']),
            Filter::inputText('vendor_name')->operators(['contains']),
            Filter::inputText('gem_contract_number')->operators(['contains']),
            Filter::inputText('gem_non_availability_certificate_number')->operators(['contains']),
            Filter::inputText('not_gem_remarks')->operators(['contains']),
            Filter::datetimepicker('created_at'),
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

    /*
    public function actions(): array
    {
       return [
           Button::make('edit', 'Edit')
               ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
               ->route('transaction.edit', function(\App\Models\Transaction $model) {
                    return $model->id;
               }),

           Button::make('destroy', 'Delete')
               ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
               ->route('transaction.destroy', function(\App\Models\Transaction $model) {
                    return $model->id;
               })
               ->method('delete')
        ];
    }
    */

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

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($transaction) => $transaction->id === 1)
                ->hide(),
        ];
    }
    */
}
