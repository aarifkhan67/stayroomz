@extends('layouts.app')
@section('title', 'Make Payment')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm" data-aos="fade-up">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-credit-card me-2"></i>Make Payment
                    </h4>
                </div>
                <div class="card-body p-4">
                    <!-- Room Details -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($room->image)
                                <img src="/uploads/rooms/{{ $room->image }}" class="img-fluid rounded" alt="{{ $room->title }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-house text-muted fs-1"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-2">{{ $room->title }}</h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt me-2"></i>{{ $room->location }}
                            </p>
                            <p class="text-muted mb-2">
                                <i class="bi bi-house me-2"></i>{{ ucfirst($room->type) }} • {{ ucfirst($room->occupant_type) }}
                            </p>
                            <div class="d-flex align-items-center gap-3">
                                <span class="fw-bold text-primary fs-4">₹{{ number_format($room->price) }}/month</span>
                                @if($room->deposit)
                                    <span class="text-muted">+ ₹{{ number_format($room->deposit) }} deposit</span>
                                @endif
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success">Available</span>
                            </div>
                        </div>
                    </div>

                    <!-- Landlord Details -->
                    <div class="alert alert-info mb-4">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-person-check me-2"></i>Landlord Details
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Name:</strong> {{ $room->owner_name }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $room->phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Email:</strong> {{ $room->email }}</p>
                                <p class="mb-0"><strong>Preferred Contact:</strong> {{ ucfirst($room->preferred_contact) }}</p>
                            </div>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Payment Form -->
                    <form action="{{ route('payment.create', $room->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Payment Amount *</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount', $room->price) }}" 
                                           min="1" step="0.01" required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="payment_type" class="form-label">Payment Type *</label>
                                <select class="form-select @error('payment_type') is-invalid @enderror" 
                                        id="payment_type" name="payment_type" required>
                                    <option value="">Select Payment Type</option>
                                    <option value="rent" {{ old('payment_type') == 'rent' ? 'selected' : '' }}>Rent</option>
                                    <option value="deposit" {{ old('payment_type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                                    <option value="maintenance" {{ old('payment_type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="other" {{ old('payment_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('payment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method *</label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="upi" {{ old('payment_method') == 'upi' ? 'selected' : '' }}>UPI</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online Payment</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" name="due_date" value="{{ old('due_date') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description (Optional)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Add any additional details about this payment...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Rooms
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-credit-card me-2"></i>Create Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card shadow-sm mt-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Payment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">How it works:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Create payment request
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Get QR code for payment
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Pay directly to landlord
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Upload payment proof
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Payment Methods:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-phone text-primary me-2"></i>
                                    UPI - Scan QR code with any UPI app
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-bank text-primary me-2"></i>
                                    Bank Transfer - Direct transfer to landlord
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-cash text-primary me-2"></i>
                                    Cash - Pay in person and upload receipt
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-credit-card text-primary me-2"></i>
                                    Online - Use any online payment method
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default due date to next month
    const today = new Date();
    const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
    const formattedDate = nextMonth.toISOString().split('T')[0];
    
    if (!document.getElementById('due_date').value) {
        document.getElementById('due_date').value = formattedDate;
    }

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const amount = document.getElementById('amount').value;
        const paymentType = document.getElementById('payment_type').value;
        const paymentMethod = document.getElementById('payment_method').value;
        
        if (!amount || amount <= 0) {
            e.preventDefault();
            alert('Please enter a valid payment amount.');
            return;
        }
        
        if (!paymentType) {
            e.preventDefault();
            alert('Please select a payment type.');
            return;
        }
        
        if (!paymentMethod) {
            e.preventDefault();
            alert('Please select a payment method.');
            return;
        }
    });
});
</script>
@endpush 