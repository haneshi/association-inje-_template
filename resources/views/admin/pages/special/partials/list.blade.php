<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>{{ $pageData['name'] }}</h6>
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <form class="col-auto auto" action="{{ route('admin.travel') }}">
                        <div class="input-icon">
                            <div class="input-group">
                                <span class="input-group-text text-body"><x-tabler-search /></span>
                                <input type="text" id="st" name="st" value="{{ $paramData['st'] }}"
                                    class="form-control" placeholder="특산물 이름">
                            </div>
                        </div>
                    </form>
                </div>
                <a href="{{ route('admin.special.write', request()->query()) }}" class="btn btn-sm btn-primary mb-0">
                    <x-tabler-plus />특산물 추가
                </a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">특산물
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">지역</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">사용유무</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    등록일</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody id="sortTable">
                            @forelse ($dataList as $data)
                                <tr @if ($data->is_active) data-id="{{ $data->id }}" @endif>
                                    @if ($data->is_active)
                                        <td class="align-middle text-center text-sm">
                                            <x-tabler-arrows-move style="width: 0.9rem; cursor: pointer;"
                                                class="handle" />
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>
                                        <a
                                            href="{{ route('admin.travel.view', ['id' => $data->id] + request()->query()) }}">
                                            <div class="d-flex px-2 py-1">
                                                <img src="{{ $data->preview ?? asset('assets/img/bg/no-image.jpg') }}"
                                                    class="avatar avatar-lg me-3" style="object-fit: cover;">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-xs">{{ $data->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $data->name_eng }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span
                                            class="badge badge-sm bg-gradient-{{ $data->is_active ? 'success' : 'danger' }}">{{ $data->is_active ? '사용중' : '미사용' }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span
                                            class="text-secondary text-xs font-weight-bold">{{ $data->created_at->format('y.m.d') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.travel.view', ['id' => $data->id] + request()->query()) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="Edit travel">
                                            자세히 보기
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-w p-5" colspan="20">
                                        @if (isset($paramData['st']))
                                            검색하신 [ {{ $paramData['st'] }} ]
                                        @else
                                            등록된
                                        @endif 특산물이 없습니다.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@section('afterScript')
    <script src="{{ asset('assets/plugins/SortableJS/sortable.min.js') }}"></script>
    <script>
        const sortTable = document.getElementById('sortTable');
        new Sortable(sortTable, {
            animation: 150,
            handle: '.handle',
            filter: '.filtered',
            onEnd: function(e) {
                const seqIdxes = [];
                const childNodes = document.querySelectorAll(`#sortTable > tr`);
                childNodes.forEach(item => {
                    if (item.getAttribute('data-id')) {
                        seqIdxes.push(item.getAttribute('data-id'));
                    }
                });
                if (seqIdxes.length > 1) {
                    common.ajax.postJson('{{ route('admin.special.data') }}', {
                        pType: 'setSeq',
                        seqIdxes
                    });
                }
            }
        });
    </script>
@endsection
