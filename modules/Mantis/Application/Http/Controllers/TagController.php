<?php

namespace Bitoff\Mantis\Application\Http\Controllers;

use Bitoff\Mantis\Application\Http\Requests\TagStoreRequest;
use Bitoff\Mantis\Application\Models\Tag;
use Illuminate\Routing\Controller;

class TagController extends Controller
{
    public function store(TagStoreRequest $request)
    {
        Tag::create(['name'=>$request->name]);

        return back();
    }
}
