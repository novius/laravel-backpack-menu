<ul>
    <?php $depth = null; ?>
    @foreach($items as $key => $item)
        @if($depth == null)
            <?php $depth = $item->depth; ?>
        @endif
        @if($item->depth != $depth)
            @continue
        @endif
        <li>
            {!!  $item->link() !!}
            @if($item->hasChildren())
                <?php $children = $item->children()->get()->keyBy('id'); ?>
                @if($children->isNotEmpty())
                    <?php echo view('laravel-backpack-menu::menu', ['items' => $children]) ?>
                @endif
            @endif
        </li>
    @endforeach
</ul>
