<div style="min-width:100px;max-width:150px">
    <small class="d-block mb-1">{{ $percentage === null ? '—' : rtrim(rtrim(number_format($percentage,1,',',''),'0'),',').' %' }}</small>
    <div class="progress" style="height:7px" role="progressbar" aria-label="{{ $label }}: completitud" aria-valuemin="0" aria-valuemax="100" @if($percentage!==null) aria-valuenow="{{ $percentage }}" @endif>
        <div class="progress-bar bg-info" style="width:{{ $percentage ?? 0 }}%"></div>
    </div>
</div>
