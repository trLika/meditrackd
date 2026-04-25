@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center">
        <ul class="pagination pagination-sm mb-0 gap-1">
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link border-0 bg-transparent">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link rounded-circle fw-bold shadow-sm" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background-color: #198754; border-color: #198754;">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link rounded-circle text-success border-0 shadow-sm" href="{{ $url }}" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background-color: white;">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>
    </nav>
@endif
