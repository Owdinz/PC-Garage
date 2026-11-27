
/*
 * PC-Garage — BUG-013
 * Database-Backed Checkout
 * Payment support: Cash on Delivery only
 */

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('checkout-form');
  const orderList = document.querySelector('.order-list');
  const totalDisplay = document.getElementById('checkout-total-display');
  const secureOrderBtn = document.getElementById('secure-order');

  const checkPreviousOrderBtn =
    document.getElementById('check-previous-order');

  const status = document.getElementById('order-status');


  if (!form || !orderList || !secureOrderBtn) return;

  let cartItems = [];
  let submitting = false;



  // BUG-013: Preserve pending checkout attempts across reloads.
  const CHECKOUT_PENDING_KEY = 'pcgarage_pending_checkout';

  let checkoutRequestId = null;

  function createCheckoutRequestId() {
    const bytes = new Uint8Array(32);
    crypto.getRandomValues(bytes);

    return Array.from(
      bytes,
      byte => byte.toString(16).padStart(2, '0')
    ).join('');
  }

  function getPendingCheckout() {
    try {
      const saved = sessionStorage.getItem(CHECKOUT_PENDING_KEY);
      if (!saved) return null;

      const pending = JSON.parse(saved);

      if (
        typeof pending?.requestId !== 'string' ||
        !/^[a-f0-9]{64}$/.test(pending.requestId) ||
        typeof pending?.payload !== 'object' ||
        pending.payload === null ||
        Array.isArray(pending.payload)
      ) {
        return null;
      }

      return pending;
    } catch {
      return null;
    }
  }

  function savePendingCheckout(payload) {
    const pending = {
      requestId: payload.checkoutRequestId,
      payload
    };

    sessionStorage.setItem(
      CHECKOUT_PENDING_KEY,
      JSON.stringify(pending)
    );
  }

  function clearPendingCheckout() {
    sessionStorage.removeItem(CHECKOUT_PENDING_KEY);
    checkoutRequestId = null;
  }

  function getCheckoutRequestId() {
    if (!checkoutRequestId) {
      checkoutRequestId = createCheckoutRequestId();
    }

    return checkoutRequestId;
  }



  async function recoverPendingCheckout() {
    const pending = getPendingCheckout();

    if (!pending || submitting) return false;

    submitting = true;
    secureOrderBtn.disabled = true;

    showStatus('Checking your previous checkout attempt...');

    try {
      const response = await fetch(
        'checkout-status.php?request_id=' +
        encodeURIComponent(pending.requestId),
        {
          method: 'GET',
          credentials: 'same-origin',
          headers: {
            'Accept': 'application/json'
          },
          cache: 'no-store'
        }
      );

      const result = await response.json();

      if (!response.ok || !result.success) {
        throw new Error(
          result.message || 'Unable to check checkout status.'
        );
      }

      if (result.found === true) {
        clearPendingCheckout();

        cartItems = [];
        renderCart();

        showStatus(
          `Order #${result.order_id} was already placed successfully!`
        );

        return true;
      }

      showStatus(
        'No completed order was found for this request. ' +
        'No new order has been submitted.',
        true
      );

      return false;

    } catch (error) {
      showStatus(
        error.message || 'Unable to check your previous order.',
        true
      );

      return false;

    } finally {
      submitting = false;

      secureOrderBtn.disabled =
        cartItems.length === 0 ||
        getPendingCheckout() !== null;

      secureOrderBtn.style.opacity =
        secureOrderBtn.disabled ? '0.5' : '1';

      secureOrderBtn.style.cursor =
        secureOrderBtn.disabled ? 'not-allowed' : 'pointer';
    }
  }



  const formatCurrency = amount =>
    CartManager.formatCurrency(amount);

  function showStatus(message, isError = false) {
    if (!status) return;

    status.hidden = false;
    status.textContent = message;
    status.style.color = isError ? '#b91c1c' : '#166534';
  }

  function setTotal(amount) {
    if (totalDisplay) {
      totalDisplay.textContent = formatCurrency(amount);
    }
  }

  function renderCart() {
    orderList.replaceChildren();

    if (cartItems.length === 0) {
      const empty = document.createElement('p');
      empty.className = 'empty-cart';
      empty.textContent =
        'Your cart is empty. Add items before placing an order.';

      orderList.appendChild(empty);
      setTotal(0);
      secureOrderBtn.disabled = true;
      secureOrderBtn.style.opacity = '0.5';
      secureOrderBtn.style.cursor = 'not-allowed';
      return;
    }

    let total = 0;

    cartItems.forEach(item => {
      const price = Number(item.price);
      const quantity = Number(item.quantity);
      total += price * quantity;

      const article = document.createElement('article');
      article.className = 'order-item';

      const details = document.createElement('div');
      details.className = 'item-details';

      const name = document.createElement('p');
      const nameLabel = document.createElement('span');
      nameLabel.textContent = 'ITEM: ';
      name.append(nameLabel, document.createTextNode(item.name));

      const priceLine = document.createElement('p');
      const priceLabel = document.createElement('span');
      priceLabel.textContent = 'PRICE: ';
      priceLine.append(
        priceLabel,
        document.createTextNode(formatCurrency(price))
      );

      details.append(name, priceLine);

      const actions = document.createElement('div');
      actions.className = 'item-actions';

      const quantityDisplay = document.createElement('span');
      quantityDisplay.textContent = `Quantity: ${quantity}`;

      actions.appendChild(quantityDisplay);
      article.append(details, actions);
      orderList.appendChild(article);
    });


    setTotal(total);

    const hasPendingCheckout = getPendingCheckout() !== null;

    secureOrderBtn.disabled =
      submitting ||
      cartItems.length === 0 ||
      hasPendingCheckout;

    secureOrderBtn.style.opacity =
      secureOrderBtn.disabled ? '0.5' : '1';

    secureOrderBtn.style.cursor =
      secureOrderBtn.disabled ? 'not-allowed' : 'pointer';

  }

  async function loadCheckoutCart() {
    secureOrderBtn.disabled = true;

    try {
      const result = await CartManager.loadCart();
      cartItems = result.items || [];
      renderCart();
    } catch (error) {
      cartItems = [];
      renderCart();
      showStatus(
        error.message || 'Unable to load checkout items.',
        true
      );
    }
  }

  form.addEventListener('submit', async event => {
    event.preventDefault();

    if (submitting || cartItems.length === 0) return;
    if (!form.reportValidity()) return;

    const data = new FormData(form);

    const payload = {
      checkoutRequestId: getCheckoutRequestId(),
      fullName: String(data.get('fullName') || '').trim(),
      contact: String(data.get('contact') || '').trim(),
      email: String(data.get('email') || '').trim(),
      address: String(data.get('address') || '').trim(),
      postalCode: String(data.get('postalCode') || '').trim(),
      notes: String(data.get('notes') || '').trim(),
      shippingMethod: String(data.get('shippingMethod') || ''),
      paymentMethod: String(data.get('paymentMethod') || '')
    };

    if (payload.paymentMethod !== 'cod') {
      showStatus(
        'Only Cash on Delivery is currently available.',
        true
      );
      return;
    }


    const pending = getPendingCheckout();

    if (pending) {
      // Do not silently replace an unresolved checkout attempt.
      showStatus(
        'A previous checkout attempt is pending. ' +
        'Please verify its result before placing another order.',
        true
      );
      return;
    }

    try {
      savePendingCheckout(payload);
    } catch (error) {
      showStatus(
        'Unable to preserve checkout request. Please enable session storage.',
        true
      );
      return;
    }

    submitting = true;
    secureOrderBtn.disabled = true;
    showStatus('Processing your order...');


    try {
      const response = await fetch('checkout-submit.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-Token':
            document.querySelector(
              'meta[name="cart-csrf-token"]'
            )?.content || ''
        },
        body: JSON.stringify(payload)
      });

      const result = await response.json();

      if (!response.ok || !result.success) {
        throw new Error(
          result.message || 'Checkout could not be completed.'
        );
      }

      clearPendingCheckout();

      showStatus(
        `Order #${result.order_id} placed successfully!`
      );

      cartItems = [];
      renderCart();

      // Do not clear the legacy localStorage cart here:
      // Custom Build still uses it until its own integration.

      alert(
        `Order #${result.order_id} placed successfully!\n` +
        `Total: ${formatCurrency(result.total)}\n` +
        'Payment: Cash on Delivery'
      );

      // Stay on the confirmation message for now.
      // Delivery tracking integration will be handled later.

    } catch (error) {
      showStatus(
        error.message || 'Unable to place your order.',
        true
      );

    } finally {
      submitting = false;

      secureOrderBtn.disabled =
        submitting ||
        cartItems.length === 0 ||
        getPendingCheckout() !== null;


      secureOrderBtn.style.opacity =
        secureOrderBtn.disabled ? '0.5' : '1';

      secureOrderBtn.style.cursor =
        secureOrderBtn.disabled ? 'not-allowed' : 'pointer';

      if (checkPreviousOrderBtn) {
        checkPreviousOrderBtn.hidden =
          getPendingCheckout() === null;
      }
    }
  });


  const hasPendingCheckout = getPendingCheckout() !== null;

  if (checkPreviousOrderBtn) {
    checkPreviousOrderBtn.hidden = !hasPendingCheckout;

    checkPreviousOrderBtn.addEventListener('click', async () => {
      checkPreviousOrderBtn.disabled = true;

      try {
        await recoverPendingCheckout();
      } finally {
        checkPreviousOrderBtn.disabled = false;
        checkPreviousOrderBtn.hidden =
          getPendingCheckout() === null;
      }
    });
  }

  if (hasPendingCheckout) {
    showStatus(
      'A previous checkout attempt is pending. ' +
      'Click CHECK PREVIOUS ORDER to verify whether it was saved.',
      true
    );
  }

  loadCheckoutCart();

});
