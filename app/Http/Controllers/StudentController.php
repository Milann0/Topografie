<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::all();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
            ]);

            $student = new User();
            $student->name = $validated['name'];
            $student->lastname = $validated['lastname'];
            $student->role = 'user';
            $student->save();

            return redirect()->route('students.index')->with('success', 'Student created!');
        }
        catch (\Exception $e) {
            dd($e);
        }
    }
}
