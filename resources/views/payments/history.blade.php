@extends('layouts.app')
@section('title', 'Payment History')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm" data-aos="fade-up">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>Payment History
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Room</th>
                                        @if($user->isRenter())
                                            <th>Landlord</th>
                                        @else
                                            <th>Renter</th>
                                        @endif
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <code>{{ $payment->payment_id }}</code>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $payment->room->title }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $payment->room->location }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($user->isRenter())
                                                    <div>
                                                        <strong>{{ $payment->landlord->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $payment->room->phone }}</small>
                                                    </div>
                                                @else
                                                    <div>
                                                        <strong>{{ $payment->renter->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $payment->renter->phone }}</small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary">{{ $payment->formatted_amount }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $payment->payment_type_display }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $payment->payment_method_display }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $payment->status_badge_class }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $payment->created_at->format('M j, Y') }}
                                                    <br>
                                                    {{ $payment->created_at->format('g:i A') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('payment.show', $payment->payment_id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if($payment->payment_proof)
                                                        <a href="{{ route('payment.download-proof', $payment->payment_id) }}" 
                                                           class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    @endif
                                                    @if($payment->isPending() && $user->id === $payment->renter_id)
                                                        <button class="btn btn-sm btn-outline-danger" 
                                                                onclick="if(confirm('Are you sure you want to cancel this payment?')) window.location.href='{{ route('payment.cancel', $payment->payment_id) }}'">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-credit-card text-muted fs-1 mb-3"></i>
                            <h5 class="text-muted">No payments found</h5>
                            <p class="text-muted">You haven't made any payments yet.</p>
                            @if($user->isRenter())
                                <a href="/" class="btn btn-primary">
                                    <i class="bi bi-search me-2"></i>Browse Rooms
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Statistics -->
            @if($payments->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white text-center" data-aos="fade-up" data-aos-delay="100">
                            <div class="card-body">
                                <i class="bi bi-credit-card fs-1 mb-2"></i>
                                <h4 class="mb-1">{{ $payments->total() }}</h4>
                                <p class="mb-0">Total Payments</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white text-center" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-body">
                                <i class="bi bi-check-circle fs-1 mb-2"></i>
                                <h4 class="mb-1">{{ $payments->where('status', 'completed')->count() }}</h4>
                                <p class="mb-0">Completed</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark text-center" data-aos="fade-up" data-aos-delay="300">
                            <div class="card-body">
                                <i class="bi bi-clock fs-1 mb-2"></i>
                                <h4 class="mb-1">{{ $payments->where('status', 'pending')->count() }}</h4>
                                <p class="mb-0">Pending</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white text-center" data-aos="fade-up" data-aos-delay="400">
                            <div class="card-body">
                                <i class="bi bi-currency-rupee fs-1 mb-2"></i>
                                <h4 class="mb-1">₹{{ number_format($payments->where('status', 'completed')->sum('amount')) }}</h4>
                                <p class="mb-0">Total Amount</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 