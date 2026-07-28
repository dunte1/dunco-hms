@extends('admin.layouts.app')
@include('admin.partials.stats')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-plus text-primary mr-2"></i>
                        Create Report Template
                    </h2>
                    <p class="text-muted mb-0">Build a custom report template with your desired fields and filters</p>
                </div>
                <a href="{{ route('hms.reports.custom-builder.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </div>

    <form id="templateForm" action="{{ route('hms.reports.custom-builder.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <!-- Left Column: Template Configuration -->
            <div class="col-md-8">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Template Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required 
                                           placeholder="e.g., Monthly Patient Report">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-control">
                                        <option value="">Select Category</option>
                                        <option value="patient">Patient</option>
                                        <option value="billing">Billing</option>
                                        <option value="lab">Laboratory</option>
                                        <option value="pharmacy">Pharmacy</option>
                                        <option value="hr">HR</option>
                                        <option value="financial">Financial</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" 
                                      placeholder="Describe what this report contains..."></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_premium" value="1" id="isPremium">
                            <label class="form-check-label" for="isPremium">
                                Premium Feature
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Data Source Selection -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Data Source</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Select Table <span class="text-danger">*</span></label>
                            <select name="config[table]" id="tableSelect" class="form-control" required>
                                <option value="">-- Select a table --</option>
                                @foreach($availableTables as $table => $label)
                                    <option value="{{ $table }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Choose the main data table for this report</small>
                        </div>
                        <div id="fieldsContainer" class="d-none">
                            <label class="form-label">Select Fields</label>
                            <div id="fieldsList" class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                <small class="text-muted">Select a table first to see available fields</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Filters</h5>
                    </div>
                    <div class="card-body">
                        <div id="filtersContainer">
                            <div class="filter-row mb-3 border-bottom pb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select name="filters[0][field]" class="form-control filter-field">
                                            <option value="">Select Field</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="filters[0][operator]" class="form-control">
                                            <option value="=">=</option>
                                            <option value="!=">!=</option>
                                            <option value=">">&gt;</option>
                                            <option value="<">&lt;</option>
                                            <option value=">=">&gt;=</option>
                                            <option value="<=">&lt;=</option>
                                            <option value="LIKE">Contains</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="filters[0][value]" class="form-control" placeholder="Value">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-filter">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addFilter">
                            <i class="fas fa-plus mr-1"></i> Add Filter
                        </button>
                    </div>
                </div>

                <!-- Sorting & Grouping -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Sorting & Grouping</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Order By</label>
                                    <select name="config[order_by]" id="orderBySelect" class="form-control">
                                        <option value="id">ID</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Direction</label>
                                    <select name="config[order_direction]" class="form-control">
                                        <option value="asc">Ascending</option>
                                        <option value="desc">Descending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">Limit</label>
                                    <input type="number" name="config[limit]" class="form-control" value="100" min="1">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Group By (Optional)</label>
                            <select name="config[group_by]" id="groupBySelect" class="form-control">
                                <option value="">None</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Preview & Actions -->
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-save mr-2"></i> Save Template
                        </button>
                        <a href="{{ route('hms.reports.custom-builder.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                        <hr>
                        <h6>Template Preview</h6>
                        <div class="small text-muted">
                            <p><strong>Name:</strong> <span id="previewName">-</span></p>
                            <p><strong>Category:</strong> <span id="previewCategory">-</span></p>
                            <p><strong>Table:</strong> <span id="previewTable">-</span></p>
                            <p><strong>Fields:</strong> <span id="previewFields">-</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableSelect = document.getElementById('tableSelect');
    const fieldsContainer = document.getElementById('fieldsContainer');
    const fieldsList = document.getElementById('fieldsList');
    const orderBySelect = document.getElementById('orderBySelect');
    const groupBySelect = document.getElementById('groupBySelect');
    let filterCount = 1;

    // Fetch fields when table is selected
    tableSelect.addEventListener('change', function() {
        const table = this.value;
        if (!table) {
            fieldsContainer.classList.add('d-none');
            return;
        }

        fetch(`{{ route('hms.reports.custom-builder.api.table-fields') }}?table=${table}`)
            .then(response => response.json())
            .then(data => {
                if (data.fields) {
                    fieldsList.innerHTML = '';
                    const selectedFields = [];
                    
                    data.fields.forEach(field => {
                        const checkbox = document.createElement('div');
                        checkbox.className = 'form-check';
                        checkbox.innerHTML = `
                            <input class="form-check-input field-checkbox" type="checkbox" 
                                   value="${field}" id="field_${field}" data-field="${field}">
                            <label class="form-check-label" for="field_${field}">
                                ${field}
                            </label>
                        `;
                        fieldsList.appendChild(checkbox);
                        
                        // Add to selects
                        const option1 = new Option(field, field);
                        orderBySelect.appendChild(option1.cloneNode(true));
                        groupBySelect.appendChild(option1);
                        
                        // Add filter field options
                        document.querySelectorAll('.filter-field').forEach(select => {
                            const option = new Option(field, field);
                            select.appendChild(option.cloneNode(true));
                        });
                    });
                    
                    fieldsContainer.classList.remove('d-none');
                    updatePreview();
                }
            })
            .catch(error => {
                console.error('Error fetching fields:', error);
                fieldsList.innerHTML = '<small class="text-danger">Error loading fields</small>';
            });
    });

    // Update preview
    function updatePreview() {
        document.getElementById('previewName').textContent = 
            document.querySelector('input[name="name"]').value || '-';
        document.getElementById('previewCategory').textContent = 
            document.querySelector('select[name="category"]').value || '-';
        document.getElementById('previewTable').textContent = 
            tableSelect.value || '-';
        
        const selectedFields = Array.from(document.querySelectorAll('.field-checkbox:checked'))
            .map(cb => cb.dataset.field);
        document.getElementById('previewFields').textContent = 
            selectedFields.length > 0 ? selectedFields.join(', ') : 'All fields';
    }

    // Add filter row
    document.getElementById('addFilter').addEventListener('click', function() {
        const container = document.getElementById('filtersContainer');
        const newRow = document.createElement('div');
        newRow.className = 'filter-row mb-3 border-bottom pb-3';
        newRow.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <select name="filters[${filterCount}][field]" class="form-control filter-field">
                        <option value="">Select Field</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="filters[${filterCount}][operator]" class="form-control">
                        <option value="=">=</option>
                        <option value="!=">!=</option>
                        <option value=">">&gt;</option>
                        <option value="<">&lt;</option>
                        <option value="LIKE">Contains</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="filters[${filterCount}][value]" class="form-control" placeholder="Value">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-filter">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        
        // Populate filter field options
        Array.from(orderBySelect.options).forEach(option => {
            if (option.value) {
                const newOption = new Option(option.text, option.value);
                newRow.querySelector('.filter-field').appendChild(newOption);
            }
        });
        
        filterCount++;
    });

    // Remove filter row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-filter')) {
            e.target.closest('.filter-row').remove();
        }
    });

    // Update preview on input change
    document.querySelectorAll('input[name="name"], select[name="category"]').forEach(el => {
        el.addEventListener('change', updatePreview);
    });

    // Handle form submission
    document.getElementById('templateForm').addEventListener('submit', function(e) {
        const selectedFields = Array.from(document.querySelectorAll('.field-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selectedFields.length === 0) {
            selectedFields.push('*');
        }
        
        // Create config object
        const configInput = document.createElement('input');
        configInput.type = 'hidden';
        configInput.name = 'config[fields]';
        configInput.value = JSON.stringify(selectedFields);
        this.appendChild(configInput);
    });
});
</script>
@endpush
@endsection

