@props(['headers' => [], 'data' => [], 'emptyMessage' => 'No data found', 'colspan' => 4])

<div class="overflow-x-auto bg-white shadow-md rounded-lg">
    <table class="w-full divide-y divide-gray-200">
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