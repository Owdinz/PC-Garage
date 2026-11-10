// cart-manager.js - Centralized localStorage Cart Manager for PC-Garage
const CartManager = {
  STORAGE_KEY: 'pc_garage_cart',

  getCart() {
    try {
      return JSON.parse(localStorage.getItem(this.STORAGE_KEY)) || [];
    } catch (e) {
      console.error('Error reading cart from localStorage', e);
      return [];
    }
  },

  saveCart(cart) {
    try {
      localStorage.setItem(this.STORAGE_KEY, JSON.stringify(cart));
      window.dispatchEvent(new Event('cartUpdated'));
    } catch (e) {
      console.error('Error saving cart to localStorage', e);
    }
  },

  addItem(name, price, quantity = 1, image = '') {
    const cart = this.getCart();
    const cleanPrice = typeof price === 'number' ? price : Number(String(price).replace(/[^0-9.]/g, ''));
    const existing = cart.find(item => item.name === name);

    if (existing) {
      existing.quantity += Number(quantity) || 1;
    } else {
      cart.push({
        name: name,
        price: cleanPrice,
        quantity: Math.max(1, Number(quantity) || 1),
        image: image
      });
    }

    this.saveCart(cart);
  },

  updateQuantity(name, quantity) {
    const cart = this.getCart();
    const item = cart.find(i => i.name === name);
    if (item) {
      item.quantity = Math.max(1, Number(quantity) || 1);
      this.saveCart(cart);
    }
  },

  removeItem(name) {
    let cart = this.getCart();
    cart = cart.filter(item => item.name !== name);
    this.saveCart(cart);
  },

  clearCart() {
    localStorage.removeItem(this.STORAGE_KEY);
    window.dispatchEvent(new Event('cartUpdated'));
  },

  getTotal() {
    return this.getCart().reduce((sum, item) => sum + (item.price * item.quantity), 0);
  },

  getItemCount() {
    return this.getCart().reduce((sum, item) => sum + item.quantity, 0);
  },

  formatCurrency(amount) {
    return (Number(amount) || 0).toLocaleString('en-PH', {
      style: 'currency',
      currency: 'PHP',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  }
};