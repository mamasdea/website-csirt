<div>
    @if ($poll)
        <div class="poll-container">
            {{-- Poll Question --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-accent mb-2">{{ $poll->question }}</h3>
                <p class="text-xs text-subtle">
                    @if (!$hasVoted)
                        Pilih salah satu opsi di bawah ini
                    @else
                        Terima kasih sudah berpartisipasi!
                    @endif
                </p>
            </div>

            {{-- Status Messages --}}
            @if (session()->has('success') || session()->has('error') || $errors->any())
                <div
                    class="mb-4 p-4 rounded-xl text-sm font-medium flex items-start gap-3 animate-fade-in {{ session()->has('success') ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                    <i data-lucide="{{ session()->has('success') ? 'check-circle' : 'alert-circle' }}"
                        class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <span>
                        @if (session()->has('success'))
                            {{ session('success') }}
                        @elseif (session()->has('error'))
                            {{ session('error') }}
                        @else
                            Pilih salah satu opsi sebelum mengirim.
                        @endif
                    </span>
                </div>
            @endif

            {{-- Voting Form --}}
            @if (!$hasVoted)
                <form wire:submit.prevent="submitVote">
                    <div class="space-y-3 mb-4">
                        @foreach ($poll->options_with_votes as $index => $option)
                            @php
                                $optionId = $option['id'];
                                $optionText = $option['text'];
                                $isSelected = $optionId == $optionIdToVote;
                            @endphp

                            <div wire:key="poll-option-{{ $index }}" class="poll-option-wrapper">
                                <label for="option_{{ $optionId }}"
                                    wire:click="$set('optionIdToVote', {{ $optionId }})"
                                    class="poll-option group {{ $isSelected ? 'poll-option-selected' : '' }}">

                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="poll-radio {{ $isSelected ? 'poll-radio-selected' : '' }}">
                                            <div class="poll-radio-inner {{ $isSelected ? 'scale-100' : 'scale-0' }}">
                                            </div>
                                        </div>
                                        <span class="font-medium text-sm">{{ $optionText }}</span>
                                    </div>

                                    <i data-lucide="chevron-right"
                                        class="w-4 h-4 text-subtle group-hover:text-accent transition-colors {{ $isSelected ? 'text-accent' : '' }}"></i>

                                    <input type="radio" wire:model="optionIdToVote" id="option_{{ $optionId }}"
                                        value="{{ $optionId }}" class="sr-only">
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="poll-submit-btn" wire:loading.attr="disabled">
                        <span wire:loading.remove class="flex items-center justify-center gap-2">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Kirim Polling
                        </span>
                        <span wire:loading class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </form>
            @endif

            {{-- Poll Results --}}
            @if ($hasVoted)
                <div class="poll-results animate-slide-up">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-accent flex items-center gap-2">
                            <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                            Hasil Polling
                        </h4>
                        <span class="text-xs text-subtle">
                            {{ array_sum(array_column($poll->results, 'votes')) }} suara
                        </span>
                    </div>

                    <div class="space-y-4">
                        @foreach ($poll->results as $index => $result)
                            @php
                                $percentage = $result['percentage'];
                                $votes = $result['votes'];
                            @endphp
                            <div class="poll-result-item" style="animation-delay: {{ $index * 100 }}ms;">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-medium text-sm text-color">{{ $result['text'] }}</span>
                                    <div class="text-right">
                                        <span
                                            class="text-sm font-bold text-accent">{{ number_format($percentage, 1) }}%</span>
                                        <span class="text-xs text-subtle block">{{ $votes }} suara</span>
                                    </div>
                                </div>

                                <div class="poll-bar-container">
                                    <div class="poll-bar"
                                        style="width: {{ $percentage }}%; animation-delay: {{ $index * 100 }}ms;">
                                        <div class="poll-bar-shine"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="poll-empty">
            <i data-lucide="inbox" class="w-12 h-12 text-subtle mx-auto mb-3 opacity-50"></i>
            <p class="text-center text-subtle font-medium">Tidak ada polling aktif saat ini.</p>
            <p class="text-center text-subtle text-xs mt-1">Silakan cek kembali nanti</p>
        </div>
    @endif

    <style>
        /* Container */
        .poll-container {
            padding: 1.5rem;
            border-radius: 1rem;
            background: var(--bg-secondary);
            border: 1px solid color-mix(in oklab, var(--subtle-color) 30%, transparent);
        }

        /* Poll Options */
        .poll-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-radius: 0.75rem;
            border: 2px solid color-mix(in oklab, var(--subtle-color) 30%, transparent);
            background: var(--bg-primary);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .poll-option:hover {
            border-color: var(--accent);
            background: color-mix(in oklab, var(--accent) 5%, var(--bg-primary));
            transform: translateX(4px);
        }

        .poll-option-selected {
            border-color: var(--accent);
            background: color-mix(in oklab, var(--accent) 10%, var(--bg-primary));
            box-shadow: 0 0 0 3px color-mix(in oklab, var(--accent) 15%, transparent);
        }

        /* Custom Radio */
        .poll-radio {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid color-mix(in oklab, var(--subtle-color) 50%, transparent);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .poll-radio-selected {
            border-color: var(--accent);
            background: var(--accent);
        }

        .poll-radio-inner {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: white;
            transition: transform 0.2s ease;
        }

        /* Submit Button */
        .poll-submit-btn {
            width: 100%;
            padding: 0.875rem;
            background: var(--accent);
            color: white;
            font-weight: 600;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .poll-submit-btn:hover:not(:disabled) {
            background: color-mix(in oklab, var(--accent) 90%, black);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px color-mix(in oklab, var(--accent) 30%, transparent);
        }

        .poll-submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Results Section */
        .poll-results {
            padding-top: 1.5rem;
            border-top: 2px solid color-mix(in oklab, var(--subtle-color) 20%, transparent);
        }

        .poll-result-item {
            opacity: 0;
            animation: slideUp 0.4s ease forwards;
        }

        .poll-bar-container {
            height: 32px;
            background: color-mix(in oklab, var(--subtle-color) 10%, transparent);
            border-radius: 0.5rem;
            overflow: hidden;
            position: relative;
        }

        .poll-bar {
            height: 100%;
            background: var(--accent);
            border-radius: 0.5rem;
            position: relative;
            overflow: hidden;
            width: 0;
            animation: fillBar 1s ease forwards;
            box-shadow: 0 2px 8px color-mix(in oklab, var(--accent) 30%, transparent);
        }

        .poll-bar-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shine 2s ease infinite;
        }

        /* Empty State */
        .poll-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            background: var(--bg-secondary);
            border-radius: 1rem;
            border: 2px dashed color-mix(in oklab, var(--subtle-color) 30%, transparent);
        }

        /* Animations */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fillBar {
            from {
                width: 0;
            }
        }

        @keyframes shine {
            to {
                left: 100%;
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-slide-up {
            animation: slideUp 0.5s ease;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .poll-container {
                padding: 1rem;
            }

            .poll-option {
                padding: 0.875rem;
            }
        }
    </style>
</div>
