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

// ============================
// ADMIN SIDE MENUS CLICKS
// ============================
//const menuLinks = document.querySelectorAll("#sidebarMenu a");
const menuLinks = [];
const sections = document.querySelectorAll(".content-box > *");

if (menuLinks.length > 0) {
  menuLinks.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();

      // Remove active state and reset icons
      menuLinks.forEach(l => {
        l.classList.remove("active");
        const img = l.querySelector("img");
        if (img) {
          img.src = l.getAttribute("data-icon-black");
        }
      });

      // Activate the clicked item
      link.classList.add("active");
      const activeImg = link.querySelector("img");
      if (activeImg) {
        activeImg.src = link.getAttribute("data-icon-white");
      }

      // Hide all content sections
      sections.forEach(section => section.classList.add("hidden"));

      // Show the selected section
      const targetId = link.getAttribute("data-target");
      const targetSection = document.getElementById(targetId);
      if (targetSection) targetSection.classList.remove("hidden");
    });
  });
}

// ============================
// SHOW/HIDE PASSWORD LOGIC
// ============================
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

// ============================
// SEARCH MODAL FUNCTIONALITY
// ============================
const searchInput = document.getElementById('searchInput');
const modalOverlay = document.querySelector('.search-modal-overlay');
const closeModal = document.getElementById('closeModal');
const searchList = document.getElementById('searchList');

// Search modal show/hide
if (searchInput && modalOverlay) {
  searchInput.addEventListener('focus', () => {
    modalOverlay.classList.remove('hidden');
  });
}

if (closeModal && modalOverlay) {
  closeModal.addEventListener('click', () => {
    modalOverlay.classList.add('hidden');
  });
}

// Search list show/hide
if (searchInput && searchList) {
  searchInput.addEventListener('focus', () => {
    searchList.classList.remove('hidden');
  });

  // Hide list when clicking outside
  document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !searchList.contains(e.target)) {
      searchList.classList.add('hidden');
    }
  });
}

// ============================
// COMPATIBILITY MODAL
// ============================
const compatibilityModal = document.getElementById("compatibility-modal");

