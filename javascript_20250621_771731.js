document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality for novel profile page
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    if (tabBtns.length > 0) {
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all buttons and panes
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));
                
                // Add active class to clicked button and corresponding pane
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
    }
    
    // Star rating functionality
    const stars = document.querySelectorAll('.stars i');
    if (stars.length > 0) {
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                
                // Highlight stars up to the clicked one
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });
    }
    
    // Chapter select functionality
    const chapterSelect = document.querySelector('.chapter-select');
    if (chapterSelect) {
        chapterSelect.addEventListener('change', function() {
            if (this.value !== 'Select Chapter') {
                window.location.href = 'content.html?chapter=' + this.value;
            }
        });
    }
    
    // Font settings functionality
    const fontSettingsBtn = document.querySelector('.btn-font-settings');
    if (fontSettingsBtn) {
        fontSettingsBtn.addEventListener('click', function() {
            // Implement font settings modal
            alert('Font settings will be implemented here');
        });
    }
    
    // Load advertisements from admin (simulated)
    loadAdvertisements();
    
    // Check for admin connection
    checkAdminConnection();
});

function loadAdvertisements() {
    // In a real implementation, this would fetch ads from the admin interface
    const ads = document.querySelectorAll('.advertisement .ad-container');
    if (ads.length > 0) {
        // Simulate loading different ads
        const adImages = [
            'images/ad1.jpg',
            'images/ad2.jpg',
            'images/ad3.jpg'
        ];
        
        ads.forEach((ad, index) => {
            // In a real implementation, this would be fetched from the admin interface
            const adIndex = index % adImages.length;
            ad.innerHTML = `<img src="${adImages[adIndex]}" alt="Advertisement">`;
        });
    }
}

function checkAdminConnection() {
    // This is a placeholder for the admin connection check
    // In a real implementation, this would verify connection to the admin interface
    const adminConnection = document.getElementById('admin-connection');
    if (adminConnection) {
        const adminUrl = adminConnection.getAttribute('data-admin-url');
        console.log('Reader interface connected to admin at:', adminUrl);
        
        // This connection would be used to:
        // 1. Receive updates about new novels/changes
        // 2. Receive advertisement updates
        // 3. Update website settings (name, logo, etc.)
    }
}