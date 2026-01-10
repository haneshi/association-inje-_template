<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>게시판 리스트</h6>
                <a href="{{ route('admin.setting.board.write') }}" class="btn btn-sm btn-primary mb-0">
                    <x-tabler-plus />게시판 추가
                </a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th
                                    class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">
                                    순서
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    게시판 이름</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    사용유무</th>
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
                                            <x-tabler-arrows-move style="width: 1rem; cursor: pointer;"
                                                class="handle" />
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>
                                        <a href="{{ route('admin.setting.board.view', $data->id) }}"><p class="text-xs font-weight-bold mb-0">{{ $data->board_name }}</p></a>
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
                                        <a href="{{ route('admin.setting.board.view', $data->id) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="Edit user">
                                            자세히 보기
                                        </a>
                                    </td>
                                </tr>
                            @empty
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
                    common.ajax.postJson('{{ route('admin.travel.data') }}', {
                        pType: 'setSeq',
                        seqIdxes
                    });
                }
            }
        });
    </script>
@endsection
