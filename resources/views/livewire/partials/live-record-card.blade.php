<div style="background: #f3f4f6; border-radius: 10px; padding: 16px; margin-bottom: 12px; border-left: 4px solid {{ $record->current_stage == 'completed' ? '#10b981' : '#f59e0b' }};">
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
        <div>
            <h3 style="font-size: 16px; font-weight: bold; margin: 0 0 4px 0;">{{ $record->vehicle_name }}</h3>
            <p style="font-size: 14px; color: #6b7280; margin: 0;">{{ $record->plate_number }}</p>
        </div>
        <div style="background: {{ $record->current_stage == 'completed' ? '#10b981' : '#f59e0b' }}; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; white-space: nowrap;">
            @if($record->current_stage == 'completed')
                ✓ Selesai
            @else
                {{ $stages[$record->current_stage]['label'] }}
            @endif
        </div>
    </div>
    <p style="font-size: 14px; color: #1f2937; margin: 0; line-height: 1.4;">{{ $record->description }}</p>
</div>