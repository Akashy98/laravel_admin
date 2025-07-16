/**
 * CKEditor Configuration
 * Common configuration for CKEditor 5 across the application
 */

// CKEditor Configuration Object
const CKEditorConfig = {
    // Toolbar configuration
    toolbar: {
        items: [
            'heading',
            '|',
            'bold',
            'italic',
            'underline',
            'strikethrough',
            '|',
            'link',
            '|',
            'bulletedList',
            'numberedList',
            '|',
            'indent',
            'outdent',
            '|',
            'blockQuote',
            'insertTable',
            '|',
            'undo',
            'redo'
        ]
    },

    // Heading options
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
            { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
            { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
            { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
        ]
    },

    // Table configuration
    table: {
        contentToolbar: [
            'tableColumn',
            'tableRow',
            'mergeTableCells'
        ]
    },

    // Placeholder text
    placeholder: 'Start typing your content here...',

    // Language (optional - for internationalization)
    language: 'en'
};

/**
 * Initialize CKEditor on a specific element
 * @param {string} selector - CSS selector for the textarea
 * @param {Object} customConfig - Custom configuration to merge with default
 * @returns {Promise} - Promise that resolves with the editor instance
 */
function initializeCKEditor(selector, customConfig = {}) {
    // Merge custom config with default config
    const config = { ...CKEditorConfig, ...customConfig };

    return ClassicEditor
        .create(document.querySelector(selector), config)
        .then(editor => {
            console.log('CKEditor initialized successfully on:', selector);
            return editor;
        })
        .catch(error => {
            console.error('Error initializing CKEditor on', selector, ':', error);
            throw error;
        });
}

/**
 * Initialize CKEditor with custom placeholder
 * @param {string} selector - CSS selector for the textarea
 * @param {string} placeholder - Custom placeholder text
 * @returns {Promise} - Promise that resolves with the editor instance
 */
function initializeCKEditorWithPlaceholder(selector, placeholder) {
    return initializeCKEditor(selector, { placeholder });
}

/**
 * Auto-generate slug from title
 * @param {string} titleSelector - CSS selector for title input
 * @param {string} slugSelector - CSS selector for slug input
 */
function initializeSlugGenerator(titleSelector, slugSelector) {
    const titleInput = document.querySelector(titleSelector);
    const slugInput = document.querySelector(slugSelector);

    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            const title = this.value;

            if (slugInput.value === '') {
                slugInput.value = title.toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
            }
        });
    }
}

/**
 * Initialize all CKEditor instances on the page
 * @param {Object} options - Configuration options
 */
function initializeAllCKEditors(options = {}) {
    const editors = document.querySelectorAll('textarea[id*="content"]');

    editors.forEach((editor, index) => {
        const customPlaceholder = editor.dataset.placeholder || options.placeholder || CKEditorConfig.placeholder;

        initializeCKEditorWithPlaceholder(`#${editor.id}`, customPlaceholder)
            .then(editorInstance => {
                // Store editor instance for later use if needed
                editor.ckEditorInstance = editorInstance;
            })
            .catch(error => {
                console.error(`Failed to initialize CKEditor for ${editor.id}:`, error);
            });
    });
}

// Export functions for global use
window.CKEditorConfig = CKEditorConfig;
window.initializeCKEditor = initializeCKEditor;
window.initializeCKEditorWithPlaceholder = initializeCKEditorWithPlaceholder;
window.initializeSlugGenerator = initializeSlugGenerator;
window.initializeAllCKEditors = initializeAllCKEditors;

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize slug generators
    initializeSlugGenerator('#title', '#slug');

    // Initialize CKEditor if ClassicEditor is available
    if (typeof ClassicEditor !== 'undefined') {
        initializeAllCKEditors();
    } else {
        console.warn('CKEditor not loaded. Make sure to include the CKEditor CDN script.');
    }
});
