<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    // GET ALL MENU ITEMS
    public function index(Request $request)
    {
        $query = MenuItem::query();

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get());
    }

    // GET SINGLE ITEM
    public function show($id)
    {
        $item = MenuItem::findOrFail($id);
        return response()->json($item);
    }

    // CREATE ITEM (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'price'    => 'required|numeric',
            'category' => 'required|string'
        ]);

        $item = MenuItem::create($request->all());
        return response()->json([
            'message' => 'Menu item created',
            'item'    => $item
        ], 201);
    }

    // UPDATE ITEM (admin)
    public function update(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);
        $item->update($request->all());
        return response()->json([
            'message' => 'Menu item updated',
            'item'    => $item
        ]);
    }

    // DELETE ITEM (admin)
    public function destroy($id)
    {
        MenuItem::findOrFail($id)->delete();
        return response()->json(['message' => 'Menu item deleted']);
    }
}