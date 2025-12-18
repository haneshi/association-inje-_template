<div class="container">
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
            <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                    <h3 class="font-weight-bolder text-info text-gradient">협회 로그인</h3>
                    <p class="mb-0">Enter your ID and password to sign in</p>
                </div>
                <div class="card-body">
                    <form role="form" id="frm" novalidate>
                        <label>ID</label>
                        <div class="mb-3">
                            <input type="ID" class="form-control" placeholder="ID" aria-label="ID"
                                aria-describedby="ID-addon" name="user_id" id="user_id">
                        </div>
                        <label>Password</label>
                        <div class="mb-3">
                            <input type="password" class="form-control" placeholder="Password" aria-label="Password"
                                aria-describedby="password-addon" name="password" id="password">
                        </div>
                        <div class="text-center">
                            <button class="btn bg-gradient-info w-100 mt-4 mb-0">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6"
                    style="background-image:url('{{ asset('assets/plugins/img/curved-images/curved6.jpg') }}')"></div>
            </div>
        </div>
    </div>
</div>
@section('afterScript')
    <script src="{{ asset('assets/plugins/validation/just-validate.min.js') }}"></script>
    <script>
        const validation = new JustValidate('#frm', apps.plugins.JustValidate.basic())
            .onSuccess((e) => {
                e.preventDefault();
                common.ajax.postFormID('{{ route('admin.login') }}', 'frm');
            })
            .addField('#user_id', [{
                    rule: 'required',
                    errorMessage: '아이디를 입력해 주세요'
                },
                {
                    rule: 'minLength',
                    value: 3,
                    errorMessage: '아이디는 3자리 이상을 입력해 주세요'
                },
            ])
            .addField('#password', [{
                rule: 'required',
                errorMessage: '비밀번호를 입력해 주세요'
            }, ]);
    </script>
@endsection
