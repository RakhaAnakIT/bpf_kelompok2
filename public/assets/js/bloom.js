/* BloomShop - pengganti interaksi React (useState) dengan JavaScript biasa */
document.addEventListener("DOMContentLoaded", function () {
  // 1. Buka/tutup menu HP
  var btnMenu = document.getElementById("btn-menu");
  var menu = document.getElementById("mobile-menu");
  if (btnMenu && menu) {
    // ikon: garis tiga (menu tertutup) / silang (menu terbuka)
    var iconX = btnMenu.innerHTML;
    var iconMenu =
      '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu h-6 w-6 text-gray-700"><path d="M4 12h16"></path><path d="M4 18h16"></path><path d="M4 6h16"></path></svg>';
    btnMenu.innerHTML = iconMenu;
    btnMenu.addEventListener("click", function () {
      menu.classList.toggle("hidden");
      btnMenu.innerHTML = menu.classList.contains("hidden") ? iconMenu : iconX;
      btnMenu.setAttribute("aria-expanded", !menu.classList.contains("hidden"));
    });
  }

  // 2. Buka/tutup kotak cari HP
  var btnSearch = document.getElementById("btn-search");
  var search = document.getElementById("mobile-search");
  if (btnSearch && search) {
    btnSearch.addEventListener("click", function () {
      search.classList.toggle("hidden");
      var input = search.querySelector("input");
      if (input && !search.classList.contains("hidden")) input.focus();
    });
  }

  // 3. Header berubah saat halaman di-scroll
  var header = document.getElementById("site-header");
  if (header) {
    var onScroll = function () {
      var s = window.scrollY > 10;
      header.classList.toggle("bg-white/95", s);
      header.classList.toggle("backdrop-blur-xl", s);
      header.classList.toggle("shadow-lg", s);
      header.classList.toggle("bg-white/80", !s);
      header.classList.toggle("backdrop-blur-md", !s);
      header.classList.toggle("shadow-sm", !s);
    };
    window.addEventListener("scroll", onScroll);
    onScroll();
  }

  // 4. Tombol suka (ikon hati) pada kartu produk
  document.querySelectorAll('button[name="Like Button"]').forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      btn.classList.toggle("opacity-100");
      btn.classList.toggle("text-destructive");
      var svg = btn.querySelector("svg");
      if (svg) svg.classList.toggle("fill-current");
    });
  });

  // 5. Tombol tambah/kurang jumlah (halaman produk & keranjang)
  document.querySelectorAll("svg.lucide-plus, svg.lucide-minus").forEach(function (icon) {
    var btn = icon.closest("button");
    if (!btn) return;
    btn.disabled = false;
    btn.addEventListener("click", function () {
      var box = btn.parentElement;
      var num = Array.prototype.find.call(box.querySelectorAll("span, input"), function (el) {
        return /^\d+$/.test((el.value || el.textContent).trim());
      });
      if (!num) return;
      var val = parseInt(num.value || num.textContent, 10);
      val = icon.classList.contains("lucide-plus") ? val + 1 : Math.max(1, val - 1);
      if (num.tagName === "INPUT") num.value = val;
      else num.textContent = val;
    });
  });
});