if (compatibilityModal) {
  compatibilityModal.addEventListener("click", (e) => {
    if (e.target === compatibilityModal) {
      compatibilityModal.classList.add("hidden");
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') compatibilityModal.classList.add('hidden');
  });
}

// ============================
// NAV ITEMS ACTIVE STATE
// ============================
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

// ============================
// MOBILE MENU TOGGLE
// ============================
const menuButton = document.querySelector('[aria-label="Open menu"]');
let menuOpen = false;

if (menuButton) {
  menuButton.addEventListener('click', () => {
    menuOpen = !menuOpen;
    console.log('Menu toggled:', menuOpen);
  });
}

// ============================
// PLAN TABS
// ============================
const planTabs = document.querySelectorAll('.plan-tab');
const planPanes = document.querySelectorAll('.tab-pane');
const tabIndicator = document.getElementById('tab-indicator');

function activatePlanTab(tab) {
  planTabs.forEach(t => t.classList.remove('active-tab', 'font-bold', 'text-[#f4633a]'));
  tab.classList.add('active-tab', 'font-bold', 'text-[#f4633a]');

  const rect = tab.getBoundingClientRect();
  const parentRect = tab.parentElement.getBoundingClientRect();

  if (tabIndicator) {
    tabIndicator.style.width = rect.width + 'px';
    tabIndicator.style.left = (rect.left - parentRect.left) + 'px';
  }

  const target = tab.dataset.tab;
  planPanes.forEach(p => {
    p.classList.toggle('hidden', p.dataset.content !== target);
  });
}

if (planTabs.length > 0) {
  planTabs.forEach(tab => {
    tab.addEventListener('click', () => activatePlanTab(tab));
  });

  window.addEventListener('load', () => {
    const active = document.querySelector('.plan-tab.active-tab');
    if (active) activatePlanTab(active);
  });

  window.addEventListener('resize', () => {
    const active = document.querySelector('.plan-tab.active-tab');
    if (active) activatePlanTab(active);
  });
}

// ============================
// QR SCAN ANIMATION
// ============================
document.querySelectorAll('[data-scan="qr"]').forEach(element => {
  element.addEventListener('click', () => {
    element.classList.add('scan-animation');
    setTimeout(() => element.classList.remove('scan-animation'), 2000);
  });
});

// ============================
// FAQ FUNCTIONALITY
// ============================
const faqItems = document.querySelectorAll('.faq-item');

if (faqItems.length > 0) {
  faqItems.forEach(item => {
    const header = item.querySelector('.faq-header');
    const content = item.querySelector('.faq-content');
    const toggle = item.querySelector('.faq-toggle span');

    if (header && content && toggle) {
      header.addEventListener('click', () => {
        const isExpanded = item.dataset.expanded === 'true';

        faqItems.forEach(other => {
          if (other !== item) {
            other.dataset.expanded = 'false';
            other.classList.remove('faq-expanded');
            const otherContent = other.querySelector('.faq-content');
            const otherToggle = other.querySelector('.faq-toggle span');
            if (otherContent) otherContent.classList.remove('expanded');
            if (otherToggle) {
              otherToggle.textContent = '+';
              otherToggle.className = 'text-2xl text-[#666] font-bold';
            }
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
    }
  });
}

// ============================
// TESTIMONIALS FUNCTIONALITY
// ============================
const avatars = document.querySelectorAll('.avatar-selector');
const testimonialsContainer = document.getElementById('testimonialsContainer');

const testimonialSets = [
  [
    { name: "Robert", role: "HR Manager", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/img_670e85fe14e5634.png" },
    { name: "Alice", role: "CEO, etc.venues", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/img_670e85fe14e5634_48x48.png" },
    { name: "Alan", role: "Team Lead", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/Helena Turpin.png" }
  ],
  [
    { name: "John", role: "Marketing Head", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/img_tab_raghav_pavaman.png" },
    { name: "Priya", role: "Project Manager", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/img_kain_kyel_seo.png" },
    { name: "Lee", role: "Tech Lead", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/Anurag Singh.png" }
  ],
  [
    { name: "David", role: "Traveler", text: "Bloomr always asked good questions for our projects and helped them at a high level, top work!.", img: "../assets/images/img_tab_raghav_pavaman.png" },
    { name: "Nina", role: "Photographer", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/Helena Turpin.png" },
    { name: "Ravi", role: "Freelancer", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/img_kain_kyel_seo.png" }
  ],
  [
    { name: "Mia", role: "HR Specialist", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/img_kain_kyel_seo.png" },
    { name: "Tom", role: "Consultant", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/Helena Turpin.png" },
    { name: "Sophia", role: "Manager", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/img_tab_raghav_pavaman.png" }
  ],
  [
    { name: "Arun", role: "Engineer", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/img_tab_raghav_pavaman.png" },
    { name: "Emma", role: "Designer", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/Helena Turpin.png" },
    { name: "Leo", role: "Product Lead", text: "Bloomr always asked good questions for our projects and helped us execute them at a high level, top work!.", img: "../assets/images/img_kain_kyel_seo.png" }
  ]
];

function renderTestimonials(index) {
  if (!testimonialsContainer) return;
  
  testimonialsContainer.innerHTML = '';
  const currentSet = testimonialSets[index] || testimonialSets[0];
  
  currentSet.forEach(t => {
    const testimonialDiv = document.createElement('div');
    testimonialDiv.className = 'snap-center shrink-0 w-[80%] sm:w-auto mx-2 bg-[#f4f4f6] rounded-[12px] p-6 flex flex-col justify-between text-left';
    testimonialDiv.innerHTML = `
      <p class="text-[14px] sm:text-[16px] font-normal text-[#020203] leading-[24px] mb-4">"${t.text}"</p>
      <div class="flex items-center gap-3">
        <img src="${t.img}" alt="${t.name}" class="w-[36px] h-[36px] rounded-full">
        <div>
          <h4 class="text-[12px] font-bold text-[#020203] leading-[17px]">${t.name}</h4>
          <p class="text-[12px] text-[#020203] leading-[17px]">${t.role}</p>
        </div>
      </div>
    `;
    testimonialsContainer.appendChild(testimonialDiv);
  });
}

if (avatars.length > 0 && testimonialsContainer) {
  renderTestimonials(0);

  avatars.forEach((avatar, index) => {
    avatar.addEventListener('click', () => {
      avatars.forEach(a => a.classList.remove('active'));
      avatar.classList.add('active');
      renderTestimonials(index);
    });
  });
}

// ============================
// SMOOTH SCROLL FUNCTIONALITY
// ============================
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

// ============================
// SECTION FADE-IN ANIMATION
// ============================
const fadeInObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) entry.target.classList.add('animate-fade-in');
  });
}, { threshold: 0.1 });

document.querySelectorAll('section').forEach(sec => fadeInObserver.observe(sec));

// Create styles only once
const fadeInStyle = document.createElement('style');
fadeInStyle.textContent = `
  .animate-fade-in { animation: fadeIn .6s ease-in-out; }
  @keyframes fadeIn { from{ opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);} }
`;
document.head.appendChild(fadeInStyle);

// ============================
// MOBILE BOTTOM NAV
// ============================
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

// ============================
// DROPDOWN LANGUAGE MENU
// ============================
const dropdownButton = document.getElementById("dropdownButton");
const dropdownMenu = document.getElementById("dropdownMenu");

if (dropdownButton && dropdownMenu) {
  dropdownButton.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle("hidden");
  });

  document.addEventListener("click", (e) => {
    if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
      dropdownMenu.classList.add("hidden");
    }
  });
}

// ============================
// PLAN OPTIONS SELECTION
// ============================
document.addEventListener('DOMContentLoaded', () => {
  const planOptions = document.querySelectorAll('.plan-option');

  planOptions.forEach(option => {
    option.addEventListener('click', () => {
      planOptions.forEach(o => o.classList.remove('selected'));
      option.classList.add('selected');
    });
  });
});

// ============================
// KEY FEATURES TAB
// ============================
const featureTabs = document.querySelectorAll('.tab-link');
const featurePanes = document.querySelectorAll('.tab-pane');
const featureIndicator = document.querySelector('.tab-indicator');

function activateFeatureTab(tab) {
  featureTabs.forEach(t => t.classList.remove('active'));
  tab.classList.add('active');

  const target = tab.dataset.tab;
  featurePanes.forEach(p => {
    p.classList.toggle('active', p.dataset.content === target);
  });

  // Move indicator smoothly
  if (featureIndicator) {
    const rect = tab.getBoundingClientRect();
    const parentRect = tab.parentElement.getBoundingClientRect();
    featureIndicator.style.width = rect.width + 'px';
    featureIndicator.style.left = (rect.left - parentRect.left) + 'px';
  }
}

if (featureTabs.length > 0) {
  featureTabs.forEach(tab => {
    tab.addEventListener('click', e => {
      e.preventDefault();
      activateFeatureTab(tab);
    });
  });

  // Set correct indicator position on load
  window.addEventListener('load', () => {
    const active = document.querySelector('.tab-link.active');
    if (active) activateFeatureTab(active);
  });

  // Update position on resize
  window.addEventListener('resize', () => {
    const active = document.querySelector('.tab-link.active');
    if (active) activateFeatureTab(active);
  });
}

// ============================
// COMPATIBILITY MODAL FUNCTIONS
// ============================
function popupOpen() {
  const modal = document.getElementById('compatibility-modal');
  if (modal) modal.style.display = 'flex';
}

function popupClose() {
  const modal = document.getElementById('compatibility-modal');
  if (modal) modal.style.display = 'none';
}

// Close modal when clicking outside
if (compatibilityModal) {
  compatibilityModal.addEventListener('click', (e) => {
    if (e.target === compatibilityModal) {
      popupClose();
    }
  });
}

// ============================
// MODAL TABS WITH UNDERLINE
// ============================
document.addEventListener('DOMContentLoaded', () => {
  const modalTabs = document.querySelectorAll('.modal-tab');
  const modalPanes = document.querySelectorAll('.modal-tab-pane');
  const modalUnderline = document.querySelector('.tab-underline');

  function moveModalUnderline(activeTab) {
    if (!modalUnderline) return;
    
    const tabRect = activeTab.getBoundingClientRect();
    const container = activeTab.parentElement;
    const containerRect = container.getBoundingClientRect();
    const scrollOffset = container.scrollLeft;

    modalUnderline.style.width = `${tabRect.width}px`;
    modalUnderline.style.left = `${tabRect.left - containerRect.left + scrollOffset}px`;
  }

  // Initialize underline position on page load
  const activeModalTab = document.querySelector('.modal-tab.active');
  if (activeModalTab) moveModalUnderline(activeModalTab);

  // Add click listeners
  if (modalTabs.length > 0) {
    modalTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        modalTabs.forEach(t => t.classList.remove('active'));
        modalPanes.forEach(p => p.classList.remove('active'));

        tab.classList.add('active');
        const targetPane = document.getElementById(tab.dataset.tab);
        if (targetPane) targetPane.classList.add('active');
        
        moveModalUnderline(tab);
      });
    });

    // Recalculate underline on window resize
    window.addEventListener('resize', () => {
      const activeTab = document.querySelector('.modal-tab.active');
      if (activeTab) moveModalUnderline(activeTab);
    });
  }
});

// ============================
// BRAND SELECT DROPDOWN
// ============================
document.addEventListener('DOMContentLoaded', () => {
  const brandSelect = document.getElementById('brandSelect');
  const selectedBrand = document.getElementById('selectedBrand');
  const optionsList = brandSelect?.querySelector('.brand-options');

  if (brandSelect && selectedBrand && optionsList) {
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
  }
});

// ============================
// CHECKOUT PAGE PAYMENT TABS
// ============================
const paymentTabs = document.querySelectorAll(".payment-tab");
const paymentPanes = document.querySelectorAll(".payment-tab-pane");
const activeLine = document.querySelector(".active-line");

function movePaymentUnderline(tab) {
  if (!activeLine) return;
  
  const tabRect = tab.getBoundingClientRect();
  const containerRect = tab.parentElement.getBoundingClientRect();
  activeLine.style.width = `${tabRect.width}px`;
  activeLine.style.left = `${tabRect.left - containerRect.left}px`;
}

// Initialize payment tabs if they exist
if (paymentTabs.length > 0) {
  // Initialize underline under the first active tab
  const defaultPaymentTab = document.querySelector(".payment-tab.active");
  if (defaultPaymentTab) movePaymentUnderline(defaultPaymentTab);

  paymentTabs.forEach(tab => {
    tab.addEventListener("click", () => {
      // Remove all active states
      paymentTabs.forEach(t => t.classList.remove("active"));
      paymentPanes.forEach(p => p.classList.remove("active"));

      // Activate the selected tab and pane
      tab.classList.add("active");
      const targetPane = document.getElementById(tab.dataset.tab);
      if (targetPane) targetPane.classList.add("active");

      // Move underline under clicked tab
      movePaymentUnderline(tab);
    });
  });
}