const list = document.getElementById('product-list');

if (list) {
  const saved = localStorage.getItem('view');
  if (saved) {
    list.dataset.view = saved;
  }

  document.querySelectorAll('.view-toggle button').forEach((btn) => {
    btn.addEventListener('click', () => {
      list.dataset.view = btn.dataset.view;
      localStorage.setItem('view', btn.dataset.view);
    });
  });
}
