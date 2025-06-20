document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const zipDropZone = document.getElementById('zipDropZone');
    const zipFileInput = document.getElementById('zipFileInput');
    const hiddenZipInput = document.getElementById('hiddenZipInput');
    const zipUploadArea = document.getElementById('zipUploadArea');
    const zipFileInfo = document.getElementById('zipFileInfo');
    const zipFileName = document.getElementById('zipFileName');
    const zipFileSize = document.getElementById('zipFileSize');

    const albumArtDropZone = document.getElementById('albumArtDropZone');
    const albumArtInput = document.getElementById('albumArtInput');
    const albumArtUploadArea = document.getElementById('albumArtUploadArea');
    const albumArtInfo = document.getElementById('albumArtInfo');
    const albumArtFileName = document.getElementById('albumArtFileName');

    const trackForm = document.getElementById('trackForm');
    const loadingOverlay = document.getElementById('loadingOverlay');

    let currentZipFile = null;

    // ZIP file drag and drop handlers
    zipDropZone.addEventListener('dragover', handleZipDragOver);
    zipDropZone.addEventListener('dragleave', handleZipDragLeave);
    zipDropZone.addEventListener('drop', handleZipDrop);
    zipDropZone.addEventListener('click', () => zipFileInput.click());
    zipFileInput.addEventListener('change', handleZipFileSelect);

    // Album art drag and drop handlers
    albumArtDropZone.addEventListener('dragover', handleAlbumArtDragOver);
    albumArtDropZone.addEventListener('dragleave', handleAlbumArtDragLeave);
    albumArtDropZone.addEventListener('drop', handleAlbumArtDrop);
    albumArtInput.addEventListener('change', handleAlbumArtSelect);

    // Form submission handler
    trackForm.addEventListener('submit', handleFormSubmit);

    // ZIP file functions
    function handleZipDragOver(e) {
        e.preventDefault();
        zipDropZone.classList.add('dragover');
    }

    function handleZipDragLeave(e) {
        e.preventDefault();
        zipDropZone.classList.remove('dragover');
    }

    function handleZipDrop(e) {
        e.preventDefault();
        zipDropZone.classList.remove('dragover');
        
        const files = Array.from(e.dataTransfer.files);
        const zipFile = files.find(file => file.name.toLowerCase().endsWith('.zip'));
        
        if (zipFile) {
            handleZipFile(zipFile);
        } else {
            showNotification('Please select a valid ZIP file', 'error');
        }
    }

    function handleZipFileSelect(e) {
        const file = e.target.files[0];
        if (file && file.name.toLowerCase().endsWith('.zip')) {
            handleZipFile(file);
        } else {
            showNotification('Please select a valid ZIP file', 'error');
        }
    }

    function handleZipFile(file) {
        // Validate file size (100MB limit)
        const maxSize = 100 * 1024 * 1024;
        if (file.size > maxSize) {
            showNotification('ZIP file is too large. Maximum size is 100MB', 'error');
            return;
        }

        currentZipFile = file;
        
        // Update UI
        zipFileName.textContent = file.name;
        zipFileSize.textContent = `ZIP file selected (${formatFileSize(file.size)})`;
        
        zipUploadArea.classList.add('hidden');
        zipFileInfo.classList.remove('hidden');
        zipDropZone.classList.add('success');

        // Transfer file to hidden input for form submission
        const dt = new DataTransfer();
        dt.items.add(file);
        hiddenZipInput.files = dt.files;
    }

    function removeZipFile() {
        currentZipFile = null;
        zipFileInput.value = '';
        hiddenZipInput.value = '';
        
        zipUploadArea.classList.remove('hidden');
        zipFileInfo.classList.add('hidden');
        zipDropZone.classList.remove('success');
    }

    // Album art functions
    function handleAlbumArtDragOver(e) {
        e.preventDefault();
        albumArtDropZone.classList.add('dragover');
    }

    function handleAlbumArtDragLeave(e) {
        e.preventDefault();
        albumArtDropZone.classList.remove('dragover');
    }

    function handleAlbumArtDrop(e) {
        e.preventDefault();
        albumArtDropZone.classList.remove('dragover');
        
        const files = Array.from(e.dataTransfer.files);
        const imageFile = files.find(file => 
            file.type === 'image/jpeg' || 
            file.type === 'image/jpg' || 
            file.type === 'image/png'
        );
        
        if (imageFile) {
            handleAlbumArtFile(imageFile);
        } else {
            showNotification('Please select a valid image file (JPEG or PNG)', 'error');
        }
    }

    function handleAlbumArtSelect(e) {
        const file = e.target.files[0];
        if (file && (file.type === 'image/jpeg' || file.type === 'image/jpg' || file.type === 'image/png')) {
            handleAlbumArtFile(file);
        } else {
            showNotification('Please select a valid image file (JPEG or PNG)', 'error');
        }
    }

    function handleAlbumArtFile(file) {
        // Validate file size (10MB limit)
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            showNotification('Image file is too large. Maximum size is 10MB', 'error');
            return;
        }

        // Validate image dimensions
        const img = new Image();
        img.onload = function() {
            if (this.width < 512 || this.height < 512) {
                showNotification('Image must be at least 512x512 pixels', 'error');
                return;
            }

            // Update UI
            albumArtFileName.textContent = file.name;
            albumArtUploadArea.classList.add('hidden');
            albumArtInfo.classList.remove('hidden');
            albumArtDropZone.classList.add('success');
        };
        
        img.onerror = function() {
            showNotification('Invalid image file', 'error');
        };
        
        img.src = URL.createObjectURL(file);
    }

    function removeAlbumArt() {
        albumArtInput.value = '';
        albumArtUploadArea.classList.remove('hidden');
        albumArtInfo.classList.add('hidden');
        albumArtDropZone.classList.remove('success');
    }

    // Form submission
    function handleFormSubmit(e) {
        e.preventDefault();
        
        // Validate ZIP file
        if (!currentZipFile) {
            showNotification('Please upload a ZIP file', 'error');
            return;
        }

        // Show loading overlay
        loadingOverlay.classList.remove('hidden');

        // Submit form
        const formData = new FormData(trackForm);
        
        fetch('../action/trackAction.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingOverlay.classList.add('hidden');
            
            if (data.success) {
                showNotification('Track information submitted successfully!', 'success');
                // Redirect to success page
                setTimeout(() => {
                    window.location.href = "../view/homepage.html";
                }, 1500);
            } else {
                if (data.errors && data.errors.length > 0) {
                    showNotification(data.errors.join('<br>'), 'error');
                } else {
                    showNotification(data.message || 'An error occurred', 'error');
                }
            }
        })
        .catch(error => {
            loadingOverlay.classList.add('hidden');
            console.error('Error:', error);
            showNotification('An error occurred while submitting the form', 'error');
        });
    }

    // Utility functions
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = message;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 20px;
            border-radius: 6px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.3s ease-out;
        `;
        
        // Set background color based on type
        switch (type) {
            case 'success':
                notification.style.backgroundColor = '#10b981';
                break;
            case 'error':
                notification.style.backgroundColor = '#ef4444';
                break;
            default:
                notification.style.backgroundColor = '#3b82f6';
        }
        
        // Add animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Add to page
        document.body.appendChild(notification);
        
        // Remove after 5 seconds
        setTimeout(() => {
            notification.style.animation = 'slideIn 0.3s ease-out reverse';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }

    // Make functions globally available
    window.removeZipFile = removeZipFile;
    window.removeAlbumArt = removeAlbumArt;
});