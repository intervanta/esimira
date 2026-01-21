// ============================
// GLOBAL FUNCTION: SCROLL TO TOP
// ============================
function scrollToPosition(position = 0, behavior = "smooth") {
  window.scrollTo({ top: position, behavior });
}

// ============================
// CUSTOM SELECT HANDLER
// ============================
function initCustomSelect() {
  const selectDropdownLists = document.querySelectorAll(".select-dropdown-list");

  selectDropdownLists.forEach((dropdownList) => {
    const selectBox = dropdownList.closest(".custom-select").querySelector("#inquiry");
    const selectedOptionLabel = dropdownList.closest(".custom-select").querySelector(".select");
    const arrowIcon = dropdownList.closest(".custom-select").querySelector(".arrowIcon");

    selectBox.addEventListener("click", (event) => {
      event.stopPropagation();
      dropdownList.classList.toggle("visible");
      arrowIcon.classList.toggle("rotate");
    });

    dropdownList.addEventListener("click", (event) => {
      const selectedValue = event.target.getAttribute("data-value");
      selectedOptionLabel.textContent = selectedValue;
      dropdownList.classList.add("selected");
      dropdownList.classList.remove("visible");
      arrowIcon.classList.remove("rotate");
      scrollToPosition(window.scrollY, "auto");
    });

    document.addEventListener("click", () => {
      dropdownList.classList.remove("visible");
      arrowIcon.classList.remove("rotate");
    });
  });
}

// ============================
// SCROLL NAV CLASS HANDLER
// ============================
function initScrollNav() {
  const contentEl = document.querySelector(".content");
  const navEl = document.querySelector("nav");

  if (!contentEl || !navEl) return;

  contentEl.addEventListener("scroll", () => {
    if (contentEl.scrollTop > 1) {
      navEl.classList.add("scrolled");
    } else {
      navEl.classList.remove("scrolled");
    }
  });
}

// ============================
// FADE IN OBSERVER
// ============================
function initFadeInObserver() {
  const faders = document.querySelectorAll(".fade-in");

  const observerOptions = {
    root: null,
    rootMargin: "0px",
    threshold: 0.3,
  };

  const fadeInObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("show");
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  faders.forEach((fader) => fadeInObserver.observe(fader));
}

// ============================
// TAB HANDLER
// ============================
function initTabs() {
  const tabsContainers = document.querySelectorAll("[data-tab-container]");

  tabsContainers.forEach((container) => {
    const tabs = container.querySelectorAll("[data-tab]");
    const contents = container.querySelectorAll("[data-content]");

    tabs.forEach((tab) => {
      tab.addEventListener("click", () => {
        tabs.forEach((t) => t.classList.remove("active"));
        tab.classList.add("active");

        const targetContent = tab.getAttribute("data-tab");

        contents.forEach((content) => {
          content.classList.remove("active");
          if (content.getAttribute("data-content") === targetContent) {
            content.classList.add("active");
          }
        });
      });
    });
  });
}

// ============================
// INIT ALL FUNCTIONS
// ============================
function initMain() {
  initCustomSelect();
  initScrollNav();
  initFadeInObserver();
  initTabs();
}

document.addEventListener("DOMContentLoaded", initMain);

// admin side menus clicks
const menuLinks = document.querySelectorAll("#sidebarMenu a");
const sections = document.querySelectorAll(".content-box > *");

menuLinks.forEach(link => {
  link.addEventListener("click", e => {
    e.preventDefault();

    // Remove active state and reset icons
    menuLinks.forEach(l => {
      l.classList.remove("active");
      const img = l.querySelector("img");
      img.src = l.getAttribute("data-icon-black");
    });

    // Activate the clicked item
    link.classList.add("active");
    const activeImg = link.querySelector("img");
    activeImg.src = link.getAttribute("data-icon-white");

    // Hide all content sections
    sections.forEach(section => section.classList.add("hidden"));

    // Show the selected section
    const targetId = link.getAttribute("data-target");
    const targetSection = document.getElementById(targetId);
    if (targetSection) targetSection.classList.remove("hidden");
  });
});



// Show/Hide password logic
const passwordInput = document.getElementById("password");
const toggleIcon = document.getElementById("togglePassword");

