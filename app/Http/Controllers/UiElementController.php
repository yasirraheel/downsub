<?php

namespace App\Http\Controllers;

use App\Models\UiElement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UiElementController extends Controller
{
    public function index()
    {
        $elements = UiElement::latest()->get();
        return view('admin.ui-elements', compact('elements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_name' => 'nullable|string|max:255',
            'html_code' => 'required|string',
            'css_code' => 'required|string',
        ]);

        UiElement::create($request->all());

        return redirect()->route('admin.ui-elements')->with('success', 'UI Element added successfully!');
    }

    public function destroy($id)
    {
        $element = UiElement::findOrFail($id);
        $element->delete();

        return redirect()->route('admin.ui-elements')->with('success', 'UI Element deleted successfully!');
    }

    public function customCss()
    {
        $elements = UiElement::all();
        $css = "";

        foreach ($elements as $element) {
            $css .= "/* Element: {$element->name} */\n";
            $css .= $element->css_code . "\n\n";
        }

        return response($css)->header('Content-Type', 'text/css');
    }
}
