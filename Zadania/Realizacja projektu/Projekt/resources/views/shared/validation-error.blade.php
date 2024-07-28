@if ($errors->any())
    <div class="row d-flex justify-content-center">
        <div class="col-10 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
            <div class="alert alert-danger pb-1">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
