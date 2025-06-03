@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">My Vouchers</h1>
    <div class="row">
        <div class="">
            <h4>Current Points: {{ Auth::user()->points }}</h4>
            <div class="card">
                <div class="card-header">Redeem Points for Vouchers</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('vouchers.redeem') }}">
                        @csrf
                        <div class="form-group">                            
                            <label>Select Voucher Tier</label>
                            <div class="inline-form-group">
                                <select name="points" class="form-control">
                                    <option value="100">100 points = RM3 (min spend RM5)</option>
                                    <option value="250">250 points = RM8 (min spend RM10)</option>
                                    <option value="500">500 points = RM20 (min spend RM22)</option>
                                </select>                        
                            <button type="submit" class="btn">Redeem</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">My Vouchers</div>
                <div class="card-body">
                    @if($vouchers->isEmpty())
                        <p>You don't have any vouchers yet.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Value</th>
                                    <th>Min Spend</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vouchers as $voucher)
                                    <tr>
                                        <td>{{ $voucher->code }}</td>
                                        <td>RM{{ $voucher->discount_amount }}</td>
                                        <td>RM{{ $voucher->minimum_spend }}</td>
                                        <td>{{ $voucher->is_used ? 'Used' : 'Available' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
        <div class="">
            <div class="card">
                <div class="card-header">Refer a friend!</div>
                <div class="card-body">
                    <h4>Your Referral Code: {{ Auth::user()->referralCode->code }}</h4>
                    <div class="text-center">
                        <div style="display: inline-block; padding: 10px; background: white; border: 1px solid #b5b5b5;">
                            {!! Auth::user()->referralCode->getQrCode() !!}
                        </div>
                        <p class="mt-2">Scan this QR code to apply referral</p>
                    </div>
                    
                    <p>Share this code with friends to earn 150 points when they sign up!</p>

                    @if(!Auth::user()->has_entered_referral)
                        <hr>
                        <h4>Enter Referral Code</h4>
                        <form method="POST" action="{{ route('referral.process') }}" id="referral-form">
                            @csrf
                            <div class="form-group">
                                <div class="inline-form-group">
                                    <input type="text" name="code" id="referral-code-input" class="form-control" placeholder="Enter or scan referral code" required>
                                    <button type="button" id="scan-qr-btn" class="btn"><i class="fas fa-qrcode"></i></button>
                                    <button type="submit" class="btn">Apply Code</button>
                                </div>
                          
                            </div>
                            <div id="qr-reader-container">
                                <div id="qr-reader"></div>
                                <div class="cancel-div">
                                <button type="button" id="cancel-scan-btn" class="btn btn-danger">Cancel Scan</button>
                                </div>
                            </div>
                        <!--    <button type="submit" class="btn">Apply Code</button>-->
                        </form>
                    @else
                        <div class="alert alert-success">
                            You've already used a referral code.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(!Auth::user()->has_entered_referral)
<script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scanBtn = document.getElementById('scan-qr-btn');
        const cancelScanBtn = document.getElementById('cancel-scan-btn');
        const qrReaderContainer = document.getElementById('qr-reader-container');
        const referralInput = document.getElementById('referral-code-input');
        let html5QrCode;
        
        scanBtn.addEventListener('click', function() {
            qrReaderContainer.style.display = 'block';
            scanBtn.style.display = 'none';
            
            html5QrCode = new Html5Qrcode("qr-reader");
            
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    const cameraId = devices[0].id; // use first camera
                    
                    html5QrCode.start(
                        cameraId,
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 }
                        },
                        qrCodeMessage => {
                            // Handle scanned code
                            referralInput.value = qrCodeMessage;
                            stopScanner();
                        },
                        errorMessage => {
                            console.error(errorMessage);
                        }
                    ).catch(err => {
                        console.error("Unable to start scanner:", err);
                        alert("Unable to start QR scanner. Please check camera permissions.");
                    });
                }
            }).catch(err => {
                console.error("Unable to access camera:", err);
                alert("Could not access camera. Please ensure you've granted camera permissions.");
            });
        });
        
        cancelScanBtn.addEventListener('click', stopScanner);
        
        function stopScanner() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().then(() => {
                    console.log("QR Scanner stopped");
                }).catch(err => {
                    console.error("Failed to stop scanner", err);
                });
            }
            qrReaderContainer.style.display = 'none';
            scanBtn.style.display = 'inline-block';
        }
        
        // Clean up scanner when leaving page
        window.addEventListener('beforeunload', function() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop();
            }
        });
    });
</script>
@endif
@endsection

<style>
    .inline-form-group {
        display: flex;
        gap: 0.3rem;
        align-items: stretch; 
    }
    .inline-form-group .form-control {
        flex-grow: 1; 
    }
    
    .inline-form-group .btn {
        height: calc(2.25rem + 2px);
        width: 20%;
        padding-top: 0.375rem;
        padding-bottom: 0.375rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    #qr-reader-container {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin-bottom: 15px;
        flex-direction: column;
        align-items: center;
        display: none;
    }

    #qr-reader {
        margin: 0 auto;
        width: 50%;
    }

    #qr-reader__dashboard_section_csr {
        margin-bottom: 15px;
    }

    #scan-qr-btn {
        width: 8%;
    }

    #scan-qr-btn, #cancel-scan-btn {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #scan-qr-btn:hover {
        background-color: #5a6268;
    }

    .cancel-div {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #cancel-scan-btn {
        background-color: #dc3545;
        margin-top: 1rem;
    }

    #cancel-scan-btn:hover {
        background-color: #c82333;
    }

    .qr-code-img {
        max-width: 200px;
        margin: 0 auto;
        display: block;
    }
    /* Vouchers Page Styles */
    .card {
        border: 1px solid #b5b5b5;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
        background: #fff;
    }

    .card-header {
        background-color: #63966b;
        color: white;
        padding: 15px 20px;
        font-weight: bold;
        font-size: 18px;
        border-bottom: 1px solid #b5b5b5;
        border-top-left-radius: 7px;
        border-top-right-radius: 7px;
    }

    .card-body {
        padding: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #3a4d3d;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #b5b5b5;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s;
        margin-bottom: 15px;
    }

    .form-control:focus {
        outline: none;
        border-color: #63966b;
        box-shadow: 0 0 0 2px rgba(99, 150, 107, 0.2);
    }

    .btn-primary {
        background-color: #63966b;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #44684a;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .table th {
        background-color: #f7f7f7;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #3a4d3d;
        border-bottom: 2px solid #63966b;
    }

    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    /* Points Summary Card */
    .points-summary-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .points-summary-card h4 {
        color: #2d4833;
        margin-bottom: 15px;
    }

    .points-summary-card hr {
        border: 0;
        height: 1px;
        background-color: #ddd;
        margin: 15px 0;
    }

    .referral-code {
        background-color: #e9e9e9;
        padding: 10px;
        border-radius: 5px;
        font-family: monospace;
        font-size: 18px;
        text-align: center;
        margin: 10px 0;
        color: #212121;
    }
</style>