if (passwordInput && toggleIcon) {
  toggleIcon.addEventListener("click", () => {
    const isHidden = passwordInput.type === "password";
    passwordInput.type = isHidden ? "text" : "password";

    toggleIcon.src = isHidden
    ? '../assets/images/eye-closed.png' 
    : '../assets/images/280_729.svg';  
    toggleIcon.alt = isHidden ? "Hide password" : "Show password";
  });
}

// new js
   // =============================
// SEARCH MODAL (kept this version)
// =============================
const searchInput = document.getElementById('searchInput');
const modalOverlay = document.querySelector('.search-modal-overlay');
const closeModal = document.getElementById('closeModal');

if (searchInput) {
  searchInput.addEventListener('focus', () => {
    modalOverlay.classList.remove('hidden');
  });
}

if (closeModal) {
  closeModal.addEventListener('click', () => {
    modalOverlay.classList.add('hidden');
  });
}
// search onclick appear list
  const searchList = document.getElementById('searchList');

  // Show list when input is clicked or focused
  if(searchInput){
  searchInput.addEventListener('focus', () => {
    searchList.classList.remove('hidden');
  });
}
// Hide list when clicking outside
document.addEventListener('click', (e) => {
    if (searchInput && searchList) {
  if (!searchInput.contains(e.target) && !searchList.contains(e.target)) {
    searchList.classList.add('hidden');
  }
}
});

document.addEventListener("DOMContentLoaded", () => {
  const modalOverlay = document.getElementById("compatibility-modal");

  if (!modalOverlay) return;  // Prevents ALL future errors

  modalOverlay.addEventListener("click", (e) => {
    if (e.target === modalOverlay) {
      modalOverlay.classList.add("hidden");
    }
  });
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') modalOverlay.classList.add('hidden');
});

// =============================
// NAV ITEMS ACTIVE STATE
// =============================
document.addEventListener('DOMContentLoaded', () => {
  const navItems = document.querySelectorAll('.nav-item');

  navItems.forEach(item => {
    item.addEventListener('click', (e) => {
      e.preventDefault();
      navItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
    });
  });
});

// =============================
// MOBILE MENU TOGGLE
// =============================
const menuButton = document.querySelector('[aria-label="Open menu"]');
let menuOpen = false;

if (menuButton) {
  menuButton.addEventListener('click', () => {
    menuOpen = !menuOpen;
    console.log('Menu toggled:', menuOpen);
  });
}

// =============================
// PLAN TABS (kept only this final version)
// =============================
const tabs = document.querySelectorAll('.plan-tab');
const panes = document.querySelectorAll('.tab-pane');
const indicator = document.getElementById('tab-indicator');

function activateTab(tab) {
  tabs.forEach(t => t.classList.remove('active-tab', 'font-bold', 'text-[#f4633a]'));
  tab.classList.add('active-tab', 'font-bold', 'text-[#f4633a]');

  const rect = tab.getBoundingClientRect();
  const parentRect = tab.parentElement.getBoundingClientRect();

  indicator.style.width = rect.width + 'px';
  indicator.style.left = (rect.left - parentRect.left) + 'px';

  const target = tab.dataset.tab;
  panes.forEach(p => {
    p.classList.toggle('hidden', p.dataset.content !== target);
  });
}

tabs.forEach(tab => {
  tab.addEventListener('click', () => activateTab(tab));
});

window.addEventListener('load', () => {
  const active = document.querySelector('.plan-tab.active-tab');
  if (active) activateTab(active);
});

window.addEventListener('resize', () => {
  const active = document.querySelector('.plan-tab.active-tab');
  if (active) activateTab(active);
});

// =============================
// QR SCAN ANIMATION
// =============================
document.querySelectorAll('[data-scan="qr"]').forEach(element => {
  element.addEventListener('click', () => {
    element.classList.add('scan-animation');
    setTimeout(() => element.classList.remove('scan-animation'), 2000);
  });
});

// =============================
// FAQ
// =============================
const faqItems = document.querySelectorAll('.faq-item');

