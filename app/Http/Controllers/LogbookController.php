<?php

namespace App\Http\Controllers;

use App\Models\LogbookEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    public function index()
    {
        $logbookEntries = LogbookEntry::with('user')->latest()->paginate(10);
        return view('pages.logbook', compact('logbookEntries'));
    }

    public function create()
    {
        return redirect()->route('logbook.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Normal,High Priority',
        ]);

        LogbookEntry::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'user_id' => Auth::id(),
            'status' => 'Pending',
        ]);

        return redirect()->route('logbook.index')->with('success', 'Logbook entry berhasil dibuat!');
    }

    /**
     * Menampilkan detail satu entri.
     */
    public function show(LogbookEntry $logbook)
    {
        // Muat relasi user untuk ditampilkan di view
        $logbook->load('user'); 
        return view('pages.logbook.show', ['logbookEntry' => $logbook]);
    }

    /**
     * Menampilkan form untuk mengedit entri.
     */
    public function edit(LogbookEntry $logbook)
    {
        return view('pages.logbook.edit', ['logbookEntry' => $logbook]);
    }

    /**
     * Memperbarui entri di database.
     */
    public function update(Request $request, LogbookEntry $logbook)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Normal,High Priority',
            'status' => 'required|in:Pending,Completed',
        ]);

        $logbook->update($request->all());

        return redirect()->route('logbook.show', $logbook)->with('success', 'Logbook entry berhasil diperbarui!');
    }

    /**
     * Menghapus entri dari database.
     */
    public function destroy(LogbookEntry $logbook)
    {
        $logbook->delete();
        return redirect()->route('logbook.index')->with('success', 'Logbook entry berhasil dihapus!');
    }
}