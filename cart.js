
/*
 * PC-Garage — BUG-011B
 * Database-Backed Shopping Cart
 *
 * Loads cart items from MySQL through cart-handler.php.
 * Supports quantity updates, item removal, and order totals.
 */

const CartPage = {
  items: [],
  loading: false,

  escapeHTML(value) {
    return String(value ?? '').replace(/[&<>"']/g, char => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    }[char]));
  },

  async loadCart() {
    const cartList = document.getElementById('cart-items-list');

    if (!cartList || this.loading) return;

    this.loading = true;

    try {
      const result = await CartManager.loadCart();

      this.items = result.items || [];
      this.renderCart();
    } catch (error) {
      console.error('Cart loading error:', error);

      cartList.textContent =
        error.message || 'Unable to load your shopping cart.';
    } finally {
      this.loading = false;
    }
  },

  renderCart() {
    const cartList = document.getElementById('cart-items-list');
    const summary = document.getElementById('summary-items');

    if (!cartList) return;

    cartList.innerHTML = '';

    if (summary) {
      summary.innerHTML = '';
    }

    if (this.items.length === 0) {
      const emptyMessage = document.createElement('p');

      emptyMessage.textContent =
        'There are no items in your cart.';

      emptyMessage.style.cssText = `
        text-align: center;
        font-family: monospace;
        font-size: 14px;
        color: #666;
        padding: 20px 0;
      `;

      cartList.appendChild(emptyMessage);
    }

    let subtotal = 0;

    this.items.forEach(item => {
      const productId = Number(item.product_id);
      const quantity = Number(item.quantity);
      const price = Number(item.price);
      const stock = Number(item.stock_quantity);

      subtotal += price * quantity;

      const cartItem = document.createElement('div');

      cartItem.className = 'cart-item';
      cartItem.dataset.productId = String(productId);

      cartItem.style.cssText = `
        border: 1px solid #666;
        border-radius: 20px;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        font-family: monospace;
        font-size: 12px;
      `;

      const productInfo = document.createElement('div');

      const productName = document.createElement('div');
      const nameLabel = document.createElement('strong');

      nameLabel.textContent = 'ITEM: ';
      productName.append(
        nameLabel,
        document.createTextNode(item.name)
      );

      const productPrice = document.createElement('div');
      const priceLabel = document.createElement('strong');

      priceLabel.textContent = 'PRICE: ';
      productPrice.append(
        priceLabel,
        document.createTextNode(
          CartManager.formatCurrency(price)
        )
      );

      productInfo.append(productName, productPrice);

      const actions = document.createElement('div');

      actions.style.cssText = `
        display: flex;
        gap: 10px;
        align-items: center;
      `;

      const quantityInput = document.createElement('input');

      quantityInput.type = 'number';
      quantityInput.className = 'quantity';
      quantityInput.value = quantity;
      quantityInput.min = '1';
      quantityInput.max = String(
        Math.max(1, Math.min(99, stock))
      );

      quantityInput.setAttribute(
        'aria-label',
        `Quantity for ${item.name}`
      );

      quantityInput.style.cssText = `
        border: 1px solid #666;
        border-radius: 12px;
        padding: 3px 10px;
        width: 60px;
        text-align: center;
      `;

      const removeButton = document.createElement('button');

      removeButton.type = 'button';
      removeButton.className = 'remove-cart-item';
      removeButton.textContent = 'Remove';

      removeButton.style.cssText = `
        border: 1px solid #666;
        background: transparent;
        color: #000;
        border-radius: 12px;
        padding: 3px 15px;
        cursor: pointer;
      `;

      actions.append(quantityInput, removeButton);
      cartItem.append(productInfo, actions);
      cartList.appendChild(cartItem);

      if (summary) {
        const summaryRow = document.createElement('div');

        summaryRow.style.cssText = `
          display: flex;
          justify-content: space-between;
          gap: 15px;
          font-size: 12px;
        `;

        const summaryName = document.createElement('span');

        summaryName.textContent =
          `${item.name} × ${quantity}`;

        const summaryPrice = document.createElement('strong');

        summaryPrice.textContent =
          CartManager.formatCurrency(price * quantity);

        summaryRow.append(summaryName, summaryPrice);
        summary.appendChild(summaryRow);
      }
    });

    this.updateAmount('subtotal', subtotal);
    this.updateAmount('discount', 0);
    this.updateAmount('shipping', 0);
    this.updateAmount('total', subtotal);
  },

  updateAmount(elementId, amount) {
    const element = document.getElementById(elementId);

    if (element) {
      element.textContent =
        CartManager.formatCurrency(amount);
    }
  },

  async updateQuantity(productId, quantity) {
    try {
      await CartManager.updateProduct(productId, quantity);
      await this.loadCart();
    } catch (error) {
      alert(error.message || 'Unable to update quantity.');
      await this.loadCart();
    }
  },

  async removeItem(productId) {
    try {
      await CartManager.removeProduct(productId);
      await this.loadCart();
    } catch (error) {
      alert(error.message || 'Unable to remove item.');
      await this.loadCart();
    }
  }
};

// Initialize cart after the page finishes loading.
document.addEventListener('DOMContentLoaded', () => {
  const cartList = document.getElementById('cart-items-list');

  if (!cartList) return;

  // Update product quantity.
  cartList.addEventListener('change', async event => {
    if (!event.target.matches('.quantity')) return;

    const row = event.target.closest('.cart-item');
    if (!row) return;

    const productId = Number(row.dataset.productId);
    const quantity = Number(event.target.value);
    const maxQuantity = Number(event.target.max);

    if (
      !Number.isInteger(quantity) ||
      quantity < 1 ||
      quantity > 99 ||
      quantity > maxQuantity
    ) {
      alert('Please enter a valid quantity within available stock.');
      await CartPage.loadCart();
      return;
    }

    event.target.disabled = true;

    await CartPage.updateQuantity(productId, quantity);
  });

  // Remove product from cart.
  cartList.addEventListener('click', async event => {
    const button = event.target.closest('.remove-cart-item');

    if (!button) return;

    const row = button.closest('.cart-item');

    if (!row) return;

    button.disabled = true;

    await CartPage.removeItem(
      Number(row.dataset.productId)
    );
  });

  // Load saved MySQL cart items.
  CartPage.loadCart();
});
