<div class="title">Order Summary</div>
<div class="item">
    <div class="txt">Subtotal (Incl. Tax)</div>
    <div class="lgTxt">₹ {{ number_format($subTotal, 2) }}</div>
</div>
<div class="item">
    <div class="txt">Discount</div>
    <div class="txt">- ₹ {{ number_format($discountAmount, 2) }}</div>
</div>
<div class="item">
    <div class="txt">Shipping Charge</div>
    <div class="txt">Free</div>
</div>
@if (app('appSettings')->get('tax.percentage')->value ?? 0 > 0)
    <div class="item">
        <div class="txt">Tax ({{ app('appSettings')->get('tax.percentage')->value ?? 0 }}%) </div>
        <div class="txt">₹ {{ number_format($taxAmount, 2) }}</div>
    </div>
@endif
<div class="item">
    <div class="lgTxt">Total</div>
    <div class="lgTxt">₹ {{ number_format($grandTotal, 2) }}</div>
</div>
