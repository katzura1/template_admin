<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        // $data = Menu::with('parent')->select('menus.id', 'menus.name')->paginate(10)->toArray();
        // dd($data);
        $parents = Menu::select('id', 'name')->where('type', 'parent')->get();
        return view('pages.menu.index')->with(['parents' => $parents]);
    }

    public function data(Request $request)
    {
        $search = $request->search['value'];
        //search param
        $searchParam = [
            ['menus.name', 'LIKE', '%' . $search . '%']
        ];
        //get request page
        $request['page'] = $request->start == 0 ? 1 : round(($request->start + $request->length) / $request->length);
        //get data 
        $datas = Menu::with('parent')->where($searchParam)->paginate($request->length ?? 10)->toArray();
        $final['draw'] = $request['draw'];
        $final['recordsTotal'] = $datas['total'];
        $final['recordsFiltered'] = $datas['total'];
        $final['data'] = $datas['data'];
        return response()->json($final, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:menus',
            'slug' => 'required|string|max:100',
            'type' => 'required|in:parent,child',
        ]);

        $validator->sometimes('id_parent', 'required|integer|min:0', function ($input) {
            return $input->type == 'child';
        });


        if ($validator->fails()) {
            return ResponseFormatter::error(500, implode('<br>', $validator->errors()->all()));
        }
        // Retrieve the validated input...
        $validated = $validator->validated();
        try {
            DB::beginTransaction();
            $menu = Menu::create($validated);
            DB::commit();
            return ResponseFormatter::success('Success save data', $menu);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error(500, $e->getMessage(), $validated);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:menus,id',
            'name' => 'required|unique:menus,name,' . $request->id,
            'slug' => 'required|string|max:100',
            'type' => 'required|in:parent,child',
        ]);

        $validator->sometimes('id_parent', 'required|integer|min:0', function ($input) {
            return $input->type == 'child';
        });


        if ($validator->fails()) {
            return ResponseFormatter::error(500, implode('<br>', $validator->errors()->all()), $request->all());
        }
        // Retrieve the validated input...
        $validated = $validator->safe()->except(['id']);
        $validated['type'] == 'parent' && $validated['id_parent'] = null;
        try {
            DB::beginTransaction();
            $menu = Menu::where('id', $validator->safe()->only(['id']))->update($validated);
            if ($menu) {
                DB::commit();
                return ResponseFormatter::success('Success save data', $menu);
            }
            DB::rollBack();
            return ResponseFormatter::error(404, 'Data not valid');
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error(500, $e->getMessage(), $validated);
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:menus,id',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(500, implode('<br>', $validator->errors()->all()), $request->all());
        }
        try {
            DB::beginTransaction();
            $menu = Menu::where('id', $validator->safe()->only(['id']))->delete();
            if ($menu) {
                DB::commit();
                return ResponseFormatter::success('Success delete data', $menu);
            }
            DB::rollBack();
            return ResponseFormatter::error(404, 'Data not valid');
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error(500, $e->getMessage());
        }
    }
}
