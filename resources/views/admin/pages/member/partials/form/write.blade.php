<div class="row">
    <div class="flex-fill">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex align-items-center">
                    <p class="mb-0">{{ $pageData['title'] }}</p>
                </div>
            </div>
            <div class="card-body">
                <form id="frm" autocomplete="off" novalidate>
                    <input type="hidden" name="pType" value="addAdmin">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group w-25">
                                <label for="name" class="form-control-label">관리자 이름<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="name" name="name"
                                    placeholder="Enter admin name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id" class="form-control-label">아이디<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="user_id" name="user_id"
                                    placeholder="Enter admin ID" required>
                            </div>
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input ms-auto mt-1" type="checkbox"
                                        id="flexSwitchCheckDefault" name="auth">
                                    <label class="form-check-label ms-2" for="flexSwitchCheckDefault">최고관리자 권한</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirm" class="form-control-label">비밀번호<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="password" id="password_confirm"
                                    name="password_confirm" placeholder="Enter admin password" required>
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-control-label">비밀번호 확인<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="password" id="password" name="password"
                                    placeholder="Enter admin password confirm" required>
                            </div>
                        </div>
                    </div>
                    <hr class="horizontal dark">
                    <div class="d-flex justify-content-end gap-2">
                        {{-- <a href="{{ route('admin.manager.users') }}" class="btn btn-outline-secondary">목록으로</a> --}}
                        <button id="submitBtn" type="submit" class="btn btn bg-gradient-warning">관리자 추가</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('afterScript')
    <script src="{{ asset('assets/plugins/validation/just-validate.min.js') }}"></script>
    <script>
        const procAddValidator = new JustValidate('#frm', apps.plugins.JustValidate.basic());
        procAddValidator.onSuccess((e) => {
                e.preventDefault();
                common.ajax.postFormSelector('{{ route('admin.setting.data') }}', '#frm');
            })
            .addField('#user_id', [{
                    rule: 'minLength',
                    value: 3,
                    errorMessage: '아이디는 3자 이상 입력 해주세요!',
                },
                {
                    rule: 'required',
                    errorMessage: '아이디를 입력해주세요.',
                },
                {
                    validator: (value) => () => {

                        if (!value || value.trim().length < 3) {
                            return Promise.resolve(true);
                        }

                        return new Promise((resolve) => {
                            common.ajax.returnFetch(
                                '{{ route('admin.setting.data') }}', {
                                    pType: 'checkUserId',
                                    value: value.trim()
                                }
                            ).then(data => {
                                resolve(!data.hasUserId);
                            }).catch(() => {
                                resolve(false);
                            });
                        });
                    },
                    errorMessage: '이미 사용중인 아이디 입니다.',
                },
            ]).addField('#name', [{
                rule: 'required',
                errorMessage: '이름을 입력해주세요!'
            }, ]).addField('#password', [{
                    rule: 'required',
                    errorMessage: '비밀번호를 입력해주세요!'
                },
                {
                    rule: 'minLength',
                    value: 4,
                    errorMessage: '비밀번호는 4자 이상 입력해주세요!'
                },
            ])
            .addField('#password_confirm', [{
                    rule: 'required',
                    errorMessage: '비밀번호 확인을 입력해주세요!'
                },
                {
                    validator: (value, fields) => {
                        if (fields['#password'] && fields['#password'].elem) {
                            const repeatPasswordValue = fields['#password'].elem.value;
                            return value === repeatPasswordValue;
                        }

                        return true;
                    },
                    errorMessage: '비밀번호가 같지 않습니다.',
                },
            ]);
    </script>
@endsection
