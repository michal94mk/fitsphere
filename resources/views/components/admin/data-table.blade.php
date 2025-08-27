@props(['headers' => [], 'data' => [], 'emptyMessage' => 'No data found', 'colspan' => 4])

<div class="overflow-x-auto bg-white shadow-md rounded-lg" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
    <style>
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f3f4f6;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
    <table class="w-full divide-y divide-gray-200" style="min-width: 800px;">
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ $header['align'] ?? 'text-left' }}">
                        {{ $header['label'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            {{ $slot }}
            
            @if($data->isEmpty())
                <tr>
                    <td colspan="{{ $colspan }}" class="px-4 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div> 