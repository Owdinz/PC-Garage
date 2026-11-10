function renderCartUI() {
  const cartList = document.getElementById('cart-items-list');
  const summaryItemsElement = document.getElementById('summary-items');
  const subtotalElement = document.getElementById('subtotal');
  const discountElement = document.getElementById('discount');
  const shippingElement = document.getElementById('shipping');
  const totalElement = document.getElementById('total');

  if (!cartList) return;

  const cart = CartManager.getCart();

  if (cart.length === 0) {
    cartList.innerHTML = '<p style="text-align: center; font-family: monospace; font-size: 14px; color: #666; padding: 20px 0;">There are no items in your cart</p>';
    if (summaryItemsElement) summaryItemsElement.innerHTML = '';
    if (subtotalElement) subtotalElement.textContent = CartManager.formatCurrency(0);
    if (discountElement) discountElement.textContent = CartManager.formatCurrency(0);
    if (shippingElement) shippingElement.textContent = CartManager.formatCurrency(0);
    if (totalElement) totalElement.textContent = CartManager.formatCurrency(0);
    return;
  }

  // Render Cart Items
  cartList.innerHTML = cart.map(item => `
    <div class="cart-item" data-name="${item.name}" data-price="${item.price}" style="border: 1px solid #666; border-radius: 20px; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; font-family: monospace; font-size: 12px;">
      <div>
        <div><strong>ITEM:</strong> ${item.name}</div>
        <div><strong>PRICE:</strong> ${CartManager.formatCurrency(item.price)}</div>
      </div>
      <div style="display: flex; gap: 10px; align-items: center;">
        <input type="number" class="quantity" value="${item.quantity}" min="1" max="99" onchange="handleQtyChange('${item.name}', this.value)" style="border: 1px solid #666; border-radius: 12px; padding: 3px 10px; width: 50px; text-align: center;">
        <button type="button" onclick="handleRemoveItem('${item.name}')" style="border: 1px solid #666; background: transparent; color: #000; border-radius: 12px; padding: 3px 15px; cursor: pointer;">Remove</button>
      </div>
    </div>
  `).join('');

  // Render Summary Breakdown
  if (summaryItemsElement) {
    summaryItemsElement.innerHTML = cart.map(item => `
      <div style="display: flex; justify-content: space-between; gap: 15px; font-size: 12px;">
        <span>${item.name} × ${item.quantity}</span>
        <strong>${CartManager.formatCurrency(item.price * item.quantity)}</strong>
      </div>
    `).join('');
  }

  const subtotal = CartManager.getTotal();
  const discount = 0;
  const shipping = 0;
  const total = subtotal - discount + shipping;

  if (subtotalElement) subtotalElement.textContent = CartManager.formatCurrency(subtotal);
  if (discountElement) discountElement.textContent = CartManager.formatCurrency(discount);
  if (shippingElement) shippingElement.textContent = CartManager.formatCurrency(shipping);
  if (totalElement) totalElement.textContent = CartManager.formatCurrency(total);
}

function handleQtyChange(name, quantity) {
  CartManager.updateQuantity(name, quantity);
  renderCartUI();
}

function handleRemoveItem(name) {
  CartManager.removeItem(name);
  renderCartUI();
}

document.addEventListener('DOMContentLoaded', () => {
  renderCartUI();
  window.addEventListener('cartUpdated', renderCartUI);
});