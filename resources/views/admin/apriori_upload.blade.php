@extends('layouts.app_admin')

@section('content')
    <div class="modern-bg">
        <div class="container py-5">
            <div class="row g-4 align-items-start">
                <!-- Form Upload -->
                <div class="col-lg-5 col-md-6">
                    <div class="card shadow-3d glass-card">
                        <div class="card-header gradient-primary text-white text-center py-3 rounded-top-4">
                            <h4 class="fw-bold mb-0">📂 Upload Dataset Apriori</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.apriori.upload.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label for="dataset" class="form-label fw-semibold text-primary">Pilih File (Excel /
                                        CSV / TXT)</label>
                                    <input type="file" class="form-control custom-input" id="dataset" name="dataset"
                                        required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="minsup" class="form-label fw-semibold text-primary">Minimum Support
                                            (%)</label>
                                        <input type="number" class="form-control custom-input" id="minsup"
                                            name="minsup" min="1" max="100" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="minconf" class="form-label fw-semibold text-primary">Minimum Confidence
                                            (%)</label>
                                        <input type="number" class="form-control custom-input" id="minconf"
                                            name="minconf" min="1" max="100" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn gradient-btn btn-lg w-100 mt-2">🚀 Proses Apriori</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Output Hasil -->
                <div class="col-lg-7 col-md-6">
                    @if (session('output'))
                        <div
                            class="alert alert-{{ session('saved') ? 'success' : 'danger' }} custom-alert mb-3 text-center fw-bold">
                            {{ session('output') }}
                        </div>

                        @if (session('minsup') && session('minconf'))
                            <div class="card shadow-3d glass-card mb-4">
                                <div class="card-header gradient-success text-white text-center py-2 rounded-top-4">
                                    <h5 class="fw-bold mb-0">Parameter Apriori</h5>
                                </div>
                                <div class="card-body text-center fs-5">
                                    <p><strong>Minimum Support:</strong> <span
                                            class="text-success">{{ session('minsup') }}%</span></p>
                                    <p><strong>Minimum Confidence:</strong> <span
                                            class="text-success">{{ session('minconf') }}%</span></p>
                                </div>
                            </div>
                        @endif

                        @if (session('groupedItemsets'))
                            @foreach (session('groupedItemsets') as $k => $itemsets)
                                <div class="card shadow-3d glass-card mb-3">
                                    <div class="card-header gradient-info text-white text-center py-2 rounded-top-4">
                                        <h5 class="fw-bold mb-0">Frequent Itemsets - Kombinasi {{ $k }}</h5>
                                    </div>
                                    <div class="card-body table-responsive custom-scroll">
                                        <table class="table table-hover align-middle text-center">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="fw-bold text-primary">Itemset</th>
                                                    <th class="fw-bold text-primary">Support (%)</th>
                                                    <th class="fw-bold text-primary">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($itemsets as $itemset)
                                                    <tr class="hover-row">
                                                        <td>{{ $itemset['items'] }}</td>
                                                        <td class="text-success fw-bold">{{ $itemset['support'] }}%</td>
                                                        <td class="text-primary fw-bold">{{ $itemset['count'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if (session('rules'))
                            <div class="card shadow-3d glass-card mb-3">
                                <div class="card-header gradient-purple text-white text-center py-2 rounded-top-4">
                                    <h5 class="fw-bold mb-0">Association Rules (Confidence ≥ {{ session('minconf') }}%)
                                    </h5>
                                </div>
                                <div class="card-body table-responsive custom-scroll">
                                    <table class="table table-hover align-middle text-center">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="fw-bold text-primary">Rule</th>
                                                <th class="fw-bold text-primary">Support (%)</th>
                                                <th class="fw-bold text-primary">Confidence (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (session('rules') as $rule)
                                                <tr class="hover-row">
                                                    <td>{{ implode(', ', $rule['antecedent']) }} →
                                                        {{ implode(', ', $rule['consequent']) }}</td>
                                                    <td class="text-success fw-bold">{{ $rule['support'] }}%</td>
                                                    <td class="text-warning fw-bold">{{ $rule['confidence'] }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .modern-bg {
            min-height: 100vh;
            background: linear-gradient(135deg, #4a90e2, #007bff, #6f42c1);
            background-size: 400% 400%;
            animation: gradientBG 12s ease infinite;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease-in-out;
        }

        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .gradient-btn {
            background: linear-gradient(135deg, #007bff, #00d4ff);
            border: none;
            color: white;
            padding: 14px;
            font-weight: bold;
            font-size: 1.1rem;
            border-radius: 14px;
            transition: all 0.3s ease-in-out;
        }

        .gradient-btn:hover {
            background: linear-gradient(135deg, #00d4ff, #007bff);
            transform: scale(1.05);
        }

        .custom-input {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #ccc;
        }

        .custom-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.6);
        }

        .custom-scroll {
            max-height: 260px;
            overflow-y: auto;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: rgba(0, 123, 255, 0.4);
            border-radius: 10px;
        }
    </style>
@endsection
