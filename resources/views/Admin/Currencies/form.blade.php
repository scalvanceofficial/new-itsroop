@extends('layouts.admin')
@section('title')
    {{ isset($currency) ? 'Edit' : 'Create' }} Currency
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header px-4 py-3 border-bottom">
                        <h5 class="card-title fw-semibold mb-0">{{ isset($currency) ? 'Edit' : 'Create' }} Currency</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ isset($currency) ? route('admin.currencies.update', $currency->id) : route('admin.currencies.store') }}" method="POST">
                            @csrf
                            @if(isset($currency))
                                @method('PUT')
                            @endif
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Code (e.g. USD)</label>
                                    <input type="text" name="code" class="form-control" value="{{ old('code', $currency->code ?? '') }}" required>
                                    @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name (e.g. US Dollar)</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $currency->name ?? '') }}" required>
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Symbol (e.g. $)</label>
                                    <input type="text" name="symbol" class="form-control" value="{{ old('symbol', $currency->symbol ?? '') }}" required>
                                    @error('symbol') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Exchange Rate (Against GBP)</label>
                                    <input type="number" step="0.00000001" name="exchange_rate" class="form-control" value="{{ old('exchange_rate', $currency->exchange_rate ?? '1.0') }}" required>
                                    @error('exchange_rate') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
