@extends('layouts.app')
@section('title', 'Administration place')
@section('content')<section class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

        <div class="error-page">
            <h2 class="headline text-warning">403</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! You don't have permission.</h3>

                <p>
                    Your current role has no enought permission to do this,
                    Meanwhile, you may <a href="{{ route('home') }}">return to dashboard</a> or try using the search form.
                </p>

                <form class="search-form">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search">

                        <div class="input-group-append">
                            <button disabled type="submit" name="submit" class="btn btn-warning"><i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.input-group -->
                </form>
            </div>
        </div>
        <!-- /.error-page -->

@endsection
