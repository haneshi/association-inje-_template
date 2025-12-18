<form id="frm-edit" autocomplete="off" novalidate>
    <input type="hidden" name="pType" value="setAccount">
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md">
                <label for="user_id" class="form-label">아이디</label>
                <input type="text" class="form-control" id="user_id" name="user_id"
                    value="{{ config('auth.admin')->user_id }}" disabled>
            </div>
            <div class="col-md">
                <label for="name" class="form-label">이름</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ config('auth.admin')->name }}">
            </div>
        </div>
        <hr class="horizontal dark">
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.setting.member') }}" class="btn btn-outline-secondary">목록으로</a>
            <button id="submitBtn" type="submit" class="btn btn bg-gradient-warning">관리자 추가</button>
        </div>
    </div>
</form>
@section('afterScript')
    @parent
    <script>
        const procEditValidator = new JustValidate('#frm-edit', apps.plugins.JustValidate.basic());
        procEditValidator
            .onSuccess((e) => {
                e.preventDefault();
                common.ajax.postFormSelector('{{ route('admin.setting.data') }}', '#frm-edit');
            })
            .addField('#name', [{
                rule: 'required',
                errorMessage: '이름을 입력해 주세요!'
            }, ]);
    </script>
@endsection
