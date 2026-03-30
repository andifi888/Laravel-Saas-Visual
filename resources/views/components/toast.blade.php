<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    @if(session('success'))
    <div class="toast toast-success show">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle text-success me-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div class="toast toast-error show">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle text-danger me-2"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif
    
    @if(isset($errors) && $errors->any())
    <div class="toast toast-error show">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle text-danger me-2"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    </div>
    @endif
</div>
