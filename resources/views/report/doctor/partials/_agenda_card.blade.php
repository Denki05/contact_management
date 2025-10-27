@php
    use Carbon\Carbon;
    $carbon = Carbon::parse($date);
    $dayNames = [
        'Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
        'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'
    ];
    $dayName = $dayNames[$carbon->format('l')] ?? $carbon->format('l');
@endphp

<div class="card shadow flex-fill h-100 border-0 rounded-3">
    <div class="card-header text-center fw-bold bg-secondary text-white p-3 rounded-top-3">
        {{ $dayName }}, {{ $carbon->isoFormat('D MMMM Y') }}
    </div>
    
    <div class="card-body p-2">
        <ul class="list-group list-group-flush">
            @if ($tasks->isEmpty())
                <li class="list-group-item text-muted text-center py-5 border-0 bg-light">
                    <i class="far fa-calendar-times fa-2x mb-2 d-block"></i>
                    Tidak ada agenda
                </li>
            @else
                @for($i = 0; $i < $maxSlot; $i++)
                    @php
                        $task = $tasks[$i] ?? null;
                        $status = $task['status'] ?? 1;
                        $status = is_numeric($status) ? (int)$status : 1;

                        // Default
                        $statusBgClass = 'bg-white text-dark';
                        $statusText = 'Sedang dikerjakan';
                        if($status === 0){
                            $statusBgClass = 'bg-danger text-white';
                            $statusText = 'Dihapus / Batal';
                        } elseif($status === 2){
                            $statusBgClass = 'bg-success text-white';
                            $statusText = 'Selesai';
                        }
                    @endphp

                    @if($task)
                        <li class="list-group-item d-flex align-items-center py-2 {{ $statusBgClass }}">
                            <i class="fas fa-dot-circle me-2" title="{{ $statusText }}"></i>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-truncate">{{ $i+1 }}. {{ $task['keterangan_task'] ?? '-' }}</div>
                            </div>
                        </li>
                    @endif
                @endfor
            @endif
        </ul>
    </div>
</div>