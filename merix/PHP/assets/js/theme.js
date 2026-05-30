/*
 * 本文件负责全站日夜模式切换。
 * 用户选择会保存到浏览器本地，刷新后保持同一主题。
 */

const themeKey = 'merix-demo-theme';
const savedTheme = localStorage.getItem(themeKey);
if (savedTheme) {
  document.body.dataset.theme = savedTheme;
}

document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
  button.addEventListener('click', () => {
    const next = document.body.dataset.theme === 'dark' ? 'light' : 'dark';
    document.body.dataset.theme = next;
    localStorage.setItem(themeKey, next);
  });
});
