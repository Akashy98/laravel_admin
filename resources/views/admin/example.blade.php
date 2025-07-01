@extends('admin.layouts.app')

@section('title', 'Example Page')
@section('page-title', 'Example Page')
@section('page-subtitle', 'Demonstrating common JavaScript functions')

@php
$breadcrumbs = [
    ['title' => 'Examples', 'url' => route('admin.example')]
];
@endphp

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="stats-card">
            <h5 class="mb-3">Toast Notifications</h5>
            <div class="d-grid gap-2">
                <button class="btn btn-success" onclick="AdminCommon.Toast.success('Success message!')">
                    <i class="fas fa-check me-2"></i>Success Toast
                </button>
                <button class="btn btn-danger" onclick="AdminCommon.Toast.error('Error message!')">
                    <i class="fas fa-times me-2"></i>Error Toast
                </button>
                <button class="btn btn-warning" onclick="AdminCommon.Toast.warning('Warning message!')">
                    <i class="fas fa-exclamation-triangle me-2"></i>Warning Toast
                </button>
                <button class="btn btn-info" onclick="AdminCommon.Toast.info('Info message!')">
                    <i class="fas fa-info-circle me-2"></i>Info Toast
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stats-card">
            <h5 class="mb-3">Loading Spinner</h5>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" onclick="showLoadingExample()">
                    <i class="fas fa-spinner me-2"></i>Show Loading (3s)
                </button>
                <button class="btn btn-secondary" onclick="AdminCommon.Loading.show()">
                    <i class="fas fa-spinner me-2"></i>Show Loading
                </button>
                <button class="btn btn-outline-secondary" onclick="hideLoadingExample()">
                    <i class="fas fa-times me-2"></i>Hide Loading
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="stats-card">
            <h5 class="mb-3">AJAX Examples</h5>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" onclick="testAjaxGet()">
                    <i class="fas fa-download me-2"></i>Test GET Request
                </button>
                <button class="btn btn-success" onclick="testAjaxPost()">
                    <i class="fas fa-upload me-2"></i>Test POST Request
                </button>
                <button class="btn btn-warning" onclick="testAjaxError()">
                    <i class="fas fa-exclamation-triangle me-2"></i>Test Error Handling
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stats-card">
            <h5 class="mb-3">Utility Functions</h5>
            <div class="d-grid gap-2">
                <button class="btn btn-info" onclick="testDateFormat()">
                    <i class="fas fa-calendar me-2"></i>Format Date
                </button>
                <button class="btn btn-secondary" onclick="testCurrencyFormat()">
                    <i class="fas fa-dollar-sign me-2"></i>Format Currency
                </button>
                <button class="btn btn-outline-primary" onclick="testCopyToClipboard()">
                    <i class="fas fa-copy me-2"></i>Copy to Clipboard
                </button>
                <button class="btn btn-outline-success" onclick="testGenerateId()">
                    <i class="fas fa-fingerprint me-2"></i>Generate ID
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="stats-card">
            <h5 class="mb-3">Form Validation Example</h5>
            <form id="exampleForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Submit Form
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="AdminCommon.FormHelper.reset(document.getElementById('exampleForm'))">
                        <i class="fas fa-undo me-2"></i>Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Example Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Example Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>This is an example modal that can be controlled using the ModalHelper functions.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Example functions to demonstrate the common JavaScript utilities

let currentLoadingSpinner = null;

function showLoadingExample() {
    currentLoadingSpinner = AdminCommon.Loading.show();
    setTimeout(() => {
        AdminCommon.Loading.hide(currentLoadingSpinner);
        AdminCommon.Toast.success('Loading completed!');
    }, 3000);
}

function hideLoadingExample() {
    if (currentLoadingSpinner) {
        AdminCommon.Loading.hide(currentLoadingSpinner);
        currentLoadingSpinner = null;
    }
}

function testAjaxGet() {
    AdminCommon.Ajax.get('https://jsonplaceholder.typicode.com/posts/1')
        .then(data => {
            AdminCommon.Toast.success('GET request successful!');
            console.log('Response:', data);
        })
        .catch(error => {
            AdminCommon.Toast.error('GET request failed!');
        });
}

function testAjaxPost() {
    const testData = {
        title: 'Test Post',
        body: 'This is a test post',
        userId: 1
    };

    AdminCommon.Ajax.post('https://jsonplaceholder.typicode.com/posts', testData)
        .then(data => {
            AdminCommon.Toast.success('POST request successful!');
            console.log('Response:', data);
        })
        .catch(error => {
            AdminCommon.Toast.error('POST request failed!');
        });
}

function testAjaxError() {
    AdminCommon.Ajax.get('https://invalid-url-that-does-not-exist.com')
        .then(data => {
            AdminCommon.Toast.success('This should not happen!');
        })
        .catch(error => {
            AdminCommon.Toast.error('Error handled correctly!');
        });
}

function testDateFormat() {
    const date = new Date();
    const formatted = AdminCommon.Utils.formatDate(date, 'YYYY-MM-DD');
    AdminCommon.Toast.info(`Formatted date: ${formatted}`);
}

function testCurrencyFormat() {
    const amount = 1234.56;
    const formatted = AdminCommon.Utils.formatCurrency(amount, 'USD');
    AdminCommon.Toast.info(`Formatted currency: ${formatted}`);
}

function testCopyToClipboard() {
    const text = 'This text was copied to clipboard!';
    AdminCommon.Utils.copyToClipboard(text)
        .then(() => {
            AdminCommon.Toast.success('Text copied to clipboard!');
        })
        .catch(() => {
            AdminCommon.Toast.error('Failed to copy text!');
        });
}

function testGenerateId() {
    const id = AdminCommon.Utils.generateId(10);
    AdminCommon.Toast.info(`Generated ID: ${id}`);
}

// Form validation example
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('exampleForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const errors = AdminCommon.FormHelper.validate(form, {
                name: { required: true, minLength: 2 },
                email: { required: true, email: true },
                message: { required: true, minLength: 10 }
            });

            if (errors.length > 0) {
                AdminCommon.FormHelper.showErrors(form, errors);
                AdminCommon.Toast.error('Please fix the errors in the form');
            } else {
                AdminCommon.Toast.success('Form is valid!');
                AdminCommon.FormHelper.reset(form);
            }
        });
    }
});

// Add some interactive examples
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to demonstrate viewport detection
    const cards = document.querySelectorAll('.stats-card');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            if (AdminCommon.Utils.isInViewport(this)) {
                AdminCommon.Toast.info('Card is in viewport!');
            }
        });
    });

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + M to show modal
        if ((e.ctrlKey || e.metaKey) && e.key === 'm') {
            e.preventDefault();
            AdminCommon.ModalHelper.show('exampleModal');
        }
    });
});
</script>
@endpush
