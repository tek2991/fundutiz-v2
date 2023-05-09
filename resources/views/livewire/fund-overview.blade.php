<div>
    <h1 class="text-xl font-semibold text-gray-700 pb-6">
        Fund Overview
    </h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @role('administrator')
            {{-- Office id --}}
            <div class="">
                <label class="block font-medium text-sm text-gray-700">
                    Select Office
                </label>
                <select name="office_id" id="office_id" wire:model="office_id"
                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    required>
                    <option value="">All office</option>
                    @foreach ($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </select>
            </div>
        @endrole
        {{-- Financial Year Id --}}
        <div class="">
            <label class="block font-medium text-sm text-gray-700">
                Select Financial Year
            </label>
            <select name="financial_year_id" id="financial_year_id" wire:model="fy_id"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                required>
                <option value="">Current financial year</option>
                @foreach ($financialYears as $financialYear)
                    <option value="{{ $financialYear->id }}">{{ $financialYear->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($fundArray as $fund)
            <div class="bg-white rounded-b-lg shadow-xs dark:bg-gray-800">
                <div class="bg-{{ $fund['color'] }} h-2.5 rounded-r-full" style="width: {{ $fund['percentage'] }}%">
                </div>
                <div class="p-4">
                    <div>
                        <p class="font-medium text-gray-700">
                            {{ $fund['fund_name'] }}
                        </p>
                        <p class="mb-2 text-sm font-medium text-gray-600">
                            H.O.A - {{ $fund['fund_hoa'] }}
                        </p>
                        <p class="font-semibold text-gray-700">
                            Allocation: {{ $fund['allocation'] }}
                        </p>

                        <p class="font-semibold text-gray-700">
                            Expenditure: {{ $fund['expenditure'] }}
                            @if ($fund['is_deficit'])
                                <span class="text-{{ $fund['color'] }}">[Deficit]</span>
                            @else
                                <span class="text-{{ $fund['color'] }}">[{{ $fund['percentage'] }}%]</span>
                            @endif
                        </p>

                        <p class="font-semibold text-gray-700">
                            Balance:
                            <span class="text-{{ $fund['color'] }}">
                                {{ $fund['balance'] }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
