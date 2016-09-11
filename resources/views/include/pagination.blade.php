
@if ($paginator->lastPage() > 1)
    <div class="ui pagination borderless menu">
        @if ($paginator->currentPage() > 1)
            <a href="{{ $paginator->previousPageUrl() }}" class="item">
                上一页
            </a>
        @endif

        @if ($paginator->currentPage() > 1)
            <a href="{{ $paginator->url(1) }}" class="item{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">1</a>
        @endif

        @if ($paginator->currentPage() > 4)
            <div class="disabled item">
                ...
            </div>
        @endif
        @for ($i = $paginator->currentPage()-2; $i <= $paginator->lastPage() && ($i < $paginator->currentPage()+3 || $i < 7); $i++)
            @if ($paginator->currentPage() == $i)
                <div class="item disabled">{{$i}}</div>
            @elseif($i > 1)
                <a href="{{ $paginator->url($i) }}" class="item{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                    {{ $i }}
                </a>
            @endif
        @endfor
        @if ($paginator->currentPage() < $paginator->lastPage())
            <div class="disabled item">
            ...
            </div>
        @endif

        @if ($paginator->currentPage() < $paginator->lastPage())
            <a href="{{ $paginator->nextPageUrl() }}" class="item">
                下一页
            </a>
        @endif
    </div>
@endif