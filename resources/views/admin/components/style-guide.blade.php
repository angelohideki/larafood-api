{{-- Admin Style Guide for LaraFood API --}}
@extends('adminlte::page')

@section('title', 'Style Guide')

@section('content_header')
    <h1>LaraFood - Style Guide</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Standardized Components</h3>
            </div>
            <div class="card-body">
                
                {{-- Buttons --}}
                <h4 class="mb-3">Buttons</h4>
                <div class="mb-4">
                    <button class="btn btn-primary me-2"><i class="fas fa-plus"></i> Primary Action</button>
                    <button class="btn btn-outline-primary me-2"><i class="fas fa-edit"></i> Secondary</button>
                    <button class="btn btn-success me-2"><i class="fas fa-check"></i> Success</button>
                    <button class="btn btn-warning me-2"><i class="fas fa-eye"></i> View</button>
                    <button class="btn btn-danger me-2"><i class="fas fa-trash"></i> Delete</button>
                </div>

                {{-- Form Elements --}}
                <h4 class="mb-3">Form Elements</h4>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="example-input" class="form-label">Standard Input</label>
                            <input type="text" class="form-control" id="example-input" placeholder="Enter text...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="example-select" class="form-label">Select Dropdown</label>
                            <select class="form-control" id="example-select">
                                <option>Choose option...</option>
                                <option>Option 1</option>
                                <option>Option 2</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Cards --}}
                <h4 class="mb-3">Card Layouts</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Standard Card</h3>
                            </div>
                            <div class="card-body">
                                Card content goes here
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-gradient-info">
                            <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Statistics Card</span>
                                <span class="info-box-number">1,234</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop