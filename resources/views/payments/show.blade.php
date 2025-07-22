@extends('layouts.app')
@section('title', 'Payment Details')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-up">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Payment Details -->
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4" data-aos="fade-up">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title mb-0">
                                <i class="bi bi-credit-card me-2"></i>Payment Details
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">Payment Information</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Payment ID:</td>
                                            <td><code>{{ $payment->payment_id }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Amount:</td>
                                            <td class="text-primary fw-bold fs-5">{{ $payment->formatted_amount }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Type:</td>
                                            <td>{{ $payment->payment_type_display }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Method:</td>
                                            <td>{{ $payment->payment_method_display }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                <span class="badge {{ $payment->status_badge_class }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if($payment->due_date)
                                            <tr>
                                                <td class="fw-bold">Due Date:</td>
                                                <td>{{ $payment->due_date->format('M j, Y') }}</td>
                                            </tr>
                                        @endif
                                        @if($payment->description)
                                            <tr>
                                                <td class="fw-bold">Description:</td>
                                                <td>{{ $payment->description }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">Room Information</h6>
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="fw-bold">{{ $payment->room->title }}</h6>
                                            <p class="text-muted mb-2">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $payment->room->location }}
                                            </p>
                                            <p class="text-muted mb-2">
                                                <i class="bi bi-house me-1"></i>{{ ucfirst($payment->room->type) }}
                                            </p>
                                            <p class="text-primary fw-bold mb-0">
                                                ₹{{ number_format($payment->room->price) }}/month
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($payment->isPending())
                                <div class="alert alert-warning mt-3">
                                    <h6 class="fw-bold mb-2">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Payment Pending
                                    </h6>
                                    <p class="mb-0">Please complete the payment using the QR code below and then upload the payment proof.</p>
                                </div>
                            @endif

                            @if($payment->isCompleted())
                                <div class="alert alert-success mt-3">
                                    <h6 class="fw-bold mb-2">
                                        <i class="bi bi-check-circle me-2"></i>Payment Completed
                                    </h6>
                                    <p class="mb-0">Payment has been completed successfully on {{ $payment->paid_at->format('M j, Y g:i A') }}.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    @if($payment->isPending())
                        <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="100">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-qr-code me-2"></i>Payment QR Code
                                </h5>
                            </div>
                            <div class="card-body text-center p-4">
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        @if($payment->qr_code)
                                            <div class="border rounded p-3 bg-white d-inline-block">
                                                <img src="{{ asset('storage/' . $payment->qr_code) }}" 
                                                     alt="Payment QR Code" 
                                                     class="img-fluid"
                                                     style="max-width: 300px;">
                                            </div>
                                        @else
                                            <div class="border rounded p-3 bg-white d-inline-block">
                                                <div class="text-center py-4">
                                                    <i class="bi bi-qr-code text-muted fs-1 mb-3"></i>
                                                    <p class="text-muted">QR Code not available</p>
                                                    <p class="text-muted small">Please contact support for assistance</p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <h6 class="fw-bold mb-2">How to pay:</h6>
                                            <ol class="text-start">
                                                <li>Open your UPI app (Google Pay, PhonePe, Paytm, etc.)</li>
                                                <li>Scan the QR code above</li>
                                                <li>Enter the amount: <strong>{{ $payment->formatted_amount }}</strong></li>
                                                <li>Add a note: <code>{{ $payment->payment_id }}</code></li>
                                                <li>Complete the payment</li>
                                                <li>Upload the payment proof below</li>
                                            </ol>
                                        </div>

                                        <div class="mt-3">
                                            <h6 class="fw-bold mb-2">Landlord Details:</h6>
                                            <p class="mb-1"><strong>Name:</strong> {{ $payment->landlord->name }}</p>
                                            <p class="mb-1"><strong>Phone:</strong> {{ $payment->room->phone }}</p>
                                            <p class="mb-0"><strong>UPI ID:</strong> {{ $payment->room->phone }}@paytm</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Proof Upload -->
                    @if($payment->isPending() && $user->id === $payment->renter_id)
                        <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-upload me-2"></i>Upload Payment Proof
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('payment.upload-proof', $payment->payment_id) }}" 
                                      method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="payment_proof" class="form-label">Payment Proof *</label>
                                            <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" 
                                                   id="payment_proof" name="payment_proof" 
                                                   accept="image/*,.pdf" required>
                                            <div class="form-text">Upload screenshot or PDF of payment confirmation</div>
                                            @error('payment_proof')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="transaction_id" class="form-label">Transaction ID (Optional)</label>
                                            <input type="text" class="form-control @error('transaction_id') is-invalid @enderror" 
                                                   id="transaction_id" name="transaction_id" 
                                                   placeholder="Enter transaction ID if available">
                                            @error('transaction_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-upload me-2"></i>Upload Proof
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Proof Display -->
                    @if($payment->payment_proof)
                        <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="300">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-file-earmark-check me-2"></i>Payment Proof
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="fw-bold mb-2">Uploaded Proof</h6>
                                        <p class="text-muted mb-2">
                                            <i class="bi bi-calendar me-1"></i>
                                            Uploaded on {{ $payment->paid_at->format('M j, Y g:i A') }}
                                        </p>
                                        @if($payment->transaction_id)
                                            <p class="text-muted mb-0">
                                                <i class="bi bi-hash me-1"></i>
                                                Transaction ID: <code>{{ $payment->transaction_id }}</code>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <a href="{{ route('payment.download-proof', $payment->payment_id) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-download me-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Action Buttons -->
                    <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-header bg-dark text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-gear me-2"></i>Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($payment->isPending() && $user->id === $payment->renter_id)
                                    <button class="btn btn-outline-danger" 
                                            onclick="if(confirm('Are you sure you want to cancel this payment?')) window.location.href='{{ route('payment.cancel', $payment->payment_id) }}'">
                                        <i class="bi bi-x-circle me-2"></i>Cancel Payment
                                    </button>
                                @endif
                                
                                <a href="{{ route('payment.history') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-clock-history me-2"></i>Payment History
                                </a>
                                
                                <a href="/" class="btn btn-outline-primary">
                                    <i class="bi bi-house me-2"></i>Back to Home
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-check me-2"></i>Contact Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">Landlord</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $payment->landlord->name }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $payment->room->phone }}</p>
                            <p class="mb-0"><strong>Email:</strong> {{ $payment->room->email }}</p>
                            
                            <hr>
                            
                            <h6 class="fw-bold mb-2">Renter</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $payment->renter->name }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $payment->renter->phone }}</p>
                            <p class="mb-0"><strong>Email:</strong> {{ $payment->renter->email }}</p>
                        </div>
                    </div>

                    <!-- Payment Timeline -->
                    <div class="card shadow-sm" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-clock me-2"></i>Payment Timeline
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Payment Created</h6>
                                        <small class="text-muted">{{ $payment->created_at->format('M j, Y g:i A') }}</small>
                                    </div>
                                </div>
                                
                                @if($payment->isCompleted())
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="fw-bold">Payment Completed</h6>
                                            <small class="text-muted">{{ $payment->paid_at->format('M j, Y g:i A') }}</small>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($payment->status === 'cancelled')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-danger"></div>
                                        <div class="timeline-content">
                                            <h6 class="fw-bold">Payment Cancelled</h6>
                                            <small class="text-muted">{{ $payment->updated_at->format('M j, Y g:i A') }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #dee2e6;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -29px;
    top: 17px;
    width: 2px;
    height: calc(100% + 3px);
    background-color: #dee2e6;
}

.timeline-content h6 {
    margin-bottom: 5px;
}
</style>
@endpush 