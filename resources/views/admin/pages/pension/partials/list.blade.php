<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>협회 펜션 리스트</h6>
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <form class="col-auto auto" action="{{ route('admin.pension') }}">
                        <div class="input-icon">
                            <div class="input-group">
                                <span class="input-group-text text-body"><x-tabler-search /></span>
                                <input type="text" id="st" name="st" value="{{ $paramData['st'] }}"
                                    class="form-control" placeholder="펜션명">
                            </div>
                        </div>
                    </form>
                </div>
                <a href="{{ route('admin.pension.write') }}" class="btn btn-sm btn-primary mb-0">
                    <x-tabler-plus />펜션 추가
                </a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">펜션명
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    지역</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    사용유무</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    등록일</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataList as $data)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.pension.view', $data->id) }}">
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="https://demos.creative-tim.com/soft-ui-design-system-pro/assets/img/team-2.jpg"
                                                        class="avatar avatar-sm me-3">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-xs">{{ $data->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $data->owner }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $data->location }}</p>
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
                                        <a href="{{ route('admin.pension.view', $data->id) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="Edit Pension">
                                            자세히 보기
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-w p-5" colspan="20">등록된 펜션이 없습니다.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($dataList->hasPages())
                <div class="card-footer d-flex align-items-center">
                    <div class="m-auto"><!-- mx-auto ms-auto -->
                        {{ $dataList->onEachSide(1)->withQueryString()->links('admin.components.pagination') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
