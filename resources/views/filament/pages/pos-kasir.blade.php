<x-filament-panels::page>

    {{-- Hide default page header --}}
    <style>
        .fi-page-header {
            display: none !important;
        }

        .fi-main-ctn {
            padding-bottom: 0 !important;
        }

        /* ── Google Fonts ── */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap');

        /* ── CSS Variables ── */
        :root {
            --pos-font: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            --pos-mono: 'JetBrains Mono', ui-monospace, monospace;
            --pos-primary: #4f46e5;
            --pos-primary-light: #eef2ff;
            --pos-primary-dark: #3730a3;
            --pos-accent: #06b6d4;
            --pos-surface: #ffffff;
            --pos-surface-alt: #f8fafc;
            --pos-surface-hover: #f1f5f9;
            --pos-border: #e2e8f0;
            --pos-text: #0f172a;
            --pos-text-secondary: #64748b;
            --pos-text-muted: #94a3b8;
            --pos-success: #10b981;
            --pos-danger: #ef4444;
            --pos-warning: #f59e0b;
            --pos-shadow-sm: 0 1px 2px rgba(0, 0, 0, .04);
            --pos-shadow: 0 4px 12px rgba(0, 0, 0, .06);
            --pos-shadow-lg: 0 12px 32px rgba(0, 0, 0, .1);
            --pos-radius: 16px;
            --pos-radius-sm: 10px;
            --pos-radius-xs: 6px;
        }

        .dark {
            --pos-surface: #0f172a;
            --pos-surface-alt: #1e293b;
            --pos-surface-hover: #334155;
            --pos-border: #334155;
            --pos-text: #f1f5f9;
            --pos-text-secondary: #94a3b8;
            --pos-text-muted: #64748b;
            --pos-primary-light: rgba(79, 70, 229, 0.15);
            --pos-shadow-sm: 0 1px 2px rgba(0, 0, 0, .2);
            --pos-shadow: 0 4px 12px rgba(0, 0, 0, .3);
            --pos-shadow-lg: 0 12px 32px rgba(0, 0, 0, .4);
        }

        /* ── Base ── */
        .pos-wrap {
            font-family: var(--pos-font);
            color: var(--pos-text);
            display: flex;
            overflow: hidden;
            margin-left: -1.5rem;
            margin-right: -1.5rem;
            margin-top: -1.5rem;
            height: calc(100vh - 65px);
        }

        .pos-wrap * {
            box-sizing: border-box;
        }

        /* ── Scrollbar ── */
        .pos-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .pos-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .pos-scroll::-webkit-scrollbar-thumb {
            background: var(--pos-border);
            border-radius: 100px;
        }

        .pos-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--pos-text-muted);
        }

        /* ── Left Panel ── */
        .pos-left {
            width: 400px;
            min-width: 400px;
            display: flex;
            flex-direction: column;
            background: var(--pos-surface);
            border-left: 1px solid var(--pos-border);
            order: 2;
        }

        @media (min-width: 1440px) {
            .pos-left {
                width: 440px;
                min-width: 440px;
            }
        }

        .pos-left-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--pos-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pos-left-header .label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--pos-text-muted);
        }

        .pos-left-header .value {
            font-size: 14px;
            font-weight: 800;
            color: var(--pos-text);
            margin-top: 2px;
        }

        .pos-left-header .clock {
            font-family: var(--pos-mono);
            font-size: 13px;
            font-weight: 700;
            color: var(--pos-text-secondary);
            background: var(--pos-surface-alt);
            padding: 4px 10px;
            border-radius: var(--pos-radius-xs);
        }

        /* ── Cart Items ── */
        .pos-cart {
            flex: 1;
            overflow-y: auto;
            padding: 12px 16px;
        }

        .pos-cart-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: var(--pos-radius-sm);
            background: var(--pos-surface-alt);
            border: 1px solid transparent;
            margin-bottom: 8px;
            transition: all .15s ease;
        }

        .pos-cart-item:hover {
            border-color: var(--pos-primary);
            background: var(--pos-primary-light);
        }

        .pos-cart-thumb {
            width: 48px;
            height: 48px;
            border-radius: var(--pos-radius-xs);
            overflow: hidden;
            background: var(--pos-surface-hover);
            flex-shrink: 0;
        }

        .pos-cart-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pos-cart-thumb .placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pos-text-muted);
        }

        .pos-cart-info {
            flex: 1;
            min-width: 0;
        }

        .pos-cart-info .name {
            font-size: 13px;
            font-weight: 700;
            color: var(--pos-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pos-cart-info .meta {
            font-size: 11px;
            color: var(--pos-text-muted);
            margin-top: 1px;
        }

        .pos-cart-info .price-unit {
            font-size: 11px;
            font-weight: 600;
            color: var(--pos-primary);
            margin-top: 2px;
        }

        .pos-cart-actions {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
        }

        .pos-qty-group {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pos-qty-btn {
            width: 28px;
            height: 28px;
            border-radius: var(--pos-radius-xs);
            border: 1px solid var(--pos-border);
            background: var(--pos-surface);
            color: var(--pos-text-secondary);
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .15s ease;
            line-height: 1;
        }

        .pos-qty-btn:hover {
            border-color: var(--pos-primary);
            color: var(--pos-primary);
            background: var(--pos-primary-light);
        }

        .pos-qty-btn.danger:hover {
            border-color: var(--pos-danger);
            color: var(--pos-danger);
            background: #fef2f2;
        }

        .pos-qty-value {
            width: 28px;
            text-align: center;
            font-family: var(--pos-mono);
            font-size: 13px;
            font-weight: 700;
            color: var(--pos-text);
        }

        .pos-cart-subtotal {
            font-family: var(--pos-mono);
            font-size: 13px;
            font-weight: 700;
            color: var(--pos-text);
        }

        /* ── Empty Cart ── */
        .pos-cart-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--pos-text-muted);
            padding: 40px 20px;
            text-align: center;
        }

        .pos-cart-empty .icon-ring {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--pos-surface-alt);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .pos-cart-empty h4 {
            font-size: 14px;
            font-weight: 700;
            margin: 0;
        }

        .pos-cart-empty p {
            font-size: 12px;
            margin-top: 4px;
        }

        /* ── Payment Section ── */
        .pos-payment {
            border-top: 1px solid var(--pos-border);
            padding: 16px;
            background: var(--pos-surface);
        }

        .pos-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: var(--pos-text-secondary);
            padding: 3px 0;
        }

        .pos-summary-row .amount {
            font-family: var(--pos-mono);
            font-weight: 600;
            color: var(--pos-text);
        }

        .pos-grand-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--pos-primary);
            border-radius: var(--pos-radius-sm);
            padding: 14px 18px;
            margin: 12px 0;
        }

        .pos-grand-total .label {
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, .75);
        }

        .pos-grand-total .amount {
            font-family: var(--pos-mono);
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
        }

        .pos-input-group {
            margin-bottom: 10px;
        }

        .pos-input-group label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--pos-text-muted);
            margin-bottom: 6px;
        }

        .pos-cash-input {
            position: relative;
        }

        .pos-cash-input .prefix {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            font-weight: 700;
            color: var(--pos-text-muted);
        }

        .pos-cash-input input {
            width: 100%;
            padding: 12px 16px 12px 40px;
            font-family: var(--pos-mono);
            font-size: 16px;
            font-weight: 700;
            background: var(--pos-surface-alt);
            border: 2px solid var(--pos-border);
            border-radius: var(--pos-radius-sm);
            color: var(--pos-text);
            outline: none;
            transition: border-color .15s ease;
        }

        .pos-cash-input input:focus {
            border-color: var(--pos-primary);
        }

        .pos-cash-input input::placeholder {
            color: var(--pos-text-muted);
            font-weight: 500;
        }

        /* ── Quick Amounts ── */
        .pos-quick-amounts {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px;
            margin-top: 8px;
        }

        .pos-quick-btn {
            padding: 8px 4px;
            border-radius: var(--pos-radius-xs);
            border: 1px solid var(--pos-border);
            background: var(--pos-surface);
            font-family: var(--pos-mono);
            font-size: 11px;
            font-weight: 700;
            color: var(--pos-text-secondary);
            cursor: pointer;
            transition: all .15s ease;
        }

        .pos-quick-btn:hover {
            border-color: var(--pos-primary);
            color: var(--pos-primary);
            background: var(--pos-primary-light);
        }

        .pos-quick-btn.exact {
            background: var(--pos-accent);
            border-color: var(--pos-accent);
            color: #fff;
        }

        .pos-quick-btn.exact:hover {
            background: #0891b2;
        }

        /* ── Change Display ── */
        .pos-change {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border-radius: var(--pos-radius-sm);
            margin: 10px 0;
        }

        .pos-change.positive {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
        }

        .pos-change.negative {
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .dark .pos-change.positive {
            background: rgba(16, 185, 129, .1);
            border-color: rgba(16, 185, 129, .25);
        }

        .dark .pos-change.negative {
            background: rgba(239, 68, 68, .1);
            border-color: rgba(239, 68, 68, .25);
        }

        .pos-change .label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .pos-change .amount {
            font-family: var(--pos-mono);
            font-size: 16px;
            font-weight: 800;
        }

        .pos-change.positive .label,
        .pos-change.positive .amount {
            color: #059669;
        }

        .pos-change.negative .label,
        .pos-change.negative .amount {
            color: #dc2626;
        }

        .pos-note-input {
            width: 100%;
            padding: 10px 14px;
            font-size: 13px;
            border: 1px solid var(--pos-border);
            border-radius: var(--pos-radius-sm);
            background: var(--pos-surface-alt);
            color: var(--pos-text);
            outline: none;
            transition: border-color .15s ease;
        }

        .pos-note-input:focus {
            border-color: var(--pos-primary);
        }

        .pos-note-input::placeholder {
            color: var(--pos-text-muted);
        }

        .pos-action-row {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .pos-btn-clear {
            width: 48px;
            height: 48px;
            border-radius: var(--pos-radius-sm);
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: var(--pos-danger);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .15s ease;
            flex-shrink: 0;
        }

        .pos-btn-clear:hover {
            background: #fee2e2;
            border-color: #fca5a5;
        }

        .dark .pos-btn-clear {
            background: rgba(239, 68, 68, .1);
            border-color: rgba(239, 68, 68, .25);
        }

        .pos-btn-pay {
            flex: 1;
            height: 48px;
            border-radius: var(--pos-radius-sm);
            border: none;
            font-family: var(--pos-font);
            font-size: 14px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all .2s ease;
        }

        .pos-btn-pay.active {
            background: var(--pos-primary);
            color: #fff;
            box-shadow: 0 4px 16px rgba(79, 70, 229, .35);
        }

        .pos-btn-pay.active:hover {
            background: var(--pos-primary-dark);
            transform: translateY(-1px);
        }

        .pos-btn-pay.disabled {
            background: var(--pos-surface-hover);
            color: var(--pos-text-muted);
            cursor: not-allowed;
            box-shadow: none;
        }

        /* ── RIGHT PANEL ── */
        .pos-right {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: var(--pos-surface-alt);
            order: 1;
        }

        .pos-top-bar {
            background: var(--pos-surface);
            border-bottom: 1px solid var(--pos-border);
            padding: 14px 20px;
        }

        .pos-search {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            background: var(--pos-surface-alt);
            border: 2px solid var(--pos-border);
            border-radius: var(--pos-radius);
            transition: all .2s ease;
        }

        .pos-search:focus-within {
            border-color: var(--pos-primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .1);
        }

        .pos-search input {
            flex: 1;
            border: none;
            background: transparent;
            font-family: var(--pos-font);
            font-size: 14px;
            color: var(--pos-text);
            outline: none;
        }

        .pos-search input::placeholder {
            color: var(--pos-text-muted);
        }

        .pos-search .clear-btn {
            background: none;
            border: none;
            color: var(--pos-text-muted);
            cursor: pointer;
            padding: 2px;
            transition: color .15s;
        }

        .pos-search .clear-btn:hover {
            color: var(--pos-text);
        }

        /* ── Tabs ── */
        .pos-tabs {
            display: flex;
            gap: 6px;
            margin-top: 12px;
            align-items: center;
        }

        .pos-tab {
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all .15s ease;
        }

        .pos-tab.inactive {
            background: transparent;
            color: var(--pos-text-muted);
        }

        .pos-tab.inactive:hover {
            background: var(--pos-surface-hover);
            color: var(--pos-text);
        }

        .pos-tab.all {
            background: var(--pos-primary);
            color: #fff;
        }

        .pos-tab.gudang-a {
            background: #0d9488;
            color: #fff;
        }

        .pos-tab.gudang-b {
            background: #e11d48;
            color: #fff;
        }

        .pos-product-count {
            margin-left: auto;
            font-size: 12px;
            font-weight: 600;
            color: var(--pos-text-muted);
        }

        /* ── Product Grid ── */
        .pos-grid-wrap {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .pos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
        }

        @media (min-width: 1440px) {
            .pos-grid {
                grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            }
        }

        @media (min-width: 1920px) {
            .pos-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        /* ── Product Card ── */
        .pos-card {
            position: relative;
            background: var(--pos-surface);
            border-radius: var(--pos-radius);
            border: 1px solid var(--pos-border);
            overflow: hidden;
            cursor: pointer;
            transition: all .2s ease;
            user-select: none;
        }

        .pos-card:hover {
            border-color: var(--pos-primary);
            box-shadow: var(--pos-shadow-lg);
            transform: translateY(-2px);
        }

        .pos-card:active {
            transform: scale(.97);
        }

        .pos-card-img {
            position: relative;
            aspect-ratio: 1;
            overflow: hidden;
            background: linear-gradient(135deg, var(--pos-surface-alt), var(--pos-surface-hover));
        }

        .pos-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .pos-card:hover .pos-card-img img {
            transform: scale(1.08);
        }

        .pos-card-img .no-img {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            color: var(--pos-text-muted);
        }

        .pos-card-img .no-img span {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .pos-card-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.03em;
            padding: 3px 8px;
            border-radius: 6px;
            color: #fff;
            text-transform: uppercase;
        }

        .pos-card-badge.ga {
            background: #0d9488;
        }

        .pos-card-badge.gb {
            background: #e11d48;
        }

        .pos-card-qty-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            min-width: 24px;
            height: 24px;
            padding: 0 6px;
            border-radius: 100px;
            background: var(--pos-primary);
            color: #fff;
            font-family: var(--pos-mono);
            font-size: 11px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(79, 70, 229, .4);
        }

        .pos-card-overlay {
            position: absolute;
            inset: 0;
            background: rgba(79, 70, 229, 0);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .2s ease;
        }

        .pos-card:hover .pos-card-overlay {
            background: rgba(79, 70, 229, .08);
        }

        .pos-card-add-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--pos-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(79, 70, 229, .4);
            opacity: 0;
            transform: scale(.5);
            transition: all .2s ease;
        }

        .pos-card:hover .pos-card-add-icon {
            opacity: 1;
            transform: scale(1);
        }

        .pos-card-body {
            padding: 12px 14px 14px;
        }

        .pos-card-name {
            font-size: 13px;
            font-weight: 800;
            color: var(--pos-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }

        .pos-card-meta {
            font-size: 11px;
            color: var(--pos-text-muted);
            margin-top: 2px;
        }

        .pos-card-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-top: 8px;
        }

        .pos-card-price {
            font-family: var(--pos-mono);
            font-size: 14px;
            font-weight: 800;
            color: var(--pos-primary);
            letter-spacing: -0.02em;
        }

        .pos-card-stock {
            font-size: 10px;
            font-weight: 600;
            color: var(--pos-text-muted);
            background: var(--pos-surface-alt);
            padding: 2px 8px;
            border-radius: 100px;
        }

        /* In-cart indicator */
        .pos-card.in-cart {
            border-color: var(--pos-primary);
        }

        .pos-card.in-cart::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--pos-primary);
        }

        /* ── Empty State ── */
        .pos-empty {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 80px 20px;
            color: var(--pos-text-muted);
            text-align: center;
        }

        .pos-empty .icon-ring {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--pos-surface);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .pos-empty h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--pos-text-secondary);
            margin: 0;
        }

        .pos-empty p {
            font-size: 13px;
            margin-top: 4px;
        }
    </style>

    <div class="pos-wrap">

        {{-- ============================================================ --}}
        {{-- LEFT  –  Order Panel                                         --}}
        {{-- ============================================================ --}}
        <div class="pos-left">

            {{-- Header --}}
            <div class="pos-left-header">
                <div>
                    <p class="label">Order Baru</p>
                    <p class="value">{{ auth()->user()->name }}</p>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="label" style="margin:0;">{{ now()->format('d M Y') }}</span>
                    <span class="clock" id="clock">{{ now()->format('H:i:s') }}</span>
                </div>
            </div>

            {{-- Lokasi Penjualan --}}
            <div style="padding:10px 16px; border-bottom:1px solid var(--pos-border); background:var(--pos-surface-alt);">
                @if(auth()->user()?->gudang_id)
                    <div style="width:100%; padding:8px 12px; border-radius:8px; border:1px solid var(--pos-border); background:var(--pos-surface); color:var(--pos-text); font-size:13px; font-weight:500; display:flex; align-items:center; gap:6px;">
                        🏭 {{ auth()->user()->gudang->nama }}
                    </div>
                @else
                    <select wire:model.live="gudangId"
                        style="width:100%; padding:8px 12px; border-radius:8px; border:1px solid var(--pos-border); background:var(--pos-surface); color:var(--pos-text); font-size:13px; font-weight:500; cursor:pointer;">
                        <option value="">— Pilih Lokasi Penjualan —</option>
                        @foreach(\App\Models\Gudang::where('aktif', true)->orderByRaw("FIELD(tipe,'toko','gudang')")->get() as $g)
                            <option value="{{ $g->id }}">{{ $g->tipe === 'toko' ? '🏪' : '🏭' }} {{ $g->nama }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            {{-- Cart Items --}}
            <div class="pos-cart pos-scroll">
                @forelse($cart as $key => $item)
                    <div wire:key="ci-{{ $key }}" class="pos-cart-item">

                        {{-- Thumbnail --}}
                        <div class="pos-cart-thumb">
                            @if ($item['foto'])
                                <img src="{{ Storage::disk('public')->url($item['foto']) }}" alt="" />
                            @else
                                <div class="placeholder">
                                    <svg width="20" height="20" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="pos-cart-info">
                            <p class="name">{{ $item['nama_produk'] }}</p>
                            <p class="meta">{{ $item['ukuran'] }}</p>
                            <p class="price-unit">Rp {{ number_format($item['harga_karton'], 0, ',', '.') }}/karton</p>
                        </div>

                        {{-- Qty + Subtotal --}}
                        <div class="pos-cart-actions">
                            <div class="pos-qty-group">
                                <button wire:click="decrementQty('{{ $key }}')"
                                    class="pos-qty-btn danger">−</button>
                                <span class="pos-qty-value">{{ $item['jumlah'] }}</span>
                                <button wire:click="incrementQty('{{ $key }}')" class="pos-qty-btn">+</button>
                            </div>
                            <span class="pos-cart-subtotal">Rp
                                {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="pos-cart-empty">
                        <div class="icon-ring">
                            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                            </svg>
                        </div>
                        <h4>Keranjang kosong</h4>
                        <p>Pilih produk dari panel kanan</p>
                    </div>
                @endforelse
            </div>

            {{-- Payment Panel --}}
            <div class="pos-payment">

                {{-- Summary --}}
                <div class="pos-summary-row">
                    <span>Item ({{ $this->totalItems }} karton)</span>
                    <span class="amount">Rp {{ number_format($this->totalHarga, 0, ',', '.') }}</span>
                </div>
                <div class="pos-summary-row">
                    <span>Diskon</span>
                    <span class="amount">Rp 0</span>
                </div>

                {{-- Grand Total --}}
                <div class="pos-grand-total">
                    <span class="label">Grand Total</span>
                    <span class="amount">Rp {{ number_format($this->totalHarga, 0, ',', '.') }}</span>
                </div>

                {{-- Cash Input --}}
                <div class="pos-input-group">
                    <label>Uang Diterima</label>
                    <div class="pos-cash-input">
                        <span class="prefix">Rp</span>
                        <input type="number" wire:model.live="totalBayar" placeholder="0" />
                    </div>
                    <div class="pos-quick-amounts">
                        @foreach ([50000, 100000, 200000, 500000] as $nom)
                            <button wire:click="setNominal({{ $nom }})" class="pos-quick-btn">
                                {{ $nom >= 1000 ? number_format($nom / 1000, 0) . 'rb' : $nom }}
                            </button>
                        @endforeach
                        <button wire:click="setExact" class="pos-quick-btn exact">Pas</button>
                    </div>
                </div>

                {{-- Change --}}
                @if ($this->bayarInt > 0)
                    <div class="pos-change {{ $this->kembalian >= 0 ? 'positive' : 'negative' }}">
                        <span class="label">{{ $this->kembalian >= 0 ? 'Kembalian' : 'Kurang' }}</span>
                        <span class="amount">Rp {{ number_format(abs($this->kembalian), 0, ',', '.') }}</span>
                    </div>
                @endif

                {{-- Metode Pembayaran --}}
                <div style="display:flex; gap:8px; margin-bottom:8px;">
                    <button wire:click="$set('metodePembayaran','cash')"
                        style="flex:1; padding:9px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; border:2px solid {{ $metodePembayaran === 'cash' ? 'var(--pos-primary)' : 'var(--pos-border)' }}; background:{{ $metodePembayaran === 'cash' ? 'var(--pos-primary-light)' : 'var(--pos-surface)' }}; color:{{ $metodePembayaran === 'cash' ? 'var(--pos-primary)' : 'var(--pos-text-secondary)' }}; transition:.15s;">
                        💵 Cash
                    </button>
                    <button wire:click="$set('metodePembayaran','transfer')"
                        style="flex:1; padding:9px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; border:2px solid {{ $metodePembayaran === 'transfer' ? 'var(--pos-primary)' : 'var(--pos-border)' }}; background:{{ $metodePembayaran === 'transfer' ? 'var(--pos-primary-light)' : 'var(--pos-surface)' }}; color:{{ $metodePembayaran === 'transfer' ? 'var(--pos-primary)' : 'var(--pos-text-secondary)' }}; transition:.15s;">
                        🏦 Transfer
                    </button>
                </div>

                {{-- Nama Pembeli --}}
                <input type="text" wire:model.live="namaPembeli" placeholder="Nama pembeli (wajib)..."
                    class="pos-note-input" style="margin-bottom:6px;" required />

                {{-- Notes --}}
                <input type="text" wire:model.live="catatan" placeholder="Catatan transaksi (opsional)..."
                    class="pos-note-input" />

                {{-- Actions --}}
                <div class="pos-action-row">
                    <button wire:click="clearCart" class="pos-btn-clear">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>

                    <button wire:click="prosesTransaksi" wire:loading.attr="disabled" @disabled(empty($cart) || $this->bayarInt < $this->totalHarga)
                        class="pos-btn-pay {{ !empty($cart) && $this->bayarInt >= $this->totalHarga ? 'active' : 'disabled' }}">
                        <span wire:loading.remove wire:target="prosesTransaksi"
                            style="display:flex;align-items:center;gap:8px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Proses Pembayaran
                        </span>
                        <span wire:loading wire:target="prosesTransaksi"
                            style="display:flex;align-items:center;gap:8px;">
                            <svg width="16" height="16" style="animation:spin 1s linear infinite"
                                fill="none" viewBox="0 0 24 24">
                                <circle opacity=".25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path opacity=".75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>

            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- RIGHT  –  Product Catalog                                    --}}
        {{-- ============================================================ --}}
        <div class="pos-right">

            {{-- Top Bar --}}
            <div class="pos-top-bar">

                {{-- Search --}}
                <div class="pos-search">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="flex-shrink:0;color:var(--pos-text-muted)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                    </svg>
                    <input type="text" wire:model.live.debounce.250ms="search"
                        placeholder="Cari produk berdasarkan merk atau ukuran..." />
                    @if ($search)
                        <button wire:click="$set('search', '')" class="clear-btn">
                            <svg width="16" height="16" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>

                {{-- Tabs --}}
                <div class="pos-tabs">
                    @if(auth()->user()?->isSuperAdmin())
                        <button wire:click="setTab('semua')"
                            class="pos-tab {{ $activeTab === 'semua' ? 'all' : 'inactive' }}">
                            Semua Produk
                        </button>
                        @foreach ($this->gudangs as $gudang)
                            <button wire:click="setTab('{{ $gudang->nama }}')"
                                class="pos-tab {{ $activeTab === $gudang->nama ? 'gudang-a' : 'inactive' }}">
                                {{ $gudang->nama }}
                            </button>
                        @endforeach
                    @endif
                    <span class="pos-product-count">{{ $this->products->count() }} produk</span>
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="pos-grid-wrap pos-scroll">
                <div class="pos-grid">

                    @forelse($this->products as $product)
                        <div wire:key="p-{{ $product->id }}" wire:click="addToCart({{ $product->id }})"
                            class="pos-card {{ isset($cart[(string) $product->id]) ? 'in-cart' : '' }}">

                            {{-- Image --}}
                            <div class="pos-card-img">
                                @if ($product->foto)
                                    <img src="{{ Storage::disk('public')->url($product->foto) }}" alt="{{ $product->merk }}" />
                                @else
                                    <div class="no-img">
                                        <svg width="36" height="36" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>No Image</span>
                                    </div>
                                @endif

                                {{-- Gudang Badge --}}
                                @if ($product->gudang)
                                    <span
                                        class="pos-card-badge {{ $product->gudang->nama === 'Gudang A' ? 'ga' : 'gb' }}">
                                        {{ $product->gudang->nama }}
                                    </span>
                                @endif

                                {{-- Cart Qty Badge --}}
                                @if (isset($cart[(string) $product->id]))
                                    <div class="pos-card-qty-badge">
                                        {{ $cart[(string) $product->id]['jumlah'] }}
                                    </div>
                                @endif

                                {{-- Hover Overlay --}}
                                <div class="pos-card-overlay">
                                    <div class="pos-card-add-icon">
                                        <svg width="22" height="22" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Body --}}
                            <div class="pos-card-body">
                                <h3 class="pos-card-name">{{ $product->merk }}</h3>
                                <p class="pos-card-meta">
                                    {{ $product->ukuran }}
                                    @if ($product->pcs_per_karton)
                                        · {{ $product->pcs_per_karton }} pcs/krt
                                    @endif
                                </p>
                                <div class="pos-card-footer">
                                    <span class="pos-card-price">Rp
                                        {{ number_format($product->harga_karton, 0, ',', '.') }}</span>
                                    <span class="pos-card-stock">Stok {{ $product->stok_karton }} krt</span>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="pos-empty">
                            <div class="icon-ring">
                                <svg width="40" height="40" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                                </svg>
                            </div>
                            <h4>Tidak ada produk</h4>
                            <p>
                                @if ($search)
                                    Tidak ada produk yang cocok dengan "{{ $search }}"
                                @else
                                    Tambahkan produk terlebih dahulu di menu Barang Masuk
                                @endif
                            </p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

    </div>

    <script>
        function updateClock() {
            const el = document.getElementById('clock');
            if (!el) return;
            el.textContent = new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
        setInterval(updateClock, 1000);
    </script>
    {{-- 
    @keyframes spin { to { transform: rotate(360deg); } } --}}

</x-filament-panels::page>
