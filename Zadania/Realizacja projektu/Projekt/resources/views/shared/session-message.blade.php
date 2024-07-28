@if (session('success'))
    <div class="container my-3">
        <div class="row">
            <div class="col alert alert-success text-center">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="container my-3">
        <div class="row">
            <div class="col alert alert-danger text-center">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif
