<div class="summaryBx">
    <div class="tite">Order Summary</div>
    <ul>
        <li>
            <span class="name">Subtotal (Incl. Tax)</span>
            <span class="amt">₹ {{ number_format($subTotal, 2) }}</span>
        </li>
        <li>
            <span class="name">Discount </span>
            <span class="amt">- ₹ {{ number_format($discountAmount, 2) }}</span>
        </li>
        <li>
            <span class="name">Shipping </span>
            <span class="amt">Free</span>
        </li>
        @if (app('appSettings')->get('tax.percentage')->value ?? 0 > 0)
            <li>
                <span class="name">Tax ({{ app('appSettings')->get('tax.percentage')->value ?? 0 }}%) </span>
                <span class="amt">₹ {{ number_format($taxAmount, 2) }}</span>
            </li>
        @endif
    </ul>
    <div class="totlBx">
        <div class="txt">Total</div>
        <div class="gTotal">₹ {{ number_format($grandTotal, 2) }}</div>
    </div>
    <a href="{{ route('checkout') }}" class="checkoutBtn hoveranim" aria-label="checkout_Btn">
        <span>Checkout</span>
    </a>
    <a href="javascript:void(0)" data-bs-dismiss="modal" data-bs-target="#cartModal" class="continueBtn"
        aria-label="shop_btn">Continue Shopping</a>
</div>
