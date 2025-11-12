<div class="min-h-full">

    {{-- TAMPILAN JIKA SUDAH LOGIN (DASHBOARD) --}}
    @if (Auth::check())
        @php $user = Auth::user(); @endphp
        <div style="background: #f3f4f6; min-height: 100%;">
        <div wire:poll.10m></div>
            <div style="background: #2563eb; color: white; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <h1 style="font-size: 24px; font-weight: bold; margin: 0 0 4px 0;">Tracking Bongkar Muat</h1>
                        <p style="font-size: 14px; margin: 0; opacity: 0.9;">PT. CBA Chemical Industry</p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="font-size: 14px; opacity: 0.9;">👤 {{ $user->name }}</span>
                        <button wire:click="logout" class="btn" style="background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; cursor: pointer;">
                            Logout
                        </button>
                    </div>
                </div>
            </div>

            <div style="max-width: 1200px; margin: 0 auto; padding: 16px;">
                @if ($user->role === 'security')
                    <button wire:click="openNewEntryModal" class="btn" style="width: 100%; background: #2563eb; color: white; padding: 14px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        + Tambah Kendaraan Baru
                    </button>
                @endif
                
                @if ($user->role === 'admin')
                    <button wire:click="exportExcel" class="btn" style="width: 100%; background: #10b981; color: white; padding: 14px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        📥 Export ke Excel
                    </button>
                @endif

                <div id="recordsList">
                    @forelse ($userRecords as $record)
                        @include('livewire.partials.record-card', ['record' => $record, 'currentUserRole' => $user->role])
                    @empty
                        <div style="background: white; border-radius: 12px; padding: 32px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <p style="font-size: 16px; color: #9ca3af; margin: 0;">Tidak ada data untuk tahap ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    {{-- TAMPILAN JIKA BELUM LOGIN (HALAMAN LOGIN) --}}
    @else
        <div style="background: linear-gradient(135deg, #2563eb 0%, #10b981 100%); min-height: 100%; display: flex; align-items: center; justify-content: center; padding: 16px;">
            <div style="width: 100%; max-width: 1200px;">
                <div style="text-align: center; margin-bottom: 32px;">
                    <h1 style="font-size: 32px; font-weight: bold; color: white; margin: 0 0 8px 0;">Tracking Bongkar Muat</h1>
                    <p style="font-size: 18px; color: rgba(255,255,255,0.9); margin: 0;">PT. CBA Chemical Industry</p>
                </div>
                
                <div style="background: white; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <div class="pulse" style="width: 12px; height: 12px; background: #10b981; border-radius: 50%;"></div>
                        <h2 style="font-size: 20px; font-weight: bold; margin: 0; color: #1f2937;">Update Terkini - Live</h2>
                    </div>
                    
                    <div wire:poll.5s id="liveRecordsList" style="max-height: 400px; overflow-y: auto;">
                        @forelse ($liveRecords as $record)
                            @include('livewire.partials.live-record-card', ['record' => $record])
                        @empty
                            <div style="text-align: center; padding: 32px;">
                                <p style="font-size: 16px; color: #9ca3af; margin: 0;">Belum ada data</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                    <h2 style="font-size: 24px; font-weight: bold; margin: 0 0 24px 0; color: #1f2937; text-align: center;">Login</h2>
                    
                    <form wire:submit.prevent="login">
                        @if ($loginError)
                            <div style="background: #fef2f2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 16px; text-align: center;">
                                {{ $loginError }}
                            </div>
                        @endif
                        <div style="margin-bottom: 20px;">
                            <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 8px; color: #1f2937;">Pilih User:</label>
                            <select wire:model="login_user_id" required style="width: 100%; padding: 14px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px; color: #1f2937; background: white;">
                                <option value="">-- Pilih Nama Anda --</option>
                                @foreach ($allUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div style="margin-bottom: 24px;">
                            <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 8px; color: #1f2937;">PIN/Password (4 digit):</label>
                            <input wire:model="login_pin" type="password" required maxlength="4" pattern="[0-9]{4}" placeholder="Masukkan PIN 4 digit" style="width: 100%; padding: 14px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px; color: #1f2937; background: white;">
                        </div>
                        
                        <button type="submit" class="btn" style="width: 100%; background: #2563eb; color: white; padding: 14px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            🔐 Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif


    {{-- MODAL (Create / Update) --}}
    @if ($showModal)
        <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; padding: 16px; overflow-y: auto;">
            <div style="max-width: 600px; margin: 20px auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
                <form wire:submit.prevent="handleSubmit">
                    <h2 style="font-size: 20px; font-weight: bold; margin: 0 0 20px 0;">
                        @if ($modalAction === 'create')
                            Tambah Kendaraan Baru
                        @else
                            Update Status - {{ $stages[$editingRecord->current_stage]['label'] }}
                        @endif
                    </h2>

                    <div id="formFields">
                        @if ($modalAction === 'create')
                            <div style="margin-bottom: 16px;">
                                <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px;">Nama Kendaraan</label>
                                <input wire:model="vehicle_name" type="text" required style="width: 100%; padding: 10px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px;">
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px;">Plat Nomor</label>
                                <input wire:model="plate_number" type="text" required style="width: 100%; padding: 10px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px;">
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px;">Keterangan</label>
                                <textarea wire:model="description" required rows="3" style="width: 100%; padding: 10px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px; resize: vertical;"></textarea>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px;">Waktu Mulai</label>
                                <input wire:model="start_time" type="datetime-local" required style="width: 100%; padding: 10px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px;">
                            </div>
                        
                        @else
                            @php
                                $startField = $editingRecord->current_stage . '_start';
                                $hasStarted = !is_null($editingRecord->$startField);
                            @endphp
                            <div style="margin-bottom: 16px;">
                                <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px;">
                                    {{ $hasStarted ? 'Waktu Selesai' : 'Waktu Mulai' }}
                                </label>
                                <input wire:model="start_time" type="datetime-local" required style="width: 100%; padding: 10px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px;">
                            </div>
                        @endif
                    </div>
                    
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" class="btn" style="flex: 1; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;">
                            Simpan
                        </button>
                        <button wire:click="closeModal" type="button" class="btn" style="flex: 1; background: #f3f4f6; color: #1f2937; padding: 12px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>