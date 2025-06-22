document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
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
    
    // Show/hide scheduled date based on status
    const chapterStatus = document.getElementById('chapterStatus');
    if (chapterStatus) {
        chapterStatus.addEventListener('change', function() {
            const scheduledDate = document.querySelector('.scheduled-date');
            if (this.value === 'scheduled') {
                scheduledDate.style.display = 'block';
            } else {
                scheduledDate.style.display = 'none';
            }
        });
    }
    
    // Search functionality
    const authorSearch = document.getElementById('authorSearch');
    const searchBtn = document.getElementById('searchBtn');
    
    if (authorSearch && searchBtn) {
        searchBtn.addEventListener('click', function() {
            const searchTerm = authorSearch.value.toLowerCase();
            const authorCards = document.querySelectorAll('.author-card');
            
            authorCards.forEach(card => {
                const authorId = card.getAttribute('data-author-id');
                const authorName = card.querySelector('h3').textContent.toLowerCase();
                
                if (authorId.includes(searchTerm) || authorName.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    
    // Delete author confirmation
    const deleteAuthorBtn = document.getElementById('deleteAuthorBtn');
    if (deleteAuthorBtn) {
        deleteAuthorBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this author and all their books?')) {
                // In a real implementation, this would make an API call to delete the author
                alert('Author deletion would be processed here');
            }
        });
    }
    
    // Delete chapter confirmation
    const deleteChapterBtns = document.querySelectorAll('.delete-chapter');
    deleteChapterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const chapterId = this.getAttribute('data-chapter-id');
            if (confirm('Are you sure you want to delete this chapter?')) {
                // In a real implementation, this would make an API call to delete the chapter
                alert('Chapter deletion would be processed here for chapter ID: ' + chapterId);
            }
        });
    });
    
    // Form submissions
    const newChapterForm = document.getElementById('newChapterForm');
    if (newChapterForm) {
        newChapterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Process form submission
            alert('New chapter would be saved here');
        });
    }
    
    const editChapterForm = document.getElementById('editChapterForm');
    if (editChapterForm) {
        editChapterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Process form submission
            alert('Chapter edits would be saved here');
        });
    }
    
    const bookSettingsForm = document.querySelector('.book-settings-form');
    if (bookSettingsForm) {
        bookSettingsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Process form submission
            alert('Book settings would be saved here');
        });
    }
    
    // Initialize chart if on statistics page
    if (document.getElementById('viewsChart')) {
        // This would be replaced with actual chart initialization using a library like Chart.js
        console.log('Chart would be initialized here with real data');
    }
});