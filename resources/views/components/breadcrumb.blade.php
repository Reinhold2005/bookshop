@if(isset($breadcrumbs))
<div class="bg-white rounded-lg shadow p-3 mb-4">
    <div class="flex items-center space-x-2 text-sm">
        <a href="{{ url('/') }}" class="text-blue-500 hover:underline">Home</a>
        @foreach($breadcrumbs as $breadcrumb)
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            @if($loop->last)
                <span class="text-gray-600">{{ $breadcrumb['name'] }}</span>
            @else
                <a href="{{ $breadcrumb['url'] }}" class="text-blue-500 hover:underline">{{ $breadcrumb['name'] }}</a>
            @endif
        @endforeach
    </div>
</div>
@endif