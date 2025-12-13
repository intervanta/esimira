// Device Compatibility Modal Module
class DeviceCompatibilityModal {
    constructor() {
        this.currentDeviceType = '';
        this.searchQuery = '';
        this.modal = null;
        this.isLoaded = false;
        
        this.init();
    }

    init() {
        this.createModalContainer();
        this.bindGlobalEvents();
        this.injectStyles();
    }

    createModalContainer() {
        if (!document.getElementById('compatibility-modal')) {
            const modalHTML = `
                <div class="modal-overlay" id="compatibility-modal">
                    <div class="modal-content" id="modal-content">
                        <div class="modal-loading">
                            <div class="loading-spinner"></div>
                            <p>Loading device compatibility data...</p>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }
        
        this.modal = document.getElementById('compatibility-modal');
    }

    bindGlobalEvents() {
        window.deviceCompatibilityOpen = () => this.open();
        window.deviceCompatibilityClose = () => this.close();
        
        document.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.style.display === 'flex') {
                this.close();
            }
        });
    }

    async open() {
        this.modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        if (!this.isLoaded) {
            await this.loadModalContent();
            this.isLoaded = true;
        }
        
        this.initModalFunctionality();
    }

    close() {
        if (this.modal) {
            this.modal.style.display = 'none';
            document.body.style.overflow = '';
            this.resetFilters();
            this.closeAllExceptionPanels();
        }
    }

    async loadModalContent() {
        try {
            const response = await fetch('/device-compatibility/modal-content');
            if (!response.ok) throw new Error('Network response was not ok');
            
            const html = await response.text();
            document.getElementById('modal-content').innerHTML = html;
            
        } catch (error) {
            console.error('Failed to load modal content:', error);
            this.showError();
        }
    }

    showError() {
        document.getElementById('modal-content').innerHTML = `
            <h2 class="modal-title">Devices compatible with eSIM</h2>
            <button class="modal-close" onclick="deviceCompatibilityClose()">×</button>
            <div class="modal-error">
                <p>Failed to load device compatibility data. Please try again.</p>
            </div>
        `;
    }

    initModalFunctionality() {
        this.bindModalTabs();
        this.bindSearch();
        this.bindExceptionPanels();
        this.updateTabIndicator();
    }

    bindModalTabs() {
        const modalTabs = this.modal.querySelectorAll('.modal-tab');
        const modalPanes = this.modal.querySelectorAll('.modal-tab-content .tab-pane');

        modalTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabName = tab.getAttribute('data-tab');
                
                modalTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                modalPanes.forEach(pane => pane.classList.remove('active'));
                const activePane = document.getElementById(tabName);
                if (activePane) {
                    activePane.classList.add('active');
                }
                
                this.currentDeviceType = tabName;
                this.updateTabIndicator();
                this.filterDevices();
                this.closeAllExceptionPanels();
            });
        });

        const activeTab = this.modal.querySelector('.modal-tab.active');
        if (activeTab) {
            this.currentDeviceType = activeTab.getAttribute('data-tab');
        }
    }

    bindSearch() {
        const searchInput = document.getElementById('device-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.searchQuery = e.target.value.toLowerCase();
                this.filterDevices();
            });
        }
    }

    bindExceptionPanels() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('.exception-toggle')) {
                const toggle = e.target.closest('.exception-toggle');
                const exceptionPanel = toggle.closest('.model-item').querySelector('.exception-panel');
                const isOpen = exceptionPanel.classList.contains('active');
                
                this.closeAllExceptionPanels();
                
                if (!isOpen) {
                    exceptionPanel.classList.add('active');
                    toggle.classList.add('active');
                }
            }
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.exception-panel') && !e.target.closest('.exception-toggle')) {
                this.closeAllExceptionPanels();
            }
        });
    }

    closeAllExceptionPanels() {
        document.querySelectorAll('.exception-panel').forEach(panel => {
            panel.classList.remove('active');
        });
        document.querySelectorAll('.exception-toggle').forEach(toggle => {
            toggle.classList.remove('active');
        });
    }

    updateTabIndicator() {
        const activeTab = this.modal.querySelector('.modal-tab.active');
        const tabUnderline = this.modal.querySelector('.tab-underline');
        
        if (!activeTab || !tabUnderline) return;

        const tabRect = activeTab.getBoundingClientRect();
        const containerRect = activeTab.parentElement.getBoundingClientRect();
        const leftPosition = tabRect.left - containerRect.left;
        
        tabUnderline.style.width = `${tabRect.width}px`;
        tabUnderline.style.left = `${leftPosition}px`;
    }

    filterDevices() {
        const currentPane = document.getElementById(this.currentDeviceType);
        if (!currentPane) return;

        const brandSections = currentPane.querySelectorAll('.brand-section');
        const modelItems = currentPane.querySelectorAll('.model-item');
        let hasVisibleDevices = false;

        modelItems.forEach(item => {
            const modelName = item.getAttribute('data-model');
            const brandName = item.closest('.brand-section').getAttribute('data-brand');
            const matchesSearch = this.searchQuery === '' || 
                                modelName.includes(this.searchQuery) ||
                                brandName.includes(this.searchQuery);
            
            if (matchesSearch) {
                item.style.display = 'flex';
                hasVisibleDevices = true;
            } else {
                item.style.display = 'none';
            }
        });

        brandSections.forEach(section => {
            const visibleModels = section.querySelectorAll('.model-item[style=""]').length > 0 || 
                                section.querySelectorAll('.model-item[style*="display: flex"]').length > 0;
            section.style.display = visibleModels ? 'block' : 'none';
        });

        this.toggleNoDevicesMessage(currentPane, hasVisibleDevices);
    }

    toggleNoDevicesMessage(pane, hasVisibleDevices) {
        let noDevicesElement = pane.querySelector('.no-devices');
        
        if (!noDevicesElement) {
            noDevicesElement = document.createElement('p');
            noDevicesElement.className = 'no-devices';
            noDevicesElement.textContent = 'No devices found matching your criteria.';
            pane.querySelector('.device-list').appendChild(noDevicesElement);
        }

        noDevicesElement.style.display = hasVisibleDevices ? 'none' : 'block';
    }

    resetFilters() {
        this.searchQuery = '';
        
        const searchInput = document.getElementById('device-search');
        if (searchInput) searchInput.value = '';
        
        if (this.isLoaded) {
            this.filterDevices();
        }
    }

    injectStyles() {
        if (document.getElementById('device-compatibility-styles')) return;

        const styles = `
            <style id="device-compatibility-styles">
                /* All the modal styles from previous implementation */
                .modal-overlay {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    justify-content: center;
                    align-items: center;
                    z-index: 1000;
                    padding: 20px;
                    box-sizing: border-box;
                }

                .modal-content {
                    background: white;
                    border-radius: 12px;
                    padding: 24px;
                    width: 600px;
                    max-width: 90vw;
                    height: auto;
                    max-height: 80vh;
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                }

                /* ... include all other modal styles ... */
            </style>
        `;
        
        document.head.insertAdjacentHTML('beforeend', styles);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.deviceCompatibilityModal = new DeviceCompatibilityModal();
});

