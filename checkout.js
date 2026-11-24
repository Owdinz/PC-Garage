document.addEventListener('DOMContentLoaded', () => {
  const orderList = document.querySelector('.order-list');
  const emptyCart = document.querySelector('.empty-cart');
  const secureOrderBtn = document.querySelector('#secure-order');
  const status = document.querySelector('#order-status');
  const toast = document.querySelector('#toast-notification');
  const toastMessage = document.querySelector('#toast-message');
  const undoBtn = document.querySelector('#undo-button');
  const totalDisplay = document.querySelector('#checkout-total-display');

  let lastDeletedItem = null;
  let undoTimer = null;

  function renderCheckoutList() {
    if (!orderList) return;

    const cart = CartManager.getCart();

    if (cart.length === 0) {
      orderList.querySelectorAll('.order-item').forEach(el => el.remove());
      if (emptyCart) emptyCart.hidden = false;
      if (secureOrderBtn) secureOrderBtn.disabled = true;
      if (totalDisplay) totalDisplay.textContent = CartManager.formatCurrency(0);
      return;
    }

    if (emptyCart) emptyCart.hidden = true;
    if (secureOrderBtn) secureOrderBtn.disabled = false;

    orderList.querySelectorAll('.order-item').forEach(el => el.remove());

    cart.forEach(item => {
      const article = document.createElement('article');
      article.className = 'order-item';
      article.dataset.name = item.name;
      article.dataset.price = item.price;
      article.innerHTML = `
        <div class="item-details">
          <p><span>ITEM:</span> ${item.name}</p>
          <p><span>PRICE:</span> ${CartManager.formatCurrency(item.price)}</p>
        </div>
        <div class="item-actions">
          <input type="number" class="quantity" min="1" max="99" value="${item.quantity}" aria-label="Quantity for ${item.name}">
          <button type="button" class="delete-button" aria-label="Delete ${item.name}">DELETE</button>
        </div>
      `;
      orderList.insertBefore(article, emptyCart);
    });

    if (totalDisplay) {
      totalDisplay.textContent = CartManager.formatCurrency(CartManager.getTotal());
    }
  }

  if (orderList) {
    orderList.addEventListener('input', (e) => {
      if (e.target.classList.contains('quantity')) {
        const item = e.target.closest('.order-item');
        if (item) {
          CartManager.updateQuantity(item.dataset.name, e.target.value);
          if (totalDisplay) totalDisplay.textContent = CartManager.formatCurrency(CartManager.getTotal());
        }
      }
    });

    orderList.addEventListener('click', (event) => {
      const button = event.target.closest('.delete-button');
      if (!button) return;

      const item = button.closest('.order-item');
      if (!item) return;

      if (undoTimer) clearTimeout(undoTimer);

      const itemName = item.dataset.name;
      const currentCart = CartManager.getCart();
      lastDeletedItem = currentCart.find(i => i.name === itemName);

      CartManager.removeItem(itemName);
      renderCheckoutList();

      if (toast && toastMessage) {
        toastMessage.textContent = `"${itemName}" removed from checkout.`;
        toast.hidden = false;
        void toast.offsetWidth;
        toast.classList.add('show');

        undoTimer = setTimeout(() => {
          toast.classList.remove('show');
          setTimeout(() => { toast.hidden = true; }, 250);
          lastDeletedItem = null;
        }, 5000);
      }
    });
  }

  if (undoBtn) {
    undoBtn.addEventListener('click', () => {
      if (!lastDeletedItem) return;

      CartManager.addItem(lastDeletedItem.name, lastDeletedItem.price, lastDeletedItem.quantity);
      if (undoTimer) clearTimeout(undoTimer);
      if (toast) {
        toast.classList.remove('show');
        setTimeout(() => { toast.hidden = true; }, 250);
      }

      renderCheckoutList();
      lastDeletedItem = null;
    });
  }

  const form = document.querySelector('#checkout-form');
  if (form) {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      if (CartManager.getCart().length === 0) return;

      alert('Order placed successfully! Redirecting to live order tracking...');
      CartManager.clearCart();
      window.location.href = 'deliver.php';
    });
  }

  renderCheckoutList();
});