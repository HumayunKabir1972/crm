<div class="crm-top-products">
    <ul class="crm-top-products__list">
        @forelse($products as $product)
            <li class="crm-top-products__item">
                <div class="crm-top-products__product-name">{{ $product->name }}</div>
                <div class="crm-top-products__deals-count">{{ $product->deals_count }} deals</div>
            </li>
        @empty
            <li class="crm-top-products__empty">No products found.</li>
        @endforelse
    </ul>
</div>