faqItems.forEach(item => {
  const header = item.querySelector('.faq-header');
  const content = item.querySelector('.faq-content');
  const toggle = item.querySelector('.faq-toggle span');

  header.addEventListener('click', () => {
    const isExpanded = item.dataset.expanded === 'true';

    faqItems.forEach(other => {
      if (other !== item) {
        other.dataset.expanded = 'false';
        other.classList.remove('faq-expanded');
        other.querySelector('.faq-content').classList.remove('expanded');
        const ot = other.querySelector('.faq-toggle span');
        ot.textContent = '+';
        ot.className = 'text-2xl text-[#666] font-bold';
      }
    });

    if (isExpanded) {
      item.dataset.expanded = 'false';
      item.classList.remove('faq-expanded');
      content.classList.remove('expanded');
      toggle.textContent = '+';
      toggle.className = 'text-2xl text-[#666] font-bold';
    } else {
      item.dataset.expanded = 'true';
      item.classList.add('faq-expanded');
      content.classList.add('expanded');
      toggle.textContent = '×';
      toggle.className = 'text-2xl text-[#f4633a] font-bold';

      item.classList.add('zoom-bounce');
      setTimeout(() => item.classList.remove('zoom-bounce'), 500);
    }
  });
});

// =============================
// TESTIMONIALS
// =============================


  // Testimonials logic 

  const avatars = document.querySelectorAll('.avatar-selector');
  const cards = document.querySelectorAll('.testimonial-card');

  function activateCard(index) {
    cards.forEach((card, i) => {
      card.classList.remove('active-card');

      if (i === index) {
        card.classList.add('active-card');
        card.scrollIntoView({
          behavior: 'smooth',
          inline: 'center',
          block: 'nearest'
        });
      }
    });
  }

  avatars.forEach((avatar) => {
    avatar.addEventListener('click', () => {
      const index = Number(avatar.dataset.card);

      avatars.forEach(a => a.classList.remove('active'));
      avatar.classList.add('active');

      activateCard(index);
    });
  });

  // Initial state
  activateCard(0);
  avatars[0].classList.add('active');


// =============================
// SMOOTH SCROLL
// =============================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', (e) => {
    e.preventDefault();
    const target = document.getElementById(anchor.getAttribute('href').substring(1));
    if (target) target.scrollIntoView({ behavior: 'smooth' });
  });
});

// =============================
// SECTION FADE-IN ANIMATION
// =============================
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) entry.target.classList.add('animate-fade-in');
  });
}, { threshold: 0.1 });

document.querySelectorAll('section').forEach(sec => observer.observe(sec));

const style = document.createElement('style');
style.textContent = `
  .animate-fade-in { animation: fadeIn .6s ease-in-out; }
  @keyframes fadeIn { from{ opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }
`;
document.head.appendChild(style);

// =============================
// MOBILE BOTTOM NAV
// =============================
const mobileNav = document.getElementById('mobileBottomNav');

if (mobileNav) {
  const items = mobileNav.querySelectorAll('.mobile-nav-item');

  items.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.dataset.target;
      const target = document.getElementById(targetId);

      if (target) {
        const y = target.getBoundingClientRect().top + window.pageYOffset - 20;
        window.scrollTo({ top: y, behavior: 'smooth' });
      }

      items.forEach(i => i.classList.remove('text-[#f4633a]'));
      btn.classList.add('text-[#f4633a]');
    });
  });

  const homeBtn = mobileNav.querySelector('[data-target="home"]');
  if (homeBtn) {
    items.forEach(i => i.classList.remove('text-[#f4633a]'));
    homeBtn.classList.add('text-[#f4633a]');
  }
}

// =============================
// DROPDOWN LANGUAGE MENU
// =============================
const button = document.getElementById("dropdownButton");
const menu = document.getElementById("dropdownMenu");

if (button && menu) {
  button.addEventListener("click", () => menu.classList.toggle("hidden"));

  document.addEventListener("click", (e) => {
    if (!button.contains(e.target) && !menu.contains(e.target)) {
      menu.classList.add("hidden");
    }
  });
}


    // Plan Tab Functionality
   const planTabs = document.querySelectorAll('.plan-tab');
const tabIndicator = document.querySelector('.tab-indicator');

