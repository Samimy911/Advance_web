(function(){
  const key='site-theme';
  function apply(t){ document.body.classList.toggle('light-mode', t==='light'); }
  const cur = localStorage.getItem(key) || 'dark'; apply(cur);
  window.toggleTheme = function(){ const next = (localStorage.getItem(key)=='dark')?'light':'dark'; localStorage.setItem(key,next); apply(next); const btn=document.getElementById('theme-toggle-btn'); if(btn) btn.innerText = next==='dark'?'🌙':'☀️'; };
  window.addEventListener('DOMContentLoaded', ()=>{ const btn=document.getElementById('theme-toggle-btn'); if(btn){btn.innerText = (localStorage.getItem(key)||'dark')==='dark'?'🌙':'☀️'; btn.addEventListener('click', toggleTheme);} });
})();