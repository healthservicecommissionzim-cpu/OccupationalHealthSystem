@extends('layouts.cos-dashboard')

@section('cos-content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Compliance Checklist</h2>
    <p class="text-gray-600 mb-6">Case: {{ $case->reference_number }} - {{ $case->first_name }} {{ $case->surname }}</p>

    <form method="POST" action="{{ route('cos.compliance.save', $case->id) }}">
        @csrf
        <div class="space-y-4">
            @foreach($evaluatedItems as $item)
                <div class="compliance-item {{ $item['compliant'] ? 'compliant' : 'non-compliant' }}">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">{{ $item['label'] }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $item['evidence'] }}</p>
                    </div>
                    <div class="ml-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="checklist_items[]" value='{"item":"{{ $item['item'] }}","status":"NON_COMPLIANT"}'>
                            <input type="checkbox" name="checklist_items[]" 
                                   value='{"item":"{{ $item['item'] }}","status":"COMPLIANT"}'
                                   {{ $item['compliant'] ? 'checked' : '' }} class="rounded">
                            <span class="text-sm">{{ $item['compliant'] ? 'Compliant' : 'Non-Compliant' }}</span>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                Save Checklist
            </button>
            <a href="{{ route('cos.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                Cancel
            </a>
        </div>
    </form>

    @if($latestCheck)
        <div class="mt-8 pt-6 border-t">
            <h3 class="font-semibold text-gray-900 mb-3">Latest Result</h3>
            <div class="p-4 bg-blue-50 rounded">
                <p class="text-sm">
                    <strong>Status:</strong> 
                    <span class="px-2 py-1 rounded {{ $latestCheck->overall_status === 'COMPLIANT' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $latestCheck->overall_status }}
                    </span>
                </p>
                <p class="text-sm mt-2">Checked: {{ $latestCheck->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    @endif
</div>
@endsection
