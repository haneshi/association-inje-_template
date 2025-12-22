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
                    <input type="hidden" name="pType" value="addPension">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name" class="form-control-label">펜션명<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="name" name="name"
                                    placeholder="Enter pension name" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="owner" class="form-control-label">관리자 이름<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="owner" name="owner"
                                    placeholder="Enter admin name" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tel" class="form-control-label">펜션 전화번호<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="tel" name="tel"
                                    placeholder="Enter tel" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="reservation_key" class="form-control-label">예약시스템 키<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="reservation_key" name="reservation_key"
                                    placeholder="Enter reservation_key" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_basic" class="form-control-label">주소<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="address_basic" name="address_basic"
                                    placeholder="Enter addresss" onclick="searchPostcode()" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_detail" class="form-control-label">상세 주소</label>
                                <input class="form-control" type="address_detail" id="address_detail"
                                    name="address_detail" placeholder="Enter pension address detail" required>
                                <input type="hidden" class="form-control" id="post" name="post">
                                <input type="hidden" class="form-control" id="address_local"
                                    name="address_local">
                                <input type="hidden" class="form-control" id="address_jibun"
                                    name="address_jibun">
                                <input type="hidden" class="form-control" id="lat" name="lat">
                                <input type="hidden" class="form-control" id="lng" name="lng">
                            </div>
                        </div>
                    </div>
                    <hr class="horizontal dark">
                    <div class="d-flex justify-content-end gap-2">
                        {{-- <a href="{{ route('admin.manager.users') }}" class="btn btn-outline-secondary">목록으로</a> --}}
                        <button id="submitBtn" type="submit" class="btn btn bg-gradient-warning">펜션 추가</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('afterScript')
    <script src="{{ asset('assets/plugins/validation/just-validate.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/address.js') }}?v={{ env('SITES_ADMIN_ASSETS_VERSION') }}"></script>
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=49f49d684621b554bb7e4382786b3e46&libraries=services"></script>
    <script>
        const procAddValidator = new JustValidate('#frm', apps.plugins.JustValidate.basic());
        procAddValidator.onSuccess((e) => {
                e.preventDefault();
                common.ajax.postFormSelector('{{ route('admin.pension.data') }}', '#frm');
            })
            .addField('#name', [{
                rule: 'required',
                errorMessage: '펜션명을 입력해주세요.',
            }, ])
            .addField('#owner', [{
                rule: 'required',
                errorMessage: '관리자 이름을 입력해주세요!'
            }, ])
            .addField('#tel', [{
                rule: 'required',
                errorMessage: '펜션 전화번호를 입력해주세요!'
            }, ])
            .addField('#reservation_key', [{
                rule: 'required',
                errorMessage: '예약시스템 키를 입력해주세요!'
            }, ])
            .addField('#address_basic', [{
                rule: 'required',
                errorMessage: '주소를 입력해주세요!'
            }, ]);
    </script>
@endsection
