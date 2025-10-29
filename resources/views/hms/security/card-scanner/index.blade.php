@extends('admin.layouts.app')

@section('title', 'Card Scanner')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">Card Scanner</h2>
            <p class="text-muted">Scan ID cards, RFID tags, and magnetic stripe cards</p>
        </div>
    </div>

    <!-- Scanner Interface -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Card Scanner</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Card Type</label>
                        <select class="form-select" id="cardType">
                            <option value="rfid">RFID Card</option>
                            <option value="id_card">ID Card (Barcode/QR)</option>
                            <option value="magnetic_stripe">Magnetic Stripe Card</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Scanner Location</label>
                        <input type="text" class="form-control" id="scannerLocation" value="Reception">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Card Data</label>
                        <input type="text" class="form-control form-control-lg" id="cardData" 
                            placeholder="Scan card or enter card data manually" autofocus>
                        <small class="text-muted">Scan card using connected scanner or manually enter data</small>
                    </div>
                    
                    <button class="btn btn-primary btn-lg w-100" onclick="scanCard()">
                        <i class="fas fa-qrcode"></i> Scan Card
                    </button>
                </div>
            </div>

            <!-- Scan Result -->
            <div class="card mt-4" id="scanResultCard" style="display: none;">
                <div class="card-header">
                    <h5>Scan Result</h5>
                </div>
                <div class="card-body" id="scanResult">
                    <!-- Result will be displayed here -->
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-outline-primary w-100 mb-2" onclick="viewHistory()">
                        <i class="fas fa-history"></i> View Scan History
                    </button>
                    <button class="btn btn-outline-info w-100 mb-2" onclick="testScanner()">
                        <i class="fas fa-cog"></i> Test Scanner
                    </button>
                    <button class="btn btn-outline-secondary w-100" onclick="clearResult()">
                        <i class="fas fa-eraser"></i> Clear Result
                    </button>
                </div>
            </div>

            <!-- Recent Scans -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6>Recent Scans</h6>
                </div>
                <div class="card-body">
                    <div id="recentScans">
                        <p class="text-muted text-center">No recent scans</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-submit on card data entry
$('#cardData').on('keypress', function(e) {
    if (e.which === 13) {
        scanCard();
    }
});

function scanCard() {
    const cardType = $('#cardType').val();
    const cardData = $('#cardData').val();
    const location = $('#scannerLocation').val();
    
    if (!cardData) {
        alert('Please enter or scan card data');
        return;
    }
    
    $.ajax({
        url: '{{ route("card-scanner.scan") }}',
        method: 'POST',
        data: {
            card_type: cardType,
            card_data: cardData,
            scanner_location: location,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            displayResult(response);
            $('#cardData').val('').focus();
            loadRecentScans();
        },
        error: function(xhr) {
            alert('Error: ' + (xhr.responseJSON?.message || 'Scan failed'));
        }
    });
}

function displayResult(result) {
    const resultCard = $('#scanResultCard');
    const resultDiv = $('#scanResult');
    
    if (result.success) {
        let html = '<div class="alert alert-success"><strong>Success!</strong> ' + result.message + '</div>';
        
        if (result.patient) {
            html += `
                <div class="card border-primary mb-3">
                    <div class="card-header bg-primary text-white">
                        <h6><i class="fas fa-user-injured"></i> Patient Information</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> ${result.patient.name}</p>
                        <p><strong>Patient No:</strong> ${result.patient.patient_no}</p>
                        <a href="/hms/patients/${result.patient.id}" class="btn btn-sm btn-primary">View Details</a>
                    </div>
                </div>
            `;
        }
        
        if (result.employee) {
            html += `
                <div class="card border-info mb-3">
                    <div class="card-header bg-info text-white">
                        <h6><i class="fas fa-user-tie"></i> Employee Information</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> ${result.employee.name}</p>
                        <p><strong>Employee ID:</strong> ${result.employee.employee_id}</p>
                        <p><strong>Department:</strong> ${result.employee.department}</p>
                        <a href="/hms/staff/${result.employee.id}" class="btn btn-sm btn-info">View Details</a>
                    </div>
                </div>
            `;
        }
        
        resultDiv.html(html);
    } else {
        resultDiv.html('<div class="alert alert-danger"><strong>Error!</strong> ' + result.message + '</div>');
    }
    
    resultCard.show();
}

function clearResult() {
    $('#scanResultCard').hide();
    $('#cardData').val('').focus();
}

function viewHistory() {
    window.location.href = '{{ route("card-scanner.history") }}';
}

function testScanner() {
    alert('Scanner test mode. Connect your card scanner hardware.');
}

function loadRecentScans() {
    // Load recent scans via AJAX
    $.ajax({
        url: '{{ route("card-scanner.history") }}',
        success: function(response) {
            if (response.success && response.data.length > 0) {
                let html = '';
                response.data.slice(0, 5).forEach(function(scan) {
                    html += `<div class="mb-2 p-2 border rounded">
                        <small><strong>${scan.card_type}</strong> - ${new Date(scan.created_at).toLocaleString()}</small>
                    </div>`;
                });
                $('#recentScans').html(html);
            }
        }
    });
}

// Load recent scans on page load
$(document).ready(function() {
    loadRecentScans();
});
</script>
@endsection

