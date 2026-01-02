<form id="frm-room-write" autocomplete="off" novalidate>
    <input type="hidden" name="pType" value="addRoom">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="name" class="form-control-label">객실명<span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="name" name="name" placeholder="객실명을 입력해주세요!"
                    required>
            </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="person_min" class="form-control-label">기준인원<span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="person_min" name="person_min" placeholder="기준인원을 입력해주세요!"
                    required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="person_max" class="form-control-label">최대인원<span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="person_max" name="person_max" placeholder="최대인원을 입력해주세요!"
                    required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="area1" class="form-control-label">객실 면적(㎡)<span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="area1" name="area1" placeholder="면적(㎡)을 입력해주세요!"
                    required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="area2" class="form-control-label">객실 면적(평)</label>
                <input class="form-control" type="text" id="area2" name="area2" readonly disabled>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="shape" class="form-control-label">객실 유형<span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="shape" name="shape"
                    placeholder="예시) 온돌방1, 화장실1, 테라스. . ." required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="jsonData" class="form-control-label">구비시설<span class="text-danger">*</span></label>
                <input class="form-control" type="text" id="jsonData" name="jsonData[]" required>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="etc" class="form-control-label">특이사항</label>
            <input class="form-control" type="text" id="etc" name="etc">
        </div>
    </div>
    <div class="row">
        <div class="mb-3">
            <div class="d-flex align-items-center">
                <p class="mb-0">객실 전경</p>
            </div>
            <div class="mb-1 p-2 bg-gradient-warning text-white opacity-8">
                <small>최대 5개 이미지 업로드 가능 (최대 10MB, 이미지 파일만 허용)</small><br>
                <small>5개 이상일때는 우선 5개 업로드 후 추가해 주세요!</small>
            </div>
            <div id="drop-area-room-write">
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="form-check form-switch">
                <input class="form-check-input ms-auto mt-1" type="checkbox" id="is_active" name="is_active">
                <label class="form-check-label ms-2" for="is_active">사용유무</label>
            </div>
        </div>
    </div>
    <hr class="horizontal dark">
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.pension', $paramData) }}" class="btn btn-outline-secondary">목록으로</a>
        <button id="submitBtn" type="submit" class="btn btn bg-gradient-warning">객실 추가</button>
    </div>
</form>


@section('afterScript')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const area1Input = document.getElementById('area1');
            const area2Input = document.getElementById('area2');
            area1Input.addEventListener('input', function() {
                const sqMeters = parseFloat(this.value);

                if (!isNaN(sqMeters) && sqMeters > 0) {
                    const pyeong = (sqMeters / 3.3058).toFixed(2);
                    area2Input.value = pyeong + '평';
                } else {
                    area2Input.value = '';
                }
            });

            const jsonData = document.getElementById('jsonData');
            let tagify = new Tagify(jsonData);

            const procAddValidator = new JustValidate('#frm-room-write', apps.plugins.JustValidate.basic());
            procAddValidator.onSuccess((e) => {
                    e.preventDefault();
                    const form = document.getElementById('frm-room-write');
                    const formData = new FormData(form);

                    const files = uppy_room_write.getFiles();
                    files.forEach((file, index) => {
                        formData.append(`images[${index}]`, file.data);
                    });

                    const location = document.querySelector('input[name="location"]:checked').value;
                    formData.append('location', location);

                    common.ajax.postFormData('{{ route('admin.pension.data') }}', formData);
                })
                .addField('#name', [{
                    rule: 'required',
                    errorMessage: '객실명을 입력해주세요!',
                }, ])
                .addField('#person_min', [{
                        rule: 'number',
                        errorMessage: '숫자만 입력 가능합니다!'
                    },
                    {
                        rule: 'required',
                        errorMessage: "기준인원을 입력해주세요!"
                    }
                ])
                .addField('#person_max', [{
                        rule: 'number',
                        errorMessage: '숫자만 입력 가능합니다!'
                    },
                    {
                        rule: 'required',
                        errorMessage: "최대인원을 입력해주세요!"
                    }
                ])
                .addField('#area1', [{
                        rule: 'number',
                        errorMessage: '숫자만 입력 가능합니다!'
                    },
                    {
                        rule: 'required',
                        errorMessage: "면적을 입력해주세요!"
                    }
                ])
                .addField('#shape', [{
                    rule: 'required',
                    errorMessage: '객실유형을 입력해주세요!'
                }, ])
                .addField('#jsonData', [{
                    rule: 'required',
                    errorMessage: '구비시설을 입력해주세요!'
                }, ]);
        });
    </script>
@endsection
