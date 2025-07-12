import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('resumeFile');
    const browseButton = document.getElementById('browseButton');
    const selectedFile = document.getElementById('selectedFile');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFile = document.getElementById('removeFile');
    const form = document.getElementById('resumeUploadForm');
    const submitButton = document.getElementById('submitButton');
    const buttonText = document.getElementById('buttonText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const errorMessages = document.getElementById('errorMessages');
    const errorList = document.getElementById('errorList');
    const successMessage = document.getElementById('successMessage');
    const placeholderText = document.getElementById('placeholderText');
    const extractedText = document.getElementById('extractedText');
    const textContent = document.getElementById('textContent');
    const copyTextButton = document.getElementById('copyTextButton');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const downloadPdfButton = document.getElementById('downloadPdfButton');
    
    // Handle browse button click
    browseButton.addEventListener('click', function() {
        fileInput.click();
    });
    
    // Handle file input change
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelection(file);
        }
    });
    
    // Handle drag and drop
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            fileInput.files = files; // Set the file input
            handleFileSelection(file);
        }
    });

    downloadPdfButton.addEventListener('click', function() {
        // Get the text from the textarea
        const coverLetterText = textContent.value || textContent.textContent;
        if (!coverLetterText || !coverLetterText.trim()) {
            showError(['No cover letter text to download.']);
            return;
        }

        // Optional: get a filename from user or use default
        const filename = 'cover_letter.pdf';

        // Create a form data object
        const formData = new FormData();
        formData.append('textContent', coverLetterText);
        formData.append('filename', filename);

        // Send POST request to backend to generate PDF
        fetch(window.generateDownloadPdfUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to generate PDF.');
            return response.blob();
        })
        .then(blob => {
            // Create a link to download the PDF
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => {
            showError([error.message || 'An error occurred while downloading the PDF.']);
        });
    });
    
    // Handle file selection
    function handleFileSelection(file) {
        // Validate file type
        if (file.type !== 'application/pdf') {
            showError(['Only PDF files are allowed.']);
            return;
        }
        
        // Validate file size (5MB = 5 * 1024 * 1024 bytes)
        if (file.size > 5 * 1024 * 1024) {
            showError(['File size must not exceed 5MB.']);
            return;
        }
        
        // Display selected file
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        selectedFile.classList.remove('hidden');
        submitButton.disabled = false;
        
        // Hide any previous errors
        hideError();
        hideSuccess();
    }
    
    // Handle remove file
    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        selectedFile.classList.add('hidden');
        submitButton.disabled = true;
        hideError();
        hideSuccess();
        hideExtractedText();
    });
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        // Show loading state
        setLoadingState(true);
        hideError();
        hideSuccess();
        
        // Send AJAX request to Laravel backend
        fetch(window.generateLetterUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess();
                showExtractedText(data.coverLetterText || 'No text extracted from the resume.');
            } else {
                if (data.errors) {
                    // Handle validation errors
                    const errorMessages = Object.values(data.errors).flat();
                    showError(errorMessages);
                } else {
                    // Handle general errors
                    showError([data.message || 'An error occurred.']);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError(['An error occurred while uploading your resume. Please try again.']);
        })
        .finally(() => {
            setLoadingState(false);
        });
    });
    
    // Handle copy text button
    copyTextButton.addEventListener('click', function() {
        navigator.clipboard.writeText(textContent.textContent).then(() => {
            const originalText = copyTextButton.textContent;
            copyTextButton.textContent = 'Copied!';
            setTimeout(() => {
                copyTextButton.textContent = originalText;
            }, 2000);
        });
    });
    
    // Utility functions
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function setLoadingState(loading) {
        if (loading) {
            buttonText.textContent = 'Uploading...';
            loadingSpinner.classList.remove('hidden');
            submitButton.disabled = true;
        } else {
            buttonText.textContent = 'Upload Resume';
            loadingSpinner.classList.add('hidden');
            submitButton.disabled = false;
        }
    }
    
    function showError(messages) {
        errorList.innerHTML = '';
        messages.forEach(message => {
            const li = document.createElement('li');
            li.textContent = message;
            errorList.appendChild(li);
        });
        errorMessages.classList.remove('hidden');
    }
    
    function hideError() {
        errorMessages.classList.add('hidden');
    }
    
    function showSuccess() {
        successMessage.classList.remove('hidden');
    }
    
    function hideSuccess() {
        successMessage.classList.add('hidden');
    }
    
    function showExtractedText(text) {
        textContent.textContent = text;
        placeholderText.classList.add('hidden');
        extractedText.classList.remove('hidden');
    }
    
    function hideExtractedText() {
        placeholderText.classList.remove('hidden');
        extractedText.classList.add('hidden');
    }
});
