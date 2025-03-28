<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Client;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servers = \App\Models\Server::withCount('clients')->get();
        return view('servers.index', compact('servers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'cpu' => 'nullable|string|max:255',
            'memory_gb' => 'nullable|integer|min:0',
            'disk_gb' => 'nullable|integer|min:0',
            'type' => 'nullable|string|max:50',
            'hosting_company' => 'nullable|string|max:255',
            'monthly_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
    
        \App\Models\Server::create($validated);
    
        return redirect()->route('servers.index')->with('success', 'Ο server προστέθηκε με επιτυχία.');
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Server $server)
    {
        $server->load('clients');
        return view('servers.show', compact('server'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Server $server)
    {
        return view('servers.edit', compact('server'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Server $server)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'cpu' => 'nullable|string|max:255',
            'memory_gb' => 'nullable|integer|min:0',
            'disk_gb' => 'nullable|integer|min:0',
            'type' => 'nullable|string|max:50',
            'hosting_company' => 'nullable|string|max:255',
            'monthly_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
    
        $server->update($validated);
    
        return redirect()->route('servers.index')->with('success', 'Ο server ενημερώθηκε με επιτυχία.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Server $server)
    {
        $server->delete();
        return redirect()->route('servers.index')->with('success', 'Ο server διαγράφηκε με επιτυχία.');
    }
}
