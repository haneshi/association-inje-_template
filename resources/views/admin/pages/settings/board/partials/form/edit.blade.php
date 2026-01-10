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
                    <input type="hidden" name="pType" value="setBoard">
                    <input type="hidden" name="id" value="{{ $board->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="type" class="form-label">게시판 타입</label>
                            <select id="type" name="type" class="form-select" disabled>
                                @foreach (config('sites.board.type') as $key => $val)
                                    <option value="{{ $key }}"
                                        @if ($key === $board->type) selected @endif>
                                        {{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="board_name" class="form-control-label">게시판 이름<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="board_name" name="board_name"
                                    value="{{ $board->board_name }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input ms-auto mt-1" type="checkbox" id="is_fixed"
                                        name="is_fixed" @if ($board->is_fixed) checked @endif>
                                    <label class="form-check-label ms-2" for="is_fixed">알림 고정</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input ms-auto mt-1" type="checkbox" id="is_secret"
                                        name="is_secret" @if ($board->is_secret) checked @endif>
                                    <label class="form-check-label ms-2" for="is_secret">비밀글 사용</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input ms-auto mt-1" type="checkbox" id="is_comment"
                                        name="is_comment" @if ($board->is_comment) checked @endif>
                                    <label class="form-check-label ms-2" for="is_comment">댓글 사용</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input ms-auto mt-1" type="checkbox" id="is_period"
                                        name="is_period" @if ($board->is_period) checked @endif>
                                    <label class="form-check-label ms-2" for="is_period">기간 조절기능</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input ms-auto mt-1" type="checkbox" id="is_active"
                                    name="is_active" @if ($board->is_active) checked @endif>
                                <label class="form-check-label ms-2" for="is_active">사용유무</label>
                            </div>
                        </div>
                    </div>
                    <hr class="horizontal dark">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.setting.board') }}" class="btn btn-outline-secondary">목록으로</a>
                        <button id="submitBtn" type="submit" class="btn btn bg-gradient-warning">게시판 수정</button>
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
                const form = document.getElementById('frm');
                const formData = new FormData(form);
                common.ajax.postFormData('{{ route('admin.setting.data') }}', formData);
            })
            .addField('#board_name', [{
                rule: 'required',
                errorMessage: '게시판 이름을 입력해주세요.',
            }, ]);
    </script>
@endsection
