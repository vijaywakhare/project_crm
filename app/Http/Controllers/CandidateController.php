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
        if (!$request->bearerToken()) {
            return response()->json(['error' => 'Your API key is invalid, missing, or has exceeded its quota'], 403);
        }

        try {
            $candidateInfo = $request->all();
            $valid = Validator::make($candidateInfo, [
                'first_name' => 'required|max:40',
                'last_name' => 'required|max:40',
                'email' => 'email|unique:candidate|max:100',
                'contact_number' => 'integer|max:100',
                'gender' => 'in:1,2',
                'specialization' => 'max:200',
                'work_ex_year' => 'integer|max:30',
                'candidate_dob' => 'nullable|date',
                'address' => 'max:500',
                'resume' => 'mimes:doc,docx,pdf|max:2048'
            ]);

            if ($valid->fails()) {
                return response()->json(['msg_error' => $validator->errors()], 422);
            }

            if($request->file('resume')){
                $fileName = time().'.'.$request->file->extension();
                $files->move('uploads',$fileName);
                $candidateInfo['resume']=$fileName;
            }  

            if (!empty($candidateInfo['candidate_dob'])) {
                $candidateInfo['candidate_dob'] = strtotime($candidateInfo['candidate_dob']);
            }

            $candidate = Candidate::create($candidateInfo);

            return response()->json($candidate, 201);

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid request - {$exception->getMessage}");
        }
    }

    public function showOneCandidate($id)
    {
        if (!$request->bearerToken()) {
            return response()->json(['error' => 'Your API key is invalid, missing, or has exceeded its quota'], 403);
        }

        if ($id) {
            return response()->json(Candidate::find($id));
        } else {
            throw new HttpException(400, "Invalid id OR id Not found");
        }
    }

    public function showAllCandidates()
    {
        if (!$request->bearerToken()) {
            return response()->json(['error' => 'Your API key is invalid, missing, or has exceeded its quota'], 403);
        }

        try {
            $limit = !empty($request->input('limit')) ? $request->input('limit') :  25;
            return response()->json(Candidate::paginate($limit));
        } catch (\Exception $e) {
            throw new HttpException(400, "Invalid request");
        }
    }

    public function showSearchCandidate($search)
    {
        if (!$request->bearerToken()) {
            return response()->json(['error' => 'Your API key is invalid, missing, or has exceeded its quota'], 403);
        }

        try {
            $search_condition = [];
            if (!empty($request->input('first_name'))) {
                $search_condition[] = ['first_name', 'like', '%' . $request->input('first_name') . '%'];
            }

            if (!empty($request->input('last_name'))) {
                $search_condition[] = ['last_name', 'like', '%' . $request->input('last_name') . '%'];
            }

            if (!empty($request->input('email'))) {
                $search_condition[] = ['email', 'like', '%' . $request->input('email') . '%'];
            }

            $limit = !empty($request->input('limit')) ? $request->input('limit') :  25;
            $search_result = Candidate::where($search_condition)->paginate($limit);

            return response()->json($search_result);
        } catch (\Exception $e) {
            throw new HttpException(400, "Invalid request");
        }
    }

    public function update($id, Request $request)
    {
        if (!$request->bearerToken()) {
            return response()->json(['error' => 'Your API key is invalid, missing, or has exceeded its quota'], 403);
        }

        $candidate = Candidate::findOrFail($id);
        $candidate->update($request->all());

        return response()->json($candidate, 200);
    }

    public function delete($id)
    {
        if (!$request->bearerToken()) {
            return response()->json(['error' => 'Your API key is invalid, missing, or has exceeded its quota'], 403);
        }

        candidate::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

    //
}
