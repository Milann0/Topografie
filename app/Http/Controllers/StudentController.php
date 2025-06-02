<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'user')->get();

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

    public function show(User $student)
    {
        return view('students.show', compact('student'));
    }

    public function destroy(User $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted!');
    }

    public function edit(User $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
            ]);

            $student->name = $validated['name'];
            $student->lastname = $validated['lastname'];
            $student->save();

            return redirect()->route('students.index')->with('success', 'Student updated!');
        } catch (\Exception $e) {
            dd($e);
        }
    }


}
