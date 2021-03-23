<?php

namespace App\Http\Controllers;

use App\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(Request $request)
    {
        try {
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:candidate',
                'contact_number' => 'required|number'
            ]);

            $candidate = Candidate::create($request->all());

            return response()->json($candidate, 201);

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data - {$exception->getMessage}");
        }
    }

    public function showOneCandidate($id)
    {
        if (!$id) {
            throw new HttpException(400, "Invalid id");
        }
        
        return response()->json(Candidate::find($id));
    }

    public function showAllCandidates()
    {
        return response()->json(Candidate::all());
    }

    public function showSearchCandidate($search)
    {
        return response()->json(Candidate::find($search));
    }

    public function update($id, Request $request)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->update($request->all());

        return response()->json($candidate, 200);
    }

    public function delete($id)
    {
        candidate::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

    //
}
