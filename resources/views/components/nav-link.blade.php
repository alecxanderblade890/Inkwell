<a href="{{ $href }}" class="text-lg font-semibold {{ request()->is($href) ? 'text-blue-600' : 'text-gray-700' }} hover:text-blue-800 transition-colors">
    {{ $slot }}
</a>