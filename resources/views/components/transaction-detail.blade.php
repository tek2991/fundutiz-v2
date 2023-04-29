<div class="px-6 py-4 bg-white border border-slate-200">
    <h2 class="pb-4">
        <span class="font-medium text-slate-600 mr-2">Transaction Details: <span
                class="text-justify text-sm text-blue-950">{{ $row->file_number }} <span class="pl-4">dated:</span>
                {{ $row->approved_at_formatted }}</span></span>
    </h2>
    <div class="grid grid-cols-4 gap-4">
        {{-- Amount --}}
        <div class="flex">
            <div class="text-sm font-medium text-slate-600 mr-2">Amount:</div>
            <div class="text-sm text-slate-900">
                {{ $row->amount_formatted }}
                @if ($row->is_deficit)
                    <span class="text-red-600">[Deficit]</span>
                @endif
            </div>
        </div>
        {{-- Fund --}}
        <div class="flex">
            <div class="text-sm font-medium text-slate-600 mr-2">Fund:</div>
            <div class="text-sm text-slate-900">{{ $row->fund->name . ' - ' . $row->fund->head_of_account }}</div>
        </div>
        {{-- Type --}}
        <div class="flex">
            <div class="text-sm font-medium text-slate-600 mr-2">Type:</div>
            <div class="text-sm text-slate-900">{{ $row->transactionType->name }}</div>
        </div>
        {{-- Approved by --}}
        <div class="flex">
            <div class="text-sm font-medium text-slate-600 mr-2">Approved by:</div>
            <div class="text-sm text-slate-900">{{ $row->approver_name }}</div>
        </div>
        {{-- Office --}}
        <div class="flex">
            <div class="text-sm font-medium text-slate-600 mr-2">Office:</div>
            <div class="text-sm text-slate-900">{{ $row->office->name }}</div>
        </div>
        {{-- Financial Year --}}
        <div class="flex">
            <div class="text-sm font-medium text-slate-600 mr-2">Financial Year:</div>
            <div class="text-sm text-slate-900">{{ $row->financialYear->name }}</div>
        </div>
        {{-- Created by --}}
        <div class="flex">
            <div class="text-sm font-medium text-slate-600 mr-2">Created by:</div>
            <div class="text-sm text-slate-900">{{ $row->createdBy->name }}</div>
        </div>
        {{-- Created at --}}
        <div class="flex">
            <div class="text-sm font-medium text-slate-600 mr-2">Created at:</div>
            <div class="text-sm text-slate-900">{{ $row->created_at_formatted }}</div>
        </div>
    </div>
    @if ($row->transactionType->name == 'Debit')
        <h2 class="pt-8 pb-4">
            <span class="font-medium text-slate-600 mr-2">Item Details</span>
        </h2>
        <div class="grid grid-cols-4 gap-4">
            {{-- Vendor --}}
            <div class="flex">
                <div class="text-sm font-medium text-slate-600 mr-2">Vendor:</div>
                <div class="text-sm text-slate-900">{{ $row->vendor_name }}</div>
            </div>
            {{-- Gem contract Number --}}
            <div class="flex col-start-1 col-span-2">
                <div class="text-sm font-medium text-slate-600 mr-2">Gem Contract No:</div>
                <div class="text-sm text-slate-900">
                    {{ $row->gem_contract_number != null ? $row->gem_contract_number : '--' }}</div>
            </div>
            {{-- Gem non availability certificate number --}}
            <div class="flex col-span-2">
                <div class="text-sm font-medium text-slate-600 mr-2">Gem Non Availability Certificate No:</div>
                <div class="text-sm text-slate-900">
                    {{ $row->gem_non_availability_certificate_number != null ? $row->gem_non_availability_certificate_number : '--' }}
                </div>
            </div>
            {{-- Not Gem remarks --}}
            <div class="flex col-span-2 col-start-1">
                <div class="text-sm font-medium text-slate-600 mr-2">Not Gem Remarks:</div>
                <div class="text-sm text-slate-900 text-justify pr-8">
                    {{ $row->not_gem_remarks != null ? $row->not_gem_remarks : '--' }}</div>
            </div>
            {{-- Item --}}
            <div class="flex col-span-2">
                <div class="text-sm font-medium text-slate-600 mr-2">Item:</div>
                <div class="text-sm text-slate-900 text-justify pr-8">{{ $row->item }}</div>
            </div>
    @endif
</div>
