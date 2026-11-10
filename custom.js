// Global Item Deletion Handler for Custom Builder
function deleteItem(buttonElement) {
  const item = buttonElement.closest('.component-item');
  if (item) {
    item.remove();
  }
}

document.addEventListener('DOMContentLoaded', () => {

  // Preset vs Custom Request Mode Toggle
  const modeButtons = document.querySelectorAll('.mode-btn');
  modeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      modeButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  // Budget Range Slider Handler
  const budgetRange = document.getElementById('budget-range');
  const budgetDisplay = document.getElementById('current-budget-display');

  if (budgetRange && budgetDisplay) {
    budgetRange.addEventListener('input', (e) => {
      const val = Number(e.target.value);
      budgetDisplay.textContent = `₱${val.toLocaleString('en-PH')}`;
    });
  }

  // Primary Use Option Selector
  const useCards = document.querySelectorAll('.use-card');
  useCards.forEach(card => {
    card.addEventListener('click', () => {
      useCards.forEach(c => c.classList.remove('active'));
      card.classList.add('active');
    });
  });

  // Preset Card Selection
  const presetCards = document.querySelectorAll('.preset-card');
  presetCards.forEach(card => {
    card.addEventListener('click', () => {
      presetCards.forEach(c => c.classList.remove('active'));
      card.classList.add('active');
    });
  });

});