planTabs.forEach(tab => {
  tab.addEventListener('click', () => {
    
    // Remove active classes
    planTabs.forEach(t => t.classList.remove('active-tab', 'text-[#f4633a]', 'font-bold'));
    planTabs.forEach(t => t.classList.add('text-[#101010]', 'font-normal'));

    // Add active class to the clicked tab
    tab.classList.add('active-tab', 'text-[#f4633a]', 'font-bold');
    tab.classList.remove('text-[#101010]', 'font-normal');

    // Move underline indicator properly (account for scroll offset)
    const container = tab.parentElement; // modal-tabs
    const tabRect = tab.getBoundingClientRect();
    const containerRect = container.getBoundingClientRect();
    const scrollLeft = container.scrollLeft; // important part!

    const leftOffset = tabRect.left - containerRect.left + scrollLeft;

    if (tabIndicator) {
      tabIndicator.style.transform = `translateX(${leftOffset}px)-20px`;
      console.log("first", tabIndicator.style.transform);
      tabIndicator.style.width = `${tabRect.width}px - 20px`;
      console.log("secod", tabIndicator.style.width);
    }
  });
});

   
    // Smooth scrolling for anchor links (desktop + mobile)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);
        if (target) {
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });

document.addEventListener('DOMContentLoaded', () => {
  const planOptions = document.querySelectorAll('.plan-option');

  planOptions.forEach(option => {
    option.addEventListener('click', () => {
      planOptions.forEach(o => o.classList.remove('selected'));
      option.classList.add('selected');
    });
  });
});


function popupOpen() {
  document.getElementById('compatibility-modal').style.display = 'flex';
}

function popupClose() {
  document.getElementById('compatibility-modal').style.display = 'none';
}

// Optional: close modal when clicking outside the content
window.onclick = function(event) {
  const modal = document.getElementById('compatibility-modal');
  if (event.target === modal) {
    popupClose();
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const tabs = document.querySelectorAll('.modal-tab');
  const panes = document.querySelectorAll('.tab-pane');
  const underline = document.querySelector('.tab-underline');

function moveUnderline(activeTab) {
  const tabRect = activeTab.getBoundingClientRect();
  const container = activeTab.parentElement;
  const containerRect = container.getBoundingClientRect();

  // Add scroll offset for horizontally scrollable containers
  const scrollOffset = container.scrollLeft;

  underline.style.width = `${tabRect.width}px`;
  underline.style.left = `${tabRect.left - containerRect.left + scrollOffset}px`;

  console.log("width:", underline.style.width);
  console.log("left:", underline.style.left);
}

  // Initialize underline position on page load
  const activeTab = document.querySelector('.modal-tab.active');
  if (activeTab) moveUnderline(activeTab);

  // Add click listeners
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      panes.forEach(p => p.classList.remove('active'));

      tab.classList.add('active');
      document.getElementById(tab.dataset.tab).classList.add('active');
      moveUnderline(tab);
    });
  });

  // Recalculate underline on window resize
  window.addEventListener('resize', () => {
    const activeTab = document.querySelector('.modal-tab.active');
    if (activeTab) moveUnderline(activeTab);
  });
});
document.addEventListener('DOMContentLoaded', () => {
  const brandSelect = document.getElementById('brandSelect');
  const selectedBrand = document.getElementById('selectedBrand');
  const optionsList = brandSelect.querySelector('.brand-options');

  brandSelect.addEventListener('click', (e) => {
    e.stopPropagation();
    brandSelect.classList.toggle('open');
    optionsList.classList.toggle('hidden');
  });

  brandSelect.querySelectorAll('.brand-option').forEach(option => {
    option.addEventListener('click', () => {
      selectedBrand.textContent = option.textContent;
      brandSelect.classList.remove('open');
      optionsList.classList.add('hidden');
    });
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', () => {
    brandSelect.classList.remove('open');
    optionsList.classList.add('hidden');
  });
});
// checkout page tabs click
const _tabs = document.querySelectorAll(".payment-tab");
const _panes = document.querySelectorAll(".tab-pane");
const activeLine = document.querySelector(".active-line");

function moveUnderline(tab) {
  const tabRect = tab.getBoundingClientRect();
  const containerRect = tab.parentElement.getBoundingClientRect();
  activeLine.style.width = `${tabRect.width}px`;
  activeLine.style.left = `${tabRect.left - containerRect.left}px`;
}

// initialize underline under the first active tab
const defaultTab = document.querySelector(".payment-tab.active");
moveUnderline(defaultTab);

_tabs.forEach(tab => {
  tab.addEventListener("click", () => {
    // remove all active states
    _tabs.forEach(t => t.classList.remove("active"));
    _panes.forEach(p => p.classList.remove("active"));

    // activate the selected tab and pane
    tab.classList.add("active");
    document.getElementById(tab.dataset.tab).classList.add("active");

    // move underline under clicked tab
    moveUnderline(tab);
  });
});
// checkout radio button

