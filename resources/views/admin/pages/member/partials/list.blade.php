<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>관리자 리스트</h6>
                @can('create', App\Models\Admin::class)
                    <a href="{{ route('admin.setting.member.write') }}" class="btn btn-sm btn-primary mb-0">
                        <x-tabler-plus />관리자 추가
                    </a>
                @endcan
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    ID
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    이름</th>
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
                            @forelse ($admins as $admin)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.setting.member.view', $admin->id) }}">
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $admin->user_id }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $admin->email }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $admin->name }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span
                                            class="badge badge-sm bg-gradient-{{ $admin->is_active ? "success" : "danger" }}">{{ $admin->is_active ? '사용중' : '미사용' }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span
                                            class="text-secondary text-xs font-weight-bold">{{ $admin->created_at->format('y.m.d') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.setting.member.view', $admin->id) }}"
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
