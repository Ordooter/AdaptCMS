<?php

namespace App\Modules\Posts\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Category;

use Auth;
use Validator;

class CategoriesController extends Controller
{
    /**
    * Index
    *
    * @return View
    */
    public function index()
    {
        $items = Category::orderBy('ord', 'ASC')->paginate(15);

        return view('posts::Admin/Categories/index', compact('items'));
    }

    /**
    * Add
    *
    * @param Request $request
    *
    * @return mixed
    */
    public function add(Request $request)
    {
        $model = new Category;

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
                'name' => 'required|unique:pages|max:255'
            ]);

            $data = $request->all();

            $data['user_id'] = Auth::user()->id;

            $model->add($data);

            return redirect()->route('admin.categories.index')->with('success', 'Category has been saved');
        }

        return view('posts::Admin/Categories/add', compact('model'));
    }

    /**
    * Edit
    *
    * @param Request $request
    * @param integer $id
    *
    * @return View
    */
    public function edit(Request $request, $id)
    {
        $model = Category::find($id);

        if (empty($model)) {
            abort(404);
        }

        $errors = [];
        if ($request->getMethod() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    Rule::unique('categories')->ignore($model->id)
                ]
            ]);

            if (!$validator->fails()) {
                $data = $request->all();

                $data['user_id'] = Auth::user()->id;

                $model->edit($data);

                return redirect()->route('admin.categories.index')->with('success', 'Category has been saved');
            } else {
                $errors = $validator->errors()->getMessages();
            }
        }

        return view('posts::Admin/Categories/edit', compact('model', 'errors'));
    }

    /**
    * Delete
    *
    * @param integer $id
    *
    * @return Redirect
    */
    public function delete($id)
    {
        $model = Category::find($id);

        if (empty($model)) {
            abort(404);
        }

        $model->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category has been saved');
    }

    /**
    * Order
    *
    * @param Request $request
    *
    * @return View|string
    */
    public function order(Request $request)
    {
        $items = Category::orderBy('ord', 'ASC')->get();

        if ($request->getMethod() == 'POST') {
            $items = json_decode($request->get('items'), true);

            Category::setNewOrder($items);

            return response()->json([
                'status' => true
            ]);
        }

        return view('posts::Admin/Categories/order', compact('items'));
    }

    /**
    * Simple Save
    *
    * @param Request $request
    *
    * @return string
    */
    public function simpleSave(Request $request)
    {
        $model = new Category;

        $response = $model->simpleSave($request->all());

        return response()->json($response);
    }
}
