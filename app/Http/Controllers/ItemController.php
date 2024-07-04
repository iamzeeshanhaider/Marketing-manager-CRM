<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    function index()
    {
        $items = Items::all();
        return view('items.index', compact('items'));
    }

    function create()
    {
        $item = null;
        return view('items.create', compact('item'));
    }

    function edit(Items $item)
    {
        return view('items.create', compact('item'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required',
        ]);

        try {
            DB::beginTransaction();

            Items::create(ItemResource::sanitizeResponse($request));

            DB::commit();

            return redirect()->route('items.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function update(Request $request, Items $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $item->update(ItemResource::sanitizeResponse($request));

            DB::commit();

            return redirect()->route('items.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }




    public function destroy(Items $item)
    {

        if ($item->delete()) {
            return redirect()->back()->with('success', 'Operation Successful');
        } else {
            return redirect()->back()->with('error', 'Unable to perform operation');
        }
    }
}
