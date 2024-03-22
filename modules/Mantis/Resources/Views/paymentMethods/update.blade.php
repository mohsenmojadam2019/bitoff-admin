@extends('layouts.app')
@section('title', $paymentMethod->name)
@section('content')

    <div class="card">
        <div class="card-body">
            @if($paymentMethod->isParent())
                <div class="alert alert-warning" role="alert">Be carefully !! This is parent payment method</div>
            @endif
            <div class="row">
                <div class="col col-lg-12">
                    <form action="{{ route('mantis.payment_methods.update', $paymentMethod->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="form-group">
                            <label for="amount">Name</label>
                            <input autocomplete="off" type="text" name="name" id="name"
                                   class="form-control {{ $errors->has('name') ? 'is-invalid' : null }}"
                                   value="{{ $paymentMethod->name }}"
                                   placeholder="write a name">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    <ul>@foreach($errors->get('name') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach</ul>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="amount">Parent</label>
                            <select name="parent_id" id="parentId"
                                    class="form-control {{ $errors->has('parent_id') ? 'is-invalid' : null }}"
                                    placeholder="Select a Parent" style="width: 100%;">
                                <option value="">This is parent</option>
                                @foreach (\Bitoff\Mantis\Application\Models\PaymentMethod::whereNull('parent_id')->get() as $parent)
                                    <option
                                        value="{{ $parent->id }}" {{ $paymentMethod->parent_id == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('parent_id'))
                                <div class="invalid-feedback">
                                    <ul>@foreach($errors->get('parent_id') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach</ul>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="fee">Fee</label>
                            <input autocomplete="off" type="number" name="fee" id="fee" min="0"
                                   class="form-control {{ $errors->has('fee') ? 'is-invalid' : null }}"
                                   value="{{ $paymentMethod->fee }}"
                                   placeholder="0">
                            @if($errors->has('fee'))
                                <div class="invalid-feedback">
                                    <ul>@foreach($errors->get('fee') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach</ul>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col col-lg-4">
                                <div class="form-group">
                                    <label for="time">Default time</label>
                                    <input autocomplete="off" type="number" name="time" id="time"
                                           class="form-control {{ $errors->has('time') ? 'is-invalid' : null }}"
                                           value="{{ $paymentMethod->time }}"
                                           placeholder="0">
                                    @if($errors->has('time'))
                                        <div class="invalid-feedback">
                                            <ul>@foreach($errors->get('time') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach</ul>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col col-lg-4">
                                <div class="form-group">
                                    <label for="min_time">Min time</label>
                                    <input autocomplete="off" type="number" name="min_time" id="min_time"
                                           class="form-control {{ $errors->has('min_time') ? 'is-invalid' : null }}"
                                           value="{{ $paymentMethod->min_time }}"
                                           placeholder="0">
                                    @if($errors->has('min_time'))
                                        <div class="invalid-feedback">
                                            <ul>@foreach($errors->get('min_time') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach</ul>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col col-lg-4">
                                <div class="form-group">
                                    <label for="max_time">Max time</label>
                                    <input autocomplete="off" type="number" name="max_time" id="max_time"
                                           class="form-control {{ $errors->has('max_time') ? 'is-invalid' : null }}"
                                           value="{{ $paymentMethod->max_time }}"
                                           placeholder="0">
                                    @if($errors->has('max_time'))
                                        <div class="invalid-feedback">
                                            <ul>@foreach($errors->get('max_time') as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach</ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group select2-purple">
                            <label for="amount">Currency</label>
                            <select name="currencies[]" class="select2" id="currencies"
                                    multiple="multiple" data-placeholder="Select one or more"
                                    tabindex="-1" style="width: 100%;">
                                @foreach (\Bitoff\Mantis\Application\Models\Currency::all() as $currency)
                                    <option
                                        value="{{ $currency->id }}" {{ $paymentMethod->hasCurrency($currency->id) ? 'selected' : '' }}>
                                        {{ $currency->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('currencies'))
                                <div class="invalid-feedback">
                                    <ul>@foreach($errors->get('currencies') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach</ul>
                                </div>
                            @endif
                        </div>
                        <div class="form-group select2-purple">
                            <label for="amount">Tags</label>
                            <select name="tags[]" class="select2" id="tags"
                                    multiple="multiple" data-placeholder="Select one or more"
                                    tabindex="-1" style="width: 100%;">
                                @foreach (\Bitoff\Mantis\Application\Models\Tag::all() as $tag)
                                    <option
                                        value="{{ $tag->id }}" {{ $paymentMethod->hasTag($tag->id) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('tags'))
                                <div class="invalid-feedback">
                                    <ul>@foreach($errors->get('tags') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach</ul>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="form-group">
                                <label for="icon">Icon</label>
                                <input type="file" name="icon" class="form-control">
                                @if($errors->has('icon'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('icon') }}
                                    </div>
                                @endif
                            </div>
                            @if($paymentMethod->getFirstMediaUrl('icon'))
                                <img src="{{ $paymentMethod->getFirstMediaUrl('icon') }}" style="width: 100px" alt="{{ $paymentMethod->getFirstMedia('icon')->name}}">
                            @endif
                        </div>

                        <div class="modal-footer justify-content-between">
                            <a href="{{ route('mantis.payment_methods.index') }}">
                                <button type="button" class="btn btn-danger">Cancel</button>
                            </a>

                            <div class="d-flex justify-content-between align-items-center align-content-center">
                                @if($paymentMethod->isParent())
                                    <div class="custom-control custom-switch mr-2">
                                        <input type="checkbox" class="custom-control-input" id="is_apply_to_children"
                                               name="is_apply_to_children">
                                        <label class="custom-control-label" for="is_apply_to_children">Apply to children
                                            ?</label>
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
