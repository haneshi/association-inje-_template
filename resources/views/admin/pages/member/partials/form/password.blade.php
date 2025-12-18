<form id="frm-password" autocomplete="off" novalidate>
    <input type="hidden" name="pType" value="setPassword">
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md">
                <label for="password_current" class="form-control-label">현재 비밀번호<span class="text-danger">*</span></label>
                <input class="form-control" type="password" id="password_current" name="password_current"
                    placeholder="Enter current password" required>
            </div>
            <div class="col-md">
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md">
                <label for="password" class="form-control-label">변경할 비밀번호<span class="text-danger">*</span></label>
                <input class="form-control" type="password" id="password" name="password"
                    placeholder="Enter new password" required>
            </div>
            <div class="col-md">
                <label for="password_confirm" class="form-control-label">변경할 비밀번호 확인<span
                        class="text-danger">*</span></label>
                <input class="form-control" type="password" id="password_confirm" name="password_confirm"
                    placeholder="Enter admin password" required>
            </div>
        </div>
    </div>
    <hr class="horizontal dark">
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.setting.member') }}" class="btn btn-outline-secondary">목록으로</a>
        <button id="submitBtn" type="submit" class="btn btn bg-gradient-warning">관리자 비밀번호 변경</button>
    </div>
</form>

@section('afterScript')
    @parent
    <script>
        const procPasswordValidator = new JustValidate('#frm-password', apps.plugins.JustValidate.basic());
        procPasswordValidator
            .onSuccess((e) => {
                e.preventDefault();
                common.ajax.postFormSelector('{{ route('admin.setting.data') }}', '#frm-password');
            })
            .addField('#password_current', [{
                    rule: 'required',
                    errorMessage: '현재 비밀번호를 입력해 주세요!'
                },
                {
                    rule: 'minLength',
                    value: 4,
                    errorMessage: '비밀번호는 4자 이상 입력해 주세요!',
                },
            ])
            .addField('#password', [{
                    rule: 'required',
                    errorMessage: '변경할 비밀번호를 입력해 주세요!'
                },
                {
                    rule: 'minLength',
                    value: 4,
                    errorMessage: '변경할 비밀번호는 4자 이상 입력해 주세요!',
                },
            ])
            .addField('#password_confirm', [{
                    rule: 'required',
                    errorMessage: '변경할 비밀번호 확인를 입력해 주세요!'
                },
                {
                    validator: (value, fields) => {
                        if (fields['#password'] && fields['#password'].elem) {
                            const repeatPasswordValue =
                                fields['#password'].elem.value;
                            return value === repeatPasswordValue;
                        }

                        return true;
                    },
                    errorMessage: '변경할 비밀번호가 같지 않습니다.',
                },
            ]);
    </script>
@endsection
