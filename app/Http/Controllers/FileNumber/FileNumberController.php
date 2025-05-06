<?php

namespace App\Http\Controllers\FileNumber;

use App\Http\Controllers\Controller;
use App\Models\FileNumber;
use Illuminate\Http\Request;

class FileNumberController extends Controller
{
    public function create()
    {
        return view('Pages.FileNumbers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_code' => 'required|string|unique:file_numbers,file_code',
            'adult_limit' => 'required|integer|min:0',
            'child_limit' => 'required|integer|min:0',
        ]);

        FileNumber::create($request->only('file_code', 'adult_limit', 'child_limit'));

        return redirect()->route('file_numbers.create')->with('success', 'تم إضافة رقم الملف بنجاح');
    }

